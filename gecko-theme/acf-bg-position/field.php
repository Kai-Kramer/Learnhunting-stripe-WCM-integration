<?php

if( ! defined( 'ABSPATH' ) ) exit;

if( !class_exists('gecko_acf_field_bg_position') ) {
    class gecko_acf_field_bg_position extends acf_field {
        function __construct( $settings ) {
            $this->name = 'bg_position';
            $this->label = __('Background Position', 'gecko-designs');
            $this->category = 'layout';

            $this->defaults = [
                "background_position" => "center center",
            ];

            $this->settings = $settings;

            parent::__construct();
        }

        function render_field_settings( $field ) {
            // acf_render_field_setting( $field, array(
            //     'label'			=> __('Theme Part','gecko-designs'),
            //     'instructions'	=> __('Choose a custom content section to be output in template','gecko-designs'),
            //     'type'			=> 'select',
            //     'name'			=> 'theme_part_id',
            //     'choices'		=> $choices,
            // ));
        }

        function render_field( $field ) {
            $name = esc_attr( $field["name"] );
            ?>
            <table>
                <tbody>
                    <tr>
                        <td><input type="radio" name="<?= $name ?>" value="left top" <?= $field['value'] == "left top" ? 'checked="checked"' : '' ?> /></td>
                        <td><input type="radio" name="<?= $name ?>" value="center top" <?= $field['value'] == "center top" ? 'checked="checked"' : '' ?> /></td>
                        <td><input type="radio" name="<?= $name ?>" value="right top" <?= $field['value'] == "right top" ? 'checked="checked"' : '' ?> /></td>
                    </tr>
                    <tr>
                        <td><input type="radio" name="<?= $name ?>" value="left center" <?= $field['value'] == "left center" ? 'checked="checked"' : '' ?> /></td>
                        <td><input type="radio" name="<?= $name ?>" value="center center" <?= $field['value'] == "center center" ? 'checked="checked"' : '' ?> /></td>
                        <td><input type="radio" name="<?= $name ?>" value="right center" <?= $field['value'] == "right center" ? 'checked="checked"' : '' ?> /></td>
                    </tr>
                    <tr>
                        <td><input type="radio" name="<?= $name ?>" value="left bottom" <?= $field['value'] == "left bottom" ? 'checked="checked"' : '' ?> /></td>
                        <td><input type="radio" name="<?= $name ?>" value="center bottom" <?= $field['value'] == "center bottom" ? 'checked="checked"' : '' ?> /></td>
                        <td><input type="radio" name="<?= $name ?>" value="right bottom" <?= $field['value'] == "right bottom" ? 'checked="checked"' : '' ?> /></td>
                    </tr>
                </tbody>
            </table>
            <?php
        }
    }

    new gecko_acf_field_bg_position( $this->settings );
}
