<?php
/**
 * Created by PhpStorm.
 * User: eggp
 * Date: 2016. 01. 16.
 * Time: 19:57
 */

function wp_geo_post_add_meta_box() {
	$screens = get_option("wp_geo_post/post_types",array());

	$post_type = get_post_type();
	if(count($screens) > 0 && array_search($post_type,$screens)) {
		add_meta_box(
				'wp_geo_post',
				'WP GEO POST',
				'wp_geo_post_meta_box_callback',
				$post_type,
				'side'
		);
	}
}

add_action( 'add_meta_boxes', 'wp_geo_post_add_meta_box' );

function wp_geo_post_meta_box_callback( $post ) {
	global $wpdb;
	$coordinate = $wpdb->get_row(
		$wpdb->prepare( "SELECT * FROM {$wpdb->prefix}wp_geo_post as wgp WHERE wgp.post_id = %d", $post->ID )
		,
		ARRAY_A );
	if ( count( $coordinate ) == 0 ) {
		$coordinate = array(
			"id"            => "null",
			"min_latitude"  => "",
			"max_latitude"  => "",
			"min_longitude" => "",
			"max_longitude" => "",
		);
	}

	wp_nonce_field( "w4efhakjsef", "43iutfrhiustr4fg" );
	echo '<label for="wp_geo_post_min_latitude">';
	echo "Minimum latitude";
	echo '</label> ';
	echo '<input type="text" id="wp_geo_post_min_latitude" name="wp_geo_post_min_latitude" value="' . esc_attr( $coordinate['min_latitude'] ) . '" size="25" />';
	echo '<label for="wp_geo_post_max_latitude">';
	echo "Maximum latitude";
	echo '</label> ';
	echo '<input type="text" id="wp_geo_post_max_latitude" name="wp_geo_post_max_latitude" value="' . esc_attr( $coordinate['max_latitude'] ) . '" size="25" />';
	echo '<label for="wp_geo_post_min_longitude">';
	echo "Minimum longitude";
	echo '</label> ';
	echo '<input type="text" id="wp_geo_post_min_longitude" name="wp_geo_post_min_longitude" value="' . esc_attr( $coordinate['min_longitude'] ) . '" size="25" />';
	echo '<label for="wp_geo_post_max_longitude">';
	echo "Maximum longitude";
	echo '</label> ';
	echo '<input type="text" id="wp_geo_post_max_longitude" name="wp_geo_post_max_longitude" value="' . esc_attr( $coordinate['max_longitude'] ) . '" size="25" />';
	echo '<input type="hidden" name="wp_geo_post_update_id" value="' . $coordinate['id'] . '"/>';
}

/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 */
function wp_geo_post_save_meta_box_data( $post_id ) {

	// check isset nonce
	if ( ! isset( $_POST['43iutfrhiustr4fg'] ) ) {
		return;
	}

	// check nonce
	if ( ! wp_verify_nonce( $_POST['43iutfrhiustr4fg'], 'w4efhakjsef' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

//	$post_types = get_option("wp_geo_post/post_types",array());
//	if ( isset( $_POST['post_type'] ) && array_search($_POST['post_type'],$post_types) === false) {
//
//		if ( ! current_user_can( 'edit_page', $post_id ) ) {
//			return;
//		}
//
//	} else {
//
//		if ( ! current_user_can( 'edit_post', $post_id ) ) {
//			return;
//		}
//	}

	if ( ! isset( $_POST['wp_geo_post_min_latitude'] )
	     || ! isset( $_POST['wp_geo_post_max_latitude'] )
	     || ! isset( $_POST['wp_geo_post_min_longitude'] )
	     || ! isset( $_POST['wp_geo_post_max_longitude'] )
	     || ! isset( $_POST['wp_geo_post_update_id'] )
	) {
		return;
	}

	global $wpdb;
	// TODO lehet jobb lenne a post type-ot is eltarolni performance miatt!
	$wpdb->replace(
		$wpdb->prefix . "wp_geo_post",
		array(
			"id"            => $_POST['wp_geo_post_update_id'],
			"post_id"       => $post_id,
			"min_latitude"  => $_POST['wp_geo_post_min_latitude'],
			"max_latitude"  => $_POST['wp_geo_post_max_latitude'],
			"min_longitude" => $_POST['wp_geo_post_min_longitude'],
			"max_longitude" => $_POST['wp_geo_post_max_longitude'],
		),
		array(
			"%d",
			"%d",
			"%d",
			"%d",
			"%d",
		)
	);
	$_POST['wp_geo_post_update_id'] = $wpdb->insert_id;
}

add_action( 'save_post', 'wp_geo_post_save_meta_box_data' );