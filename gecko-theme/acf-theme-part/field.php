<?php

if( ! defined( 'ABSPATH' ) ) exit;

if( !class_exists('gecko_acf_field_theme_part') ) {
    class gecko_acf_field_theme_part extends acf_field {
        function __construct( $settings ) {
            $this->name = 'theme_part';
            $this->label = __('Theme Part', 'gecko-designs');
            $this->category = 'content';

            $this->defaults = array(
                'theme_part_id'	=> null,
            );

            $this->settings = $settings;

            parent::__construct();
        }

        private function get_choices() {
            $choices = [];

            $theme_parts_posts = get_posts([
                'post_type' => 'gecko-theme-part',
                'posts_per_page' => -1,
                'order' => 'ASC',
                'orderby' => 'title',
            ]);

            foreach ($theme_parts_posts as $index => $theme_part) {
                $choices[$theme_part->ID] = $theme_part->post_title;
            }

            return $choices;
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
            $choices = $this->get_choices();
            ?>
            <select name="<?= esc_attr($field['name']) ?>">
                <option value="" <?= $field['value'] == '' ? 'selected' : '' ?>> --- </option>
                <?php foreach ($choices as $key => $value) { ?>
                    <option value="<?= $key ?>" <?= $field['value'] == $key ? 'selected' : '' ?>><?= $value ?></option>
                <?php } ?>
            </select>
            <?php
        }
    }

    new gecko_acf_field_theme_part( $this->settings );
}
