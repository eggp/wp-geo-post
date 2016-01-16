<?php
/*
Plugin Name: WP GEO POST
Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
Description:
Version: 0.1
Author: eggp
Author URI: http://URI_Of_The_Plugin_Author
License: GPL2
*/

global $wp_geo_post_version;
$wp_geo_post_version = '0.1';

// TODO T - js detect geo and set cookit
// TODO F - admin page - list post types
// TODO F - meta box
// TODO - detect delete post
// TODO F - install delta db
// TODO T - shortcode
$wp_geo_post_plugin_dir_path = dirname(__FILE__);

if ( is_admin() ) {
	// delta db
	require_once($wp_geo_post_plugin_dir_path."/admin/functions.php");
	require_once($wp_geo_post_plugin_dir_path."/admin/admin-page.php");
	require_once($wp_geo_post_plugin_dir_path."/admin/meta-boxes.php");
} else {
	require_once( $wp_geo_post_plugin_dir_path . "/front-end/front_end_functions.php" );
}

require_once($wp_geo_post_plugin_dir_path."/shortcodes.php");

register_activation_hook( __FILE__, 'wp_geo_post_install' );
function wp_geo_post_install() {
	global $wpdb, $wp_geo_post_version;


	$installed_ver = get_option( "wp_geo_post_version" );

	if ( $installed_ver != $wp_geo_post_version ) {
		$table_name      = $wpdb->prefix . "wp_geo_post";
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE IF NOT EXISTS $table_name (
  id int(11) NOT NULL AUTO_INCREMENT,
  post_id int(11) NOT NULL,
  min_latitude float(10,6) NOT NULL,
  max_latitude float(10,6) NOT NULL,
  min_longitude float(10,6) NOT NULL,
  max_longitude float(10,6) NOT NULL,
  PRIMARY KEY (id),
  KEY post_id (post_id)
) ENGINE=InnoDB $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );

		update_option( "wp_geo_post_version", $wp_geo_post_version );
	}
}
