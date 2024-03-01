<?php
class Learn_Hunting_Theme_Activator
{
    public function __construct() {
        add_action('after_switch_theme', [$this, 'activate']);
    }

	public static function activate()
	{
		global $wpdb;
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

		$charset_collate = $wpdb->get_charset_collate();
		$mentor_hours_table = $wpdb->prefix.'lh_mentor_hours';

		$hours = "CREATE TABLE $mentor_hours_table (
				id int(11) NOT NULL AUTO_INCREMENT,
				user_id int(11) NOT NULL,
				name varchar(255) NOT NULL,
				date varchar(255) NOT NULL,
				start_time varchar(255) NOT NULL,
				end_time varchar(255) NOT NULL,
				total_hours varchar(255) NOT NULL,
				miles_driven varchar(255) NOT NULL,
				activity_name varchar(255) NOT NULL,
				activity_state varchar(255) NOT NULL,
				date_created int(11) NOT NULL,
				PRIMARY KEY  (id)
			) $charset_collate;";

		dbDelta($hours);

		flush_rewrite_rules();
	}
}

new Learn_Hunting_Theme_Activator();
