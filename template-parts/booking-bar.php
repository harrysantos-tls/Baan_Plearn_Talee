<?php
/**
 * The booking bar under the home hero: one date picker, guests, search.
 *
 * @package bpt
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<section class="book-strip" id="booking" aria-label="<?php esc_attr_e( 'Check availability', 'bpt' ); ?>">
	<div class="book-card" data-bpt-bar>
		<div class="book-row">
			<?php if ( count( bpt_listings() ) > 1 ) : ?>
			<div class="book-field book-field--beds">
				<span class="lbl"><?php esc_html_e( 'Bedrooms', 'bpt' ); ?></span>
				<?php bpt_beds_picker(); ?>
			</div>
			<?php endif; ?>
			<div class="book-field book-field--dates">
				<span class="lbl"><?php esc_html_e( 'Dates', 'bpt' ); ?></span>
				<button type="button" class="book-dates" data-dates-toggle aria-expanded="false">
					<span data-range-text><?php esc_html_e( 'Select your dates', 'bpt' ); ?></span>
				</button>
			</div>
			<div class="book-field">
				<span class="lbl"><?php esc_html_e( 'Guests', 'bpt' ); ?></span>
				<?php bpt_guests_stepper(); ?>
			</div>
			<button type="button" class="btn-solid" data-bar-search><?php esc_html_e( 'Check availability', 'bpt' ); ?></button>
		</div>

		<div class="cal-pop" data-cal-pop hidden>
			<div class="cal" data-cal></div>
			<div class="cal-foot"><span data-hint><?php esc_html_e( 'Choose your arrival date', 'bpt' ); ?></span><button type="button" class="linkbtn" data-clear><?php esc_html_e( 'Clear dates', 'bpt' ); ?></button></div>
		</div>

		<div class="book-result" data-bar-result hidden>
			<div class="book-result__text"><span class="serif" data-bind="range"></span><br><span class="muted" data-bind="summary-line"></span></div>
			<div class="book-result__act">
				<a class="btn-solid" href="<?php echo esc_url( bpt_page_url( 'booking-summary' ) ); ?>" data-bar-continue><?php esc_html_e( 'Continue to book', 'bpt' ); ?></a>
				<button type="button" class="btn-line-dark" data-bar-reset><?php esc_html_e( 'Change dates', 'bpt' ); ?></button>
			</div>
		</div>
		<div class="form-msg" data-msg hidden></div>
	</div>
</section>
