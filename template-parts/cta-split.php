<?php
/**
 * Enquiry band, split: photograph on the left, message and contact lines on the right.
 * Used on the home page. Other pages use cta.php.
 *
 * @package bpt
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$tel = preg_replace( '/[^0-9+]/', '', bpt_detail( 'phone' ) );
?>
<section class="ctas">
	<div class="ctas__photo"><img src="<?php echo esc_url( bpt_asset( 'home/pool-sunset.jpg' ) ); ?>" alt="<?php esc_attr_e( 'The pool at Baan Plearn Talee', 'bpt' ); ?>" loading="lazy"></div>
	<div class="ctas__body">
		<div class="ctas__in">
			<h2><?php esc_html_e( 'Tell us when you would like to come', 'bpt' ); ?></h2>
			<p><?php printf( esc_html__( 'Send your dates and we will come back with availability, rates, and anything else you need to decide. Reservations are handled directly by %s.', 'bpt' ), esc_html( bpt_detail( 'agency' ) ) ); ?></p>
			<button type="button" class="btn-light" data-bpt-book><?php esc_html_e( 'Check availability', 'bpt' ); ?></button>
			<div class="ctas__lines">
				<a href="mailto:<?php echo esc_attr( bpt_detail( 'email' ) ); ?>"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="1"/><path d="M3.5 6.5l8.5 6.5 8.5-6.5"/></svg><span><?php echo esc_html( bpt_detail( 'email' ) ); ?></span></a>
				<a href="tel:<?php echo esc_attr( $tel ); ?>"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2z"/></svg><span><?php echo esc_html( bpt_detail( 'phone' ) ); ?></span></a>
			</div>
		</div>
	</div>
</section>
