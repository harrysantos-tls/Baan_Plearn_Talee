<?php
/**
 * Template Name: Booking summary (boarding pass)
 *
 * Step 1: the stay and the rate. Step 2: guest details. Step 3: confirmed.
 * The dates and guests arrive in the address (?arrive=&depart=&guests=) from the
 * booking bar or the booking window. Everything on this page is filled in by
 * assets/js/site.js.
 *
 * @package bpt
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();

get_template_part( 'template-parts/pagehero', null, array(
	'image' => bpt_asset( 'home/pool-sunset.jpg' ),
	'title' => __( 'Booking summary', 'bpt' ),
	'crumb' => __( 'Reservations', 'bpt' ),
	'size'  => 'short',
	'position' => '60% 62%',
) );
?>
<section class="block">
	<div class="wrap wrap--narrow" data-bsummary>
		<div class="bs-step" data-bs-label>Step 1 of 3 &middot; Your stay</div>

		<div class="ticket" data-ticket>
			<div class="ticket__main">
				<div class="ticket__top"><span class="kicker"><?php esc_html_e( 'Booking summary', 'bpt' ); ?></span><span class="serif ticket__villa"><?php echo esc_html( bpt_villa_name() ); ?></span></div>
				<div class="ticket__dates">
					<div><div class="lbl"><?php esc_html_e( 'Arrival', 'bpt' ); ?></div><div class="ticket__day" data-bind="a-day">-</div><div class="muted" data-bind="a-meta"></div></div>
					<div class="ticket__line"><div class="muted" data-bind="nights-big">- nights</div><div class="ticket__dots"></div></div>
					<div><div class="lbl"><?php esc_html_e( 'Departure', 'bpt' ); ?></div><div class="ticket__day" data-bind="d-day">-</div><div class="muted" data-bind="d-meta"></div></div>
				</div>
				<div class="ticket__facts">
					<div><div class="lbl"><?php esc_html_e( 'Guests', 'bpt' ); ?></div><div data-bind="guests">2 guests</div></div>
					<div><div class="lbl"><?php esc_html_e( 'Stay', 'bpt' ); ?></div><div data-bind="nights">-</div></div>
					<div><div class="lbl"><?php esc_html_e( 'Villa', 'bpt' ); ?></div><div data-bind="villa-line"><?php esc_html_e( 'Kamala, Phuket', 'bpt' ); ?></div><div class="bs-beds" data-beds-need hidden><?php bpt_beds_picker(); ?></div></div>
				</div>
			</div>
			<div class="ticket__stub">
				<div><div class="lbl"><?php esc_html_e( 'Status', 'bpt' ); ?></div><div data-bind="status"><?php esc_html_e( 'Review your stay', 'bpt' ); ?></div></div>
				<div class="rate">
					<div class="lbl"><?php esc_html_e( 'Rate', 'bpt' ); ?></div>
					<div class="rate__pending" data-rate-pending><?php esc_html_e( 'Confirmed by our reservations team', 'bpt' ); ?></div>
					<div class="rate__live" data-rate-live hidden>
						<div class="rate__row"><span data-bind="rate-line"></span><span data-bind="rate-sub"></span></div>
						<div data-rate-lines></div>
						<div class="rate__row" data-rate-tax-row><span><?php esc_html_e( 'Taxes', 'bpt' ); ?></span><span data-bind="rate-tax"></span></div>
						<div class="rate__total"><span data-bind="rate-total-label"><?php esc_html_e( 'Total', 'bpt' ); ?></span><span class="serif" data-bind="rate-total"></span></div>
						<div class="muted small" data-rate-incl><?php esc_html_e( 'Includes taxes.', 'bpt' ); ?></div>
						<div class="muted small" data-rate-note hidden><?php esc_html_e( 'The total of the nightly rates. Taxes and fees are confirmed by our reservations team.', 'bpt' ); ?></div>
					</div>
				</div>
			</div>
		</div>

		<div data-bs-panel="1">
			<div class="bs-actions">
				<button type="button" class="btn-solid" data-bs-next><?php esc_html_e( 'Continue to guest details', 'bpt' ); ?> &rarr;</button>
				<a class="tl" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Change dates', 'bpt' ); ?></a>
			</div>
			<p class="fine"><?php esc_html_e( 'No payment is taken on this page. Our reservations team confirms availability and your final rate.', 'bpt' ); ?></p>
		</div>

		<form class="bs-form" data-bs-panel="2" hidden novalidate>
			<div class="kicker"><?php esc_html_e( 'Guest details', 'bpt' ); ?></div>
			<h2><?php esc_html_e( 'Who is travelling?', 'bpt' ); ?></h2>
			<p class="bs-form__sub"><?php esc_html_e( 'Leave your details and our reservations team will reply with availability and a confirmed rate for these dates.', 'bpt' ); ?></p>
			<div class="fields">
				<label><span><?php esc_html_e( 'Full name', 'bpt' ); ?></span><input type="text" name="name" autocomplete="name" required></label>
				<label><span><?php esc_html_e( 'Email', 'bpt' ); ?></span><input type="email" name="email" autocomplete="email" required></label>
				<label><span><?php esc_html_e( 'Phone, with country code', 'bpt' ); ?></span><input type="tel" name="phone" autocomplete="tel"></label>
				<label><span><?php esc_html_e( 'Anything we should know', 'bpt' ); ?></span><input type="text" name="notes"></label>
			</div>
			<label class="bs-terms" data-reserve-only hidden><input type="checkbox" name="terms"> <span><?php esc_html_e( 'I have read and accept the', 'bpt' ); ?> <a href="<?php echo esc_url( bpt_page_url( 'terms-and-conditions' ) ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Terms and Conditions', 'bpt' ); ?></a>.</span></label>
			<input type="text" name="website" class="hp" tabindex="-1" autocomplete="off" aria-hidden="true">
			<div class="bs-actions">
				<button type="submit" class="btn-solid"><?php esc_html_e( 'Confirm request', 'bpt' ); ?></button>
				<button type="button" class="btn-line-dark" data-bs-back><?php esc_html_e( 'Back', 'bpt' ); ?></button>
				<a href="<?php echo esc_url( bpt_page_url( 'contact-us' ) ); ?>" class="btn-line-dark bs-quote-btn"><?php esc_html_e( 'Get a quote', 'bpt' ); ?></a>
			</div>
			<div class="form-msg" data-msg hidden></div>
		</form>

		<div class="bs-done" data-bs-panel="3" hidden>
			<div class="kicker"><?php esc_html_e( 'Request received', 'bpt' ); ?></div>
			<h2><?php esc_html_e( 'Thank you', 'bpt' ); ?></h2>
			<p data-bs-done-text><?php esc_html_e( 'Your request is with our reservations team. They will come back to you with availability and a confirmed rate.', 'bpt' ); ?></p>
			<p class="bs-code" data-bs-code hidden></p>
			<a class="btn-solid" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Back to home', 'bpt' ); ?></a>
		</div>
		<noscript><p class="fine"><?php esc_html_e( 'Please enable JavaScript to see your booking summary, or email us your dates.', 'bpt' ); ?></p></noscript>
	</div>
</section>
<?php get_footer(); ?>
