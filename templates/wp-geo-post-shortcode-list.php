<?php
/**
 * Created by PhpStorm.
 * User: eggp
 * Date: 2016. 01. 16.
 * Time: 21:38
 *
 * example list template
 */
?>
<?php if ( have_posts() ): ?>
	<ul class="wp-geo-post-list wp-geo-post-list-<?php echo $GLOBALS['wp-geo-post']['shortcode_id']; ?>">
		<?php while ( have_posts() ): the_post(); ?>
			<li>
				<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
			</li>
		<?php endwhile; ?>
	</ul>
<?php else: ?>
	Nincs lokációnak megfelelő tartalom
<?php endif; ?>
