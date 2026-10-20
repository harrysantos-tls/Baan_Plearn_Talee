<?php
/**
 * Fallback template.
 *
 * @package bpt
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
?>
<section class="block"><div class="wrap wrap--narrow prose">
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
		<h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
		<?php the_excerpt(); ?>
	<?php endwhile; else : ?>
		<h1><?php esc_html_e( 'Nothing here', 'bpt' ); ?></h1>
		<p><?php esc_html_e( 'The page you were looking for could not be found.', 'bpt' ); ?></p>
	<?php endif; ?>
</div></section>
<?php get_footer(); ?>
