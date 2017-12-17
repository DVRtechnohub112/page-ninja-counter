<?php

/**
 * Fired during plugin activation
 *
 * @link       https://profiles.wordpress.org/rishishah
 * @since      1.0.0
 *
 * @package    Page_Ninja_Counter
 * @subpackage Page_Ninja_Counter/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Page_Ninja_Counter
 * @subpackage Page_Ninja_Counter/includes
 * @author     Rishi Shah <rishi41194@gmail.com>
 */
class Page_Ninja_Counter_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		global $wpdb;

		$table_name = $wpdb->prefix . 'pnc_count';

		$sql = "CREATE TABLE $table_name (
		 id mediumint(10) unsigned NOT NULL AUTO_INCREMENT,
		 page_id mediumint(10) NOT NULL,
		 count mediumint(2) NOT NULL,
		 ip_address VARCHAR(25) NOT NULL,
		 user_agent VARCHAR(100) NOT NULL,
		 country_name VARCHAR(20) NOT NULL,
		 status VARCHAR(10) NOT NULL,
		 date datetime,
		 modified_date datetime,
		 PRIMARY KEY  (id) );";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );

	}

}
