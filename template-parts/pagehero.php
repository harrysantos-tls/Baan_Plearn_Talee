<?php
/**
 * Page hero. Args: image (url), title, crumb, size (short|medium|tall).
 *
 * @package bpt
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$args  = wp_parse_args( isset( $args ) ? $args : array(), array( 'image' => '', 'title' => get_the_title(), 'crumb' => '', 'size' => 'short', 'position' => '50% 50%' ) );
?>
<section class="phero phero--<?php echo esc_attr( $args['size'] ); ?>">
	<?php if ( $args['image'] ) : ?>
		<img src="<?php echo esc_url( $args['image'] ); ?>" alt="" style="object-position:<?php echo esc_attr( $args['position'] ); ?>" fetchpriority="high">
	<?php endif; ?>
	<div class="phero__wash"></div>
	<div class="wrap phero__in">
		<?php if ( $args['crumb'] ) : ?><div class="phero__crumb"><?php echo esc_html( $args['crumb'] ); ?></div><?php endif; ?>
		<h1><?php echo esc_html( $args['title'] ); ?></h1>
	</div>
</section>
