<?php
namespace GeckoTheme;

class Svg {
	public function __construct() {
        add_filter( 'upload_mimes', [ $this, 'allow_svg' ] );
        add_filter( 'wp_check_filetype_and_ext', [ $this, 'fix_mime_type_svg' ], 75, 4 );
        add_filter( 'wp_prepare_attachment_for_js', [ $this, 'fix_admin_preview' ], 10, 3 );
        add_filter( 'wp_get_attachment_image_src', [ $this, 'one_pixel_fix' ], 10, 4 );
        add_filter( 'admin_post_thumbnail_html', [ $this, 'featured_image_fix' ], 10, 3 );
        add_action( 'get_image_tag', [ $this, 'get_image_tag_override' ], 10, 6 );
        add_filter( 'wp_generate_attachment_metadata', [ $this, 'skip_svg_regeneration' ], 10, 2 );
        add_filter( 'wp_get_attachment_metadata', [ $this, 'metadata_error_fix' ], 10, 2 );
        add_filter( 'wp_get_attachment_image_attributes', [ $this, 'fix_direct_image_output' ], 10, 3 );
	}

    public function allow_svg( $mimes ) {
        $mimes['svg']  = 'image/svg+xml';
        $mimes['svgz'] = 'image/svg+xml';

        return $mimes;
    }

    public function fix_mime_type_svg( $data = null, $file = null, $filename = null, $mimes = null ) {
        $ext = isset( $data['ext'] ) ? $data['ext'] : '';
        if ( strlen( $ext ) < 1 ) {
            $exploded = explode( '.', $filename );
            $ext      = strtolower( end( $exploded ) );
        }
        if ( $ext === 'svg' ) {
            $data['type'] = 'image/svg+xml';
            $data['ext']  = 'svg';
        } elseif ( $ext === 'svgz' ) {
            $data['type'] = 'image/svg+xml';
            $data['ext']  = 'svgz';
        }

        return $data;
    }

    public function fix_admin_preview( $response, $attachment, $meta ) {

        if ( $response['mime'] == 'image/svg+xml' ) {
            $dimensions = $this->svg_dimensions( get_attached_file( $attachment->ID ) );

            if ( $dimensions ) {
                $response = array_merge( $response, $dimensions );
            }

            $possible_sizes = apply_filters( 'image_size_names_choose', array(
                'full'      => __( 'Full Size' ),
                'thumbnail' => __( 'Thumbnail' ),
                'medium'    => __( 'Medium' ),
                'large'     => __( 'Large' ),
            ) );

            $sizes = array();

            foreach ( $possible_sizes as $size => $label ) {
                $default_height = 2000;
                $default_width  = 2000;

                if ( 'full' === $size && $dimensions ) {
                    $default_height = $dimensions['height'];
                    $default_width  = $dimensions['width'];
                }

                $sizes[ $size ] = array(
                    'height'      => get_option( "{$size}_size_w", $default_height ),
                    'width'       => get_option( "{$size}_size_h", $default_width ),
                    'url'         => $response['url'],
                    'orientation' => 'portrait',
                );
            }

            $response['sizes'] = $sizes;
            $response['icon']  = $response['url'];
        }

        return $response;
    }

    public function one_pixel_fix( $image, $attachment_id, $size, $icon ) {
        if ( get_post_mime_type( $attachment_id ) == 'image/svg+xml' ) {
            $image['1'] = false;
            $image['2'] = false;
        }

        return $image;
    }

    public function featured_image_fix( $content, $post_id, $thumbnail_id ) {
        $mime = get_post_mime_type( $thumbnail_id );

        if ( 'image/svg+xml' === $mime ) {
            $content = sprintf( '<span class="svg">%s</span>', $content );
        }

        return $content;
    }

    function get_image_tag_override( $html, $id, $alt, $title, $align, $size ) {
        $mime = get_post_mime_type( $id );

        if ( 'image/svg+xml' === $mime ) {
            if ( is_array( $size ) ) {
                $width  = $size[0];
                $height = $size[1];
            } elseif ( 'full' == $size && $dimensions = $this->svg_dimensions( get_attached_file( $id ) ) ) {
                $width  = $dimensions['width'];
                $height = $dimensions['height'];
            } else {
                $width  = get_option( "{$size}_size_w", false );
                $height = get_option( "{$size}_size_h", false );
            }

            if ( $height && $width ) {
                $html = str_replace( 'width="1" ', sprintf( 'width="%s" ', $width ), $html );
                $html = str_replace( 'height="1" ', sprintf( 'height="%s" ', $height ), $html );
            } else {
                $html = str_replace( 'width="1" ', '', $html );
                $html = str_replace( 'height="1" ', '', $html );
            }

            $html = str_replace( '/>', ' role="img" />', $html );
        }

        return $html;
    }

    function skip_svg_regeneration( $metadata, $attachment_id ) {
        $mime = get_post_mime_type( $attachment_id );
        if ( 'image/svg+xml' === $mime ) {
            $additional_image_sizes = wp_get_additional_image_sizes();
            $svg_path               = get_attached_file( $attachment_id );
            $upload_dir             = wp_upload_dir();
            // get the path relative to /uploads/ - found no better way:
            $relative_path = str_replace( $upload_dir['basedir'], '', $svg_path );
            $filename      = basename( $svg_path );

            $dimensions = $this->svg_dimensions( $svg_path );

            if ( ! $dimensions ) {
                return $metadata;
            }

            $metadata = array(
                'width'  => intval( $dimensions['width'] ),
                'height' => intval( $dimensions['height'] ),
                'file'   => $relative_path
            );

            // Might come handy to create the sizes array too - But it's not needed for this workaround! Always links to original svg-file => Hey, it's a vector graphic! ;)
            $sizes = array();
            foreach ( get_intermediate_image_sizes() as $s ) {
                $sizes[ $s ] = array( 'width' => '', 'height' => '', 'crop' => false );

                if ( isset( $additional_image_sizes[ $s ]['width'] ) ) {
                    // For theme-added sizes
                    $sizes[ $s ]['width'] = intval( $additional_image_sizes[ $s ]['width'] );
                } else {
                    // For default sizes set in options
                    $sizes[ $s ]['width'] = get_option( "{$s}_size_w" );
                }

                if ( isset( $additional_image_sizes[ $s ]['height'] ) ) {
                    // For theme-added sizes
                    $sizes[ $s ]['height'] = intval( $additional_image_sizes[ $s ]['height'] );
                } else {
                    // For default sizes set in options
                    $sizes[ $s ]['height'] = get_option( "{$s}_size_h" );
                }

                if ( isset( $additional_image_sizes[ $s ]['crop'] ) ) {
                    // For theme-added sizes
                    $sizes[ $s ]['crop'] = intval( $additional_image_sizes[ $s ]['crop'] );
                } else {
                    // For default sizes set in options
                    $sizes[ $s ]['crop'] = get_option( "{$s}_crop" );
                }

                $sizes[ $s ]['file']      = $filename;
                $sizes[ $s ]['mime-type'] = $mime;
            }
            $metadata['sizes'] = $sizes;
        }

        return $metadata;
    }

    function metadata_error_fix( $data, $post_id ) {

        // If it's a WP_Error regenerate metadata and save it
        if ( is_wp_error( $data ) ) {
            $data = wp_generate_attachment_metadata( $post_id, get_attached_file( $post_id ) );
            wp_update_attachment_metadata( $post_id, $data );
        }

        return $data;
    }

    protected function svg_dimensions( $svg ) {
        $svg    = @simplexml_load_file( $svg );
        $width  = 0;
        $height = 0;
        if ( $svg ) {
            $attributes = $svg->attributes();
            if ( isset( $attributes->width, $attributes->height ) && is_numeric( $attributes->width ) && is_numeric( $attributes->height ) ) {
                $width  = floatval( $attributes->width );
                $height = floatval( $attributes->height );
            } elseif ( isset( $attributes->viewBox ) ) {
                $sizes = explode( ' ', $attributes->viewBox );
                if ( isset( $sizes[2], $sizes[3] ) ) {
                    $width  = floatval( $sizes[2] );
                    $height = floatval( $sizes[3] );
                }
            } else {
                return false;
            }
        }

        return array(
            'width'       => $width,
            'height'      => $height,
            'orientation' => ( $width > $height ) ? 'landscape' : 'portrait'
        );
    }

    public function fix_direct_image_output( $attr, $attachment, $size = 'thumbnail' ) {

        // If we're not getting a WP_Post object, bail early.
        // @see https://wordpress.org/support/topic/notice-trying-to-get-property-id/
        if ( ! $attachment instanceof WP_Post ) {
            return $attr;
        }

        $mime = get_post_mime_type( $attachment->ID );
        if ( 'image/svg+xml' === $mime ) {
            $default_height = 100;
            $default_width  = 100;

            $dimensions = $this->svg_dimensions( get_attached_file( $attachment->ID ) );

            if ( $dimensions ) {
                $default_height = $dimensions['height'];
                $default_width  = $dimensions['width'];
            }

            $attr['height'] = $default_height;
            $attr['width']  = $default_width;
        }

        return $attr;
    }

}
