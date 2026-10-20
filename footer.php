<?php
/**
 * Footer.
 *
 * @package bpt
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$zoho  = get_page_by_path( 'contact-us' );
?>
</main>

<footer class="site-foot">
	<div class="wrap">
		<div class="foot-grid">
			<div class="foot-brand">
				<img src="<?php echo esc_url( bpt_logo( 'light' ) ); ?>" alt="<?php echo esc_attr( bpt_villa_name() ); ?>">
				<p><?php echo esc_html( bpt_detail( 'address' ) ); ?></p>
			</div>
			<div class="foot-col">
				<div class="foot-title"><?php esc_html_e( 'Reservations', 'bpt' ); ?></div>
				<a href="mailto:<?php echo esc_attr( bpt_detail( 'email' ) ); ?>"><?php echo esc_html( bpt_detail( 'email' ) ); ?></a>
				<a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', bpt_detail( 'phone' ) ) ); ?>"><?php echo esc_html( bpt_detail( 'phone' ) ); ?></a>
				<a href="<?php echo esc_url( bpt_page_url( 'contact-us' ) ); ?>"><?php esc_html_e( 'Send an enquiry', 'bpt' ); ?></a>
			</div>
			<div class="foot-col">
				<div class="foot-title"><?php esc_html_e( 'The villa', 'bpt' ); ?></div>
				<a href="<?php echo esc_url( bpt_page_url( 'services-features-overview' ) ); ?>"><?php esc_html_e( 'Services and features', 'bpt' ); ?></a>
				<a href="<?php echo esc_url( bpt_page_url( 'floor-plan' ) ); ?>"><?php esc_html_e( 'Floor plan', 'bpt' ); ?></a>
				<a href="<?php echo esc_url( bpt_page_url( 'gallery' ) ); ?>"><?php esc_html_e( 'Gallery', 'bpt' ); ?></a>
				<a href="<?php echo esc_url( bpt_page_url( 'location' ) ); ?>"><?php esc_html_e( 'Location', 'bpt' ); ?></a>
			</div>
			<div class="foot-col">
				<div class="foot-title"><?php esc_html_e( 'Good to know', 'bpt' ); ?></div>
				<a href="<?php echo esc_url( bpt_page_url( 'reviews' ) ); ?>"><?php esc_html_e( 'Guest reviews', 'bpt' ); ?></a>
				<a href="<?php echo esc_url( bpt_page_url( 'terms-and-conditions' ) ); ?>"><?php esc_html_e( 'Terms and conditions', 'bpt' ); ?></a>
				<a href="<?php echo esc_url( bpt_page_url( 'contact-us' ) ); ?>"><?php esc_html_e( 'Contact us', 'bpt' ); ?></a>
			</div>
		</div>
		<div class="foot-bar">
			<span>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php echo esc_html( bpt_villa_name() ); ?>. <?php esc_html_e( 'All rights reserved.', 'bpt' ); ?></span>
			<span><?php printf( 'Marketed by <a href="https://www.theluxurysignature.com" target="_blank" rel="noopener">%s</a>', esc_html( bpt_detail( 'agency' ) ) ); ?></span>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
