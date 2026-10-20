<?php
/**
 * Enquiry band, centred on a photograph.
 *
 * @package bpt
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<section class="cta">
	<picture><source srcset="<?php echo esc_url( bpt_asset( 'home/pool-sunset.webp' ) ); ?>" type="image/webp"><picture><source srcset="<?php echo esc_url( bpt_asset( 'home/pool-sunset.webp' ) ); ?>" type="image/webp"><img src="<?php echo esc_url( bpt_asset( 'home/pool-sunset.jpg' ) ); ?>" alt="" loading="lazy"></picture></picture>
	<div class="cta__wash"></div>
	<div class="cta__in">
		<h2><?php esc_html_e( 'Tell us when you would like to come', 'bpt' ); ?></h2>
		<p><?php printf( 'Send your dates and we will come back with availability, rates, and anything else you need to decide. Reservations are handled directly by <a href="https://www.theluxurysignature.com" target="_blank" rel="noopener" class="link-light">%s</a>.', esc_html( bpt_detail( 'agency' ) ) ); ?></p>
		<p class="cta__tls"><a href="https://www.theluxurysignature.com" target="_blank" rel="noopener" class="link-light">The Luxury Signature</a> &mdash; Luxury Villa Rentals in Phuket</p>
		<button type="button" class="btn-light" data-bpt-book><?php esc_html_e( 'Check availability', 'bpt' ); ?></button>
	</div>
</section>
