<?php
/**
 * Created by PhpStorm.
 * User: eggp
 * Date: 2016. 01. 16.
 * Time: 19:33
 */

add_action( 'wp_enqueue_scripts', 'wp_geo_post_front_end_wp_enqueue_scripts' );
function wp_geo_post_front_end_wp_enqueue_scripts()
{
	wp_enqueue_script( 'wp_geo_post_front_end/wp-geo-post-front-end.js',plugins_url("front-end/js/wp-geo-post-front-end.js",dirname(__FILE__)), array(), false, true );
}