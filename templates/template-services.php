<?php
/**
 * Template Name: Services and features (tiles)
 *
 * @package bpt
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
$d = bpt_data();
$d = isset( $d['services-features-overview'] ) ? $d['services-features-overview'] : array( 'features' => array(), 'services' => array() );

get_template_part( 'template-parts/pagehero', null, array(
	'image' => bpt_asset( 'home/lifestyle.jpg' ),
	'title' => get_the_title(),
	'crumb' => __( 'The villa', 'bpt' ),
	'size'  => 'short',
	'position' => '60% 55%',
) );
?>
<section class="block">
	<div class="wrap">
		<h2 class="h-sec"><?php esc_html_e( 'Features and amenities', 'bpt' ); ?></h2>
		<div class="stiles"><?php foreach ( $d['features'] as $t ) : ?><div class="stile"><?php echo esc_html( $t ); ?></div><?php endforeach; ?></div>
		<h2 class="h-sec h-sec--gap"><?php esc_html_e( 'Services', 'bpt' ); ?></h2>
		<div class="stiles"><?php foreach ( $d['services'] as $t ) : ?><div class="stile"><?php echo esc_html( $t ); ?></div><?php endforeach; ?></div>
		<h2 class="h-sec h-sec--gap"><?php esc_html_e( 'Arrival', 'bpt' ); ?></h2>
		<div class="arrival"><img src="<?php echo esc_url( bpt_asset( 'home/entrance.jpg' ) ); ?>" alt="<?php esc_attr_e( 'The entrance to Baan Plearn Talee', 'bpt' ); ?>" loading="lazy"></div>
	</div>
</section>
<?php
get_template_part( 'template-parts/cta' );
get_footer();
