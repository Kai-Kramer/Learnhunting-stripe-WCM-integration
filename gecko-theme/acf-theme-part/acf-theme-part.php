<?php

if( ! defined( 'ABSPATH' ) ) exit;

if( !class_exists('gecko_acf_plugin_theme_part') ) {
    class gecko_acf_plugin_theme_part {
        var $settings;

        function __construct() {
            $this->settings = array(
                'version'	=> '1.0.0',
                'url'		=> plugin_dir_url( __FILE__ ),
                'path'		=> plugin_dir_path( __FILE__ )
            );

            add_action('acf/include_field_types', 	array($this, 'include_field')); // v5
            add_action('acf/register_fields', 		array($this, 'include_field')); // v4
        }

        function include_field() {
            include_once('field.php');
        }
    }

    new gecko_acf_plugin_theme_part();
}
