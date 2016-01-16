<?php
/**
 * Created by PhpStorm.
 * User: eggp
 * Date: 2016. 01. 16.
 * Time: 20:52
 */
add_action( 'admin_menu', 'wp_geo_post_add_admin_pages' );
function wp_geo_post_add_admin_pages() {
	add_menu_page( 'WP GEO POST',
		'WP GEO POST',
		'manage_options',
		'wp-geo-post',
		'wp_geo_post_admin_form' );
}

function wp_geo_post_admin_form() {
	$selected_post_types = get_option( 'wp_geo_post/post_types', array() );
	$post_types          = get_post_types(
		array(),
		'objects'
	);
	?>
	<form method="post" action="admin-post.php" enctype="application/x-www-form-urlencoded">
		<table>
			<thead>
			<tr>
				<th>Name</th>
				<th></th>
			</tr>
			</thead>
			<tbody>
			<?php foreach ( $post_types as $post_type ): ?>
				<tr>
					<td>
						<?php /* azert nem a ->name mert az attachmentnel erdekes, uj letrehozas van a post type neve helyett :( */echo $post_type->labels->name_admin_bar; ?>
					</td>
					<td>
						<input type="checkbox" name="wp_geo_post_selected_post_types[]" value="<?php echo $post_type->name; ?>"
							<?php echo( ( array_search( $post_type->name,
									$selected_post_types ) !== false ) ? "checked='checked'" : "" ); ?>
						/>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
			<tfoot>
			<tr>
				<td>
					<?php wp_nonce_field( "wrefsf3rwref", "o43ithjfosatg" ); ?>
					<input type="hidden" name="action" value="wp_geo_post_save_selected_post_types"/>
					<input type="submit" value="save"/>
				</td>
			</tr>
			</tfoot>
		</table>
	</form>
	<?php
}

add_action( 'admin_post_wp_geo_post_save_selected_post_types', 'wp_geo_post_save_selected_post_types' );
function wp_geo_post_save_selected_post_types() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'You are not allowed to be on this page.' );
	}
	check_admin_referer( 'wrefsf3rwref', 'o43ithjfosatg' );

	if ( isset( $_POST['wp_geo_post_selected_post_types'] )
	&& is_array($_POST['wp_geo_post_selected_post_types'])) {
		update_option( "wp_geo_post/post_types", $_POST['wp_geo_post_selected_post_types']);
	}

	wp_redirect( admin_url( 'admin.php?page=wp-geo-post' ) );
	exit;
}