<?php
/**
 * The booking window. Opens from any "Check availability" button.
 * Continue takes the guest to the booking summary page.
 *
 * @package bpt
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="bmodal" data-bmodal hidden>
	<div class="bmodal__dialog" role="dialog" aria-modal="true" aria-labelledby="bmodal-title" tabindex="-1">
		<div class="bmodal__side">
			<img src="<?php echo esc_url( bpt_asset( 'home/aerial-night.jpg' ) ); ?>" alt="" aria-hidden="true">
			<div class="bmodal__wash"></div>
			<div class="bmodal__side-in">
				<div class="kicker kicker--light"><?php esc_html_e( 'Reserve your stay', 'bpt' ); ?></div>
				<div class="bmodal__villa"><?php echo esc_html( bpt_villa_name() ); ?></div>
				<p class="bmodal__sub"><?php esc_html_e( 'Kamala, Phuket. Six or five bedrooms.', 'bpt' ); ?></p>
				<dl class="bmodal__rows">
					<?php if ( count( bpt_listings() ) > 1 ) : ?><div><dt><?php esc_html_e( 'Bedrooms', 'bpt' ); ?></dt><dd data-bind="beds">Choose</dd></div><?php endif; ?>
					<div><dt><?php esc_html_e( 'Arrival', 'bpt' ); ?></dt><dd data-bind="arrive">Add date</dd></div>
					<div><dt><?php esc_html_e( 'Departure', 'bpt' ); ?></dt><dd data-bind="depart">Add date</dd></div>
					<div><dt><?php esc_html_e( 'Stay', 'bpt' ); ?></dt><dd data-bind="nights">Select dates</dd></div>
					<div><dt><?php esc_html_e( 'Guests', 'bpt' ); ?></dt><dd data-bind="guests">2 guests</dd></div>
				</dl>
				<div class="bmodal__contact"><?php echo esc_html( bpt_detail( 'email' ) ); ?><br><?php echo esc_html( bpt_detail( 'phone' ) ); ?></div>
			</div>
		</div>
		<div class="bmodal__main">
			<div class="bmodal__head">
				<div>
					<div class="kicker"><?php esc_html_e( 'Step 1 of 3', 'bpt' ); ?></div>
					<h2 id="bmodal-title" data-bind-head><?php esc_html_e( 'Choose your bedrooms', 'bpt' ); ?></h2>
					<p><?php esc_html_e( 'Choose your bedrooms, your arrival and departure, then the number of guests. Next, you will see your booking summary.', 'bpt' ); ?></p>
				</div>
				<button type="button" class="bmodal__close" data-bmodal-close aria-label="<?php esc_attr_e( 'Close', 'bpt' ); ?>">&times;</button>
			</div>
			<?php if ( count( bpt_listings() ) > 1 ) : ?>
			<div class="bmodal__beds">
				<p class="bmodal__beds-prompt"><?php esc_html_e( 'Choose your bedrooms to see available dates.', 'bpt' ); ?></p>
				<?php bpt_beds_picker(); ?>
			</div>
			<?php endif; ?>
			<div class="cal" data-cal></div>
			<div class="bmodal__cal-bar"><div class="cal-hint" data-hint><?php esc_html_e( 'Choose your arrival date', 'bpt' ); ?></div><button type="button" class="bmodal__clear" data-clear hidden><?php esc_html_e( 'Clear dates', 'bpt' ); ?></button></div>
			<div class="bmodal__foot">
				<div class="bmodal__foot-guests"><div class="lbl"><?php esc_html_e( 'Guests', 'bpt' ); ?></div><?php bpt_guests_stepper(); ?></div>
				<button type="button" class="btn-solid bmodal__continue" data-bmodal-continue><?php esc_html_e( 'Continue', 'bpt' ); ?></button>
			</div>
			<div class="form-msg" data-msg hidden></div>
		</div>
	</div>
</div>
