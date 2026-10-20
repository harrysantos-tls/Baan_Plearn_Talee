<?php
/**
 * Template Name: Video
 *
 * @package bpt
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
get_template_part( 'template-parts/pagehero', null, array(
	'image' => bpt_asset( 'home/aerial-night.jpg' ),
	'title' => get_the_title(),
	'crumb' => __( 'The villa', 'bpt' ),
	'size'  => 'short',
) );
?>
<section class="block">
	<div class="wrap">
		<div class="villa-video">
			<div class="villa-video__wrap">
				<iframe
					src="https://www.youtube.com/embed/QS9lC535t4M?rel=0&modestbranding=1"
					title="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"
					frameborder="0"
					allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
					allowfullscreen
				></iframe>
			</div>
		</div>
	</div>
</section>
<?php
get_template_part( 'template-parts/cta' );
get_footer();
