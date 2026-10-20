<?php
/**
 * Booking: front end configuration, page helpers and the request form.
 *
 * The calendar, the booking bar, the booking window and the booking summary
 * all run on the front end (assets/js/site.js). Live availability and rates
 * are NOT built into the theme. They come from your Guesty integration plugin,
 * which hands the theme two optional URLs through the `bpt_booking_config`
 * filter:
 *
 *   add_filter( 'bpt_booking_config', function ( $cfg ) {
 *       $cfg['calendarUrl'] = rest_url( 'your-plugin/v1/calendar' ); // GET -> { "blocked": ["2026-12-24", ...] }
 *       $cfg['quoteUrl']    = rest_url( 'your-plugin/v1/quote' );    // GET ?arrive=&depart=&guests= -> see README
 *       return $cfg;
 *   } );
 *
 * The booking window is on. To show a "COMING SOON" notice on every Check availability
 * button instead, set $cfg['live'] = false in the same filter.
 *
 * Without those URLs the calendar lets guests pick any future date and the
 * summary says the rate is confirmed by the reservations team.
 *
 * @package bpt
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Page data kept with the theme (copy used by the setup routine).
 */
function bpt_data() {
	static $data = null;
	if ( null === $data ) {
		$file = get_theme_file_path( 'inc/pages-data.json' );
		$json = file_exists( $file ) ? json_decode( file_get_contents( $file ), true ) : array();
		$data = array();
		foreach ( (array) $json as $page ) {
			$data[ $page['slug'] ] = $page;
		}
	}
	return $data;
}

/** URL of a page by slug, whether or not it has been created yet. */
function bpt_find_page( $slug ) {
	$page = get_page_by_path( $slug );
	if ( $page ) {
		return $page;
	}
	// Child pages (for example under an old "The villa" page) are found by their own slug.
	$found = get_posts( array( 'post_type' => 'page', 'name' => $slug, 'post_status' => 'publish', 'numberposts' => 1 ) );
	return $found ? $found[0] : null;
}

function bpt_page_url( $slug ) {
	$page = bpt_find_page( $slug );
	return $page ? get_permalink( $page ) : home_url( '/' . $slug . '/' );
}

/** Title of a page by slug, falling back to the bundled data. */
function bpt_page_title( $slug ) {
	$page = bpt_find_page( $slug );
	if ( $page ) {
		return get_the_title( $page );
	}
	$data = bpt_data();
	return isset( $data[ $slug ]['title'] ) ? $data[ $slug ]['title'] : ucwords( str_replace( '-', ' ', $slug ) );
}

/** URL of a file under assets/img. */
function bpt_asset( $path ) {
	return get_theme_file_uri( 'assets/img/' . ltrim( $path, '/' ) );
}

/**
 * Photographs for a room page. Images attached to the page in the media
 * library win; otherwise the photographs bundled with the theme are used.
 */
function bpt_room_gallery( $post_id = null ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	$out     = array();
	// Use the theme folder — the canonical gallery for each room.
	$slug = get_post_field( 'post_name', $post_id );
	$dir  = get_theme_file_path( 'assets/img/rooms/' . $slug );
	if ( is_dir( $dir ) ) {
		$files = glob( $dir . '/*.jpg' );
		natsort( $files );
		foreach ( $files as $file ) {
			$out[] = array( 'url' => bpt_asset( 'rooms/' . $slug . '/' . basename( $file ) ), 'alt' => get_the_title( $post_id ) . ' ' . ( count( $out ) + 1 ) );
		}
		return $out;
	}
	// Fallback: images listed in pages-data.json (e.g. external URLs for pages with no theme folder).
	$pdata = bpt_data();
	if ( isset( $pdata[ $slug ]['images'] ) ) {
		foreach ( $pdata[ $slug ]['images'] as $url ) {
			$out[] = array( 'url' => esc_url( $url ), 'alt' => get_the_title( $post_id ) . ' ' . ( count( $out ) + 1 ) );
		}
		if ( $out ) { return $out; }
	}
	// Fallback: images attached to the page in the Media Library.
	foreach ( bpt_attached_images( $post_id, 40 ) as $att ) {
		$url = wp_get_attachment_image_url( $att->ID, 'bpt_wide' );
		if ( $url ) {
			$out[] = array( 'url' => $url, 'alt' => get_the_title( $att ) );
		}
	}
	return $out;
}

/** Configuration handed to the front end script. */
function bpt_booking_config() {
	$cfg = array(
		'ajax'        => admin_url( 'admin-ajax.php' ),
		'nonce'       => wp_create_nonce( 'bpt_booking' ),
		'summaryUrl'  => bpt_page_url( 'booking-summary' ),
		'homeUrl'     => home_url( '/' ),
		'maxGuests'   => 12,
		'minNights'   => 1,
		'live'        => true, // the booking window is on. Set to false (bpt_booking_config filter) to show a COMING SOON notice instead
		'contactUrl'  => bpt_page_url( 'contact-us' ),
		'via'         => '',
		'calendarUrl' => '',
		'quoteUrl'    => '',
		'i18n'        => array(
			'chooseDates' => __( 'Choose your arrival and departure dates.', 'bpt' ),
			'afterArrival' => __( 'Departure must be after arrival.', 'bpt' ),
			'maxGuests'    => __( 'Maximum of 12 guests.', 'bpt' ),
			'maxGuestsHard' => __( 'Sorry, this villa sleeps 12 guests only.', 'bpt' ),
			'chooseBeds'   => __( 'Choose the number of bedrooms.', 'bpt' ),
			'unavailable'  => __( 'Sorry, those dates are not available.', 'bpt' ),
			'minStay'      => __( 'The minimum stay for these dates is %d nights.', 'bpt' ),
			'calError'     => __( 'We could not load live availability just now. Please try again, or send us an enquiry.', 'bpt' ),
			'calWait'      => __( 'Loading availability, one moment.', 'bpt' ),
			'pickOther'    => __( 'Please choose other dates.', 'bpt' ),
			'pickOtherBtn' => __( 'Choose other dates', 'bpt' ),
			'soonTitle'    => __( 'COMING SOON', 'bpt' ),
			'soonText'     => __( 'Online booking is on its way. In the meantime, please send us an enquiry.', 'bpt' ),
			'soonLink'     => __( 'Send an enquiry', 'bpt' ),
			'sendError'    => __( 'Sorry, we could not send your request. Please try again or email us directly.', 'bpt' ),
		),
	);
	return apply_filters( 'bpt_booking_config', $cfg );
}

/**
 * The request form on the booking summary page.
 * Stores the request as a private "Enquiry" and emails reservations.
 */
function bpt_handle_booking_request() {
	if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'bpt_booking' ) ) {
		wp_send_json_error( array( 'message' => 'nonce' ), 403 );
	}
	if ( ! empty( $_POST['website'] ) ) { // honeypot
		wp_send_json_success();
	}

	$get = function ( $k ) {
		return isset( $_POST[ $k ] ) ? sanitize_textarea_field( wp_unslash( $_POST[ $k ] ) ) : '';
	};
	$name   = $get( 'name' );
	$email  = sanitize_email( $get( 'email' ) );
	$phone  = $get( 'phone' );
	$notes  = $get( 'notes' );
	$arrive = $get( 'arrive' );
	$depart = $get( 'depart' );
	$guests = (int) $get( 'guests' );

	// The bedroom option chosen: its listing is checked against the fixed list, never taken as it comes.
	$listing = preg_replace( '/[^A-Za-z0-9_-]/', '', $get( 'listing' ) );
	$all     = bpt_listings();
	if ( '' === $listing && 1 === count( $all ) ) {
		$listing = $all[0]['id'];
	}
	$beds = bpt_listing_beds( $listing );

	if ( '' === $name || ! is_email( $email ) ) {
		wp_send_json_error( array( 'message' => 'details' ), 422 );
	}
	if ( ! $beds ) {
		wp_send_json_error( array( 'message' => 'bedrooms' ), 422 );
	}

	$lines = array(
		'Name: ' . $name,
		'Email: ' . $email,
		'Phone: ' . $phone,
		'Arrival: ' . $arrive,
		'Departure: ' . $depart,
		'Bedrooms: ' . $beds,
		'Listing ID: ' . $listing,
		'Guests: ' . $guests,
		'Notes: ' . $notes,
	);
	// What Guesty quoted for these dates, if the booking engine plugin can say.
	$quote = bpt_stay_quote( $listing, $arrive, $depart, $guests );
	if ( $quote ) {
		$lines[] = 'Quoted by Guesty: ' . $quote['total'] . ( $quote['taxes'] ? ' (taxes ' . $quote['taxes'] . ')' : '' );
	}
	$body = implode( "\n", $lines );

	wp_insert_post( array(
		'post_type'    => 'bpt_enquiry',
		'post_status'  => 'private',
		'post_title'   => sprintf( '%s, %d bedrooms, %s to %s', $name, $beds, $arrive, $depart ),
		'post_content' => $body,
	) );

	// When the Baan Guesty plugin is active it sends its own email with the Guesty confirmation code.
	$sent = ! function_exists( 'bpg_rest_reserve' ) ? wp_mail(
		bpt_detail( 'email' ),
		sprintf( 'Baan Plearn Talee booking request: %s (%d bedrooms, %s to %s)', $name, $beds, $arrive, $depart ),
		$body,
		array( 'Content-Type: text/plain; charset=UTF-8', 'Reply-To: ' . $name . ' <' . $email . '>' )
	) : true;

	wp_send_json_success( array( 'mailed' => (bool) $sent ) );
}
add_action( 'wp_ajax_nopriv_bpt_booking_request', 'bpt_handle_booking_request' );
add_action( 'wp_ajax_bpt_booking_request', 'bpt_handle_booking_request' );

/** The guests control: minus, number, plus, and the 12 guest warning. */
function bpt_guests_stepper() {
	?>
	<div class="guests" data-guests>
		<div class="guests__box">
			<button type="button" data-dec aria-label="<?php esc_attr_e( 'Fewer guests', 'bpt' ); ?>">&minus;</button>
			<div class="guests__n" data-guests-text aria-live="polite">2 guests</div>
			<button type="button" data-inc aria-label="<?php esc_attr_e( 'More guests', 'bpt' ); ?>">+</button>
		</div>
		<div class="guests__warn" data-guests-warn hidden></div>
	</div>
	<?php
}
