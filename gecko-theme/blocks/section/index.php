<?php
// Create id attribute allowing for custom "anchor" value.
$id = 'gecko-theme-section-' . $block['id'];
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}

$is_dismissable = get_field('dismissable') ? true : false;
if ($is_preview) {
    $is_dismissable = false;
}
$enable_rounded_corners = get_field("rounded_corners");

// Create class attribute allowing for custom "className" and "align" values.
$baseClassName = 'gecko-theme-section';
$className = $baseClassName;
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}

$template = [
    [
        'core/paragraph',
        [
            'content' => 'Lorem ipsum dolor sit adipiscing non eleifend lobortis ultricies lectus eleifend vestibulum. Eleifend porta proin lorem mi faucibus luctus vivamus convallis sed. Sollicitudin feugiat ornare luctus pharetra mauris molestie porta laoreet ipsum laoreet ultrices lobortis. Augue sapien labore egestas ultricies molestie fusce sollicitudin lobortis sollicitudin. Egestas nibh eu morbi ornare fringilla elit mauris laoreet scelerisque ornare morbi.',
        ],
    ]
];

$wrapper_style = "";

if ($bg_image = get_field("background_image")) {
    $wrapper_style .= "background-image: url('" . $bg_image['url'] . "'); ";
}

$fill_mode = get_field("background_fill_mode");
$wrapper_style .= "background-size: $fill_mode; ";

if ($bg_pos = get_field("background_position")) {
    $wrapper_style .= "background-position: $bg_pos; ";
}

if ($bg_color = get_field("background_color")) {
    $wrapper_style .= "background-color: $bg_color; ";
}

$enable_overlay = false;
$overlay_style = "";
if ($overlay_color = get_field("background_overlay_color")) {
    $enable_overlay = true;
    $overlay_opacity = get_field("background_overlay_opacity");

    $overlay_opacity = number_format($overlay_opacity / 100, 2);

    $overlay_style .= "background-color: $overlay_color; ";
    $overlay_style .= "opacity: $overlay_opacity; ";

}

$frame_style = "";
$enable_rounded_frame = get_field("rounded_frame");
if ($enable_rounded_frame && $frame_color = get_field("frame_color")) {
    $frame_style = "box-shadow: 0 0 0 10rem $frame_color; ";
}
?>

<div
    id="<?php echo esc_attr($id); ?>"
    class="<?= esc_attr($className); ?>"
    data-is-preview="<?= $is_preview ? "true" : "false" ?>"
    data-padding="<?= get_field("padding") ?>"
    data-row-width="<?= get_field("row_width") ?>"
    data-rounded-frame="<?= $enable_rounded_frame ? "true" : "false" ?>"
    data-rounded-corners="<?= $enable_rounded_corners ? "true" : "false" ?>"
    data-dismissable="<?= $is_dismissable ? "true" : "false" ?>"
    style="<?= $wrapper_style ?>"
>
    <?php if ($enable_overlay): ?>
        <div class="<?= $baseClassName ?>__overlay" style="<?= $overlay_style ?>"></div>
    <?php endif ?>

    <?php if ($enable_rounded_frame): ?>
        <div class="<?= $baseClassName ?>__rounded-frame" style="<?= $frame_style ?>"></div>
    <?php endif ?>

    <?php if ($is_dismissable): ?>
        <button class="<?= $baseClassName ?>__dismiss-button">
            <svg width="500" height="500" viewBox="0 0 500 500" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M294.235 249.947L440.838 103.382C453.054 91.1698 453.054 71.3744 440.838 59.1592C428.623 46.9469 408.822 46.9469 396.604 59.1592L250 205.724L103.396 59.1592C91.1808 46.9469 71.3801 46.9469 59.1616 59.1592C46.9461 71.3714 46.9461 91.1668 59.1616 103.382L205.765 249.947L59.1616 396.512C46.9461 408.724 46.9461 428.519 59.1616 440.734C65.0593 446.631 73.0637 450 81.0687 450C89.0736 450 97.0779 447.05 102.976 440.734L250.001 294.17L396.604 440.734C402.502 446.631 410.507 450 418.512 450C426.517 450 434.521 447.05 440.419 440.734C452.634 428.522 452.634 408.727 440.419 396.512L294.235 249.947Z" fill="black" />
            </svg>
        </button>
    <?php endif ?>

    <div class="<?= $baseClassName ?>__inner">
        <InnerBlocks template="<?= esc_attr( wp_json_encode( $template ) ) ?>" />
    </div>
</div>
