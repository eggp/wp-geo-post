<?php
/**
 * Created by PhpStorm.
 * User: eggp
 * Date: 2016. 01. 16.
 * Time: 21:11
 */

add_shortcode( "wpgeopost", "wp_geo_post_shortcode" );
function wp_geo_post_shortcode( $attr, $content = "" ) {
	$list_type = ( ! isset( $attr['list-type'] ) ? null : $attr['list-type'] );
	$count     = ( ! isset( $attr['count'] ) ? null : $attr['count'] );
	// TODO time() helyett global index
	$shortcode_id = ( ! isset( $attr['id'] ) ? time() : $attr['id'] );

	if ( ! isset( $_COOKIE['wpgeopost_coords_latitude'] ) ||
	     strlen( $_COOKIE['wpgeopost_coords_latitude'] ) == 0 ||
	     ! isset( $_COOKIE['wpgeopost_coords_longitude'] ) ||
	     strlen( $_COOKIE['wpgeopost_coords_longitude'] ) == 0
	) {
		return wp_geo_post_load_not_found_template();
	}

	$post_types = get_option( "wp_geo_post/post_types", array() );
	if ( count( $post_types ) == 0 ) {
		return "Beállítási hiba! Kérem ellenőrizze a beállításokat!";
	}
	if ( $list_type != null ) {
		if ( strpos( $list_type, "," ) !== false ) {
			$post_types = explode( ",", $list_type );
		} else {
			$post_types = array( $list_type );
		}
	}

	// TODO post tabla csatolasa a post tipus miatt
	global $wpdb;
	$post_ids = $wpdb->get_col(
		$wpdb->prepare(
			"SELECT
				wgp.post_id
			 FROM
			 	{$wpdb->prefix}wp_geo_post as wgp
			 WHERE
			  	wgp.min_latitude <= %d AND
			  	wgp.max_latitude >= %d AND
			  	wgp.min_longitude <= %d AND
			  	wgp.max_longitude >= %d
			",
			$_COOKIE['wpgeopost_coords_latitude'],
			$_COOKIE['wpgeopost_coords_latitude'],
			$_COOKIE['wpgeopost_coords_longitude'],
			$_COOKIE['wpgeopost_coords_longitude']
		)
	);
	if ( count( $post_ids ) == 0 ) {
		return wp_geo_post_load_not_found_template();
	}
	// le kell kerni azon coordinatakat amik a jelenlegi kozott vannak de csatolni kell a post tablat a tipus miatt
	// wp query keszitese
	global $wp_query;
	$wp_query               = new WP_Query(
		array(
			"post_type"     => $post_types,
			"post__in"      => $post_ids,
			"post_per_page" => $count,
		)
	);
	$GLOBALS['wp-geo-post'] = array(
		"shortcode_id" => $shortcode_id,
	);
	ob_start();
	if ( $overridden_template = locate_template( 'wp-geo-post-shortcode-list-' . $shortcode_id . '.php' ) ) {
		load_template( $overridden_template );
	} else if ( $overridden_template = locate_template( 'wp-geo-post-shortcode-list.php' ) ) {
		load_template( $overridden_template );
	} else {
		load_template( dirname( __FILE__ ) . '/templates/wp-geo-post-shortcode-list.php' );
	}
	unset( $GLOBALS['wp-geo-post'] );
	wp_reset_query();

	$content = ob_get_contents();
	ob_end_clean();

	return $content;
}

function wp_geo_post_load_not_found_template() {
	ob_start();
	if ( $overridden_template = locate_template( 'wp-geo-post-shortcode-not-found.php' ) ) {
		load_template( $overridden_template );
	} else {
		load_template( dirname( __FILE__ ) . '/templates/wp-geo-post-shortcode-not-found.php' );
	}

	$content = ob_get_contents();
	ob_end_clean();

	return $content;
}