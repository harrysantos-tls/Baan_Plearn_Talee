<?php
/**
 * The booking bar of the Guesty test page: the search bar of the Guesty Booking Engine plugin.
 *
 * @package bpt
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<section class="book-strip book-strip--engine" id="booking" aria-label="<?php esc_attr_e( 'Check availability', 'bpt' ); ?>">
	<div class="wrap">
		<?php
		$bar = bpt_engine_ready() ? do_shortcode( '[guesty_calendar_picker listing_id="' . esc_attr( bpt_engine_listing() ) . '"]' ) : '';
		if ( $bar && false !== strpos( $bar, 'data-listing-id' ) ) :
			echo $bar; // phpcs:ignore WordPress.Security.EscapeOutput
		else :
			?>
			<div class="engine-missing">
				<?php if ( current_user_can( 'manage_options' ) ) : ?>
					<p><?php echo bpt_engine_ready() ? esc_html__( 'The Guesty booking engine could not read the villa from Guesty just now.', 'bpt' ) : esc_html__( 'The Guesty booking engine is not ready yet.', 'bpt' ); ?>
					<a href="<?php echo esc_url( admin_url( 'options-general.php?page=bpt-guesty' ) ); ?>"><?php esc_html_e( 'See what is missing', 'bpt' ); ?></a></p>
				<?php else : ?>
					<p><?php esc_html_e( 'Online booking is on its way. In the meantime, please send us an enquiry.', 'bpt' ); ?>
					<a href="<?php echo esc_url( bpt_page_url( 'contact-us' ) ); ?>"><?php esc_html_e( 'Send an enquiry', 'bpt' ); ?></a></p>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
