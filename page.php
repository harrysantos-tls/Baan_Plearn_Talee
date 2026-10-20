<?php
/**
 * Generic page.
 *
 * @package bpt
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
while ( have_posts() ) :
	the_post();
	get_template_part( 'template-parts/pagehero', null, array( 'image' => bpt_asset( 'home/aerial-day.jpg' ), 'title' => get_the_title(), 'size' => 'short', 'position' => '62% 60%' ) );
	?>
	<section class="block"><div class="wrap wrap--narrow prose"><?php the_content(); ?></div></section>
	<?php
endwhile;
get_footer();
