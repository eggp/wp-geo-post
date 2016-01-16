<?php
/**
 * Created by PhpStorm.
 * User: eggp
 * Date: 2016. 01. 16.
 * Time: 21:45
 */

add_action( 'admin_init', 'codex_init' );
function codex_init() {
	add_action( 'delete_post', 'wp_geo_post_detect_delete_post', 10 );
}

function wp_geo_post_detect_delete_post( $pid ) {
	global $wpdb;

	$wpdb->delete(
		$wpdb->prefix."wp_geo_post",
		array("post_id"=>$pid),
		array("%d")
	);
}