<?php
/**
 * Template Name: Floor plan (viewer)
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
$levels = array(
	array( __( 'Upper level', 'bpt' ), 'https://baanplearntalee.com/wp-content/uploads/2026/10/1_upper_level-scaled.png' ),
	array( __( 'Mid level', 'bpt' ),   'https://baanplearntalee.com/wp-content/uploads/2026/10/2_mid_level-scaled.png'   ),
	array( __( 'Lower level', 'bpt' ), 'https://baanplearntalee.com/wp-content/uploads/2026/10/3_lower_level-scaled.png' ),
);
?>
<section class="block">
	<div class="wrap">
		<div class="fplan">
			<?php foreach ( $levels as $l ) : ?>
			<div class="fplan__item">
				<div class="fplan__label"><?php echo esc_html( $l[0] ); ?></div>
				<img src="<?php echo esc_url( $l[1] ); ?>" alt="<?php echo esc_attr( $l[0] ); ?>" loading="lazy">
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
<?php
get_template_part( 'template-parts/cta' );
get_footer();
