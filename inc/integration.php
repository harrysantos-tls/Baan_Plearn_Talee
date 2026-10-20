<?php
/**
 * The two bedroom options of Baan Plearn Talee and their Guesty listings.
 *
 * The booking window, the booking bar and the booking summary choose between the listings below
 * (6 and 5 bedrooms). Availability, minimum stays and prices for the chosen listing come from the
 * "Guesty Booking Engine" plugin when it is active, through three addresses of the theme:
 *
 *   /wp-json/bpt/v1/listings   the two options, with how many guests each sleeps
 *   /wp-json/bpt/v1/calendar   ?listing=ID   the nights taken and the minimum stays
 *   /wp-json/bpt/v1/quote      ?listing=ID&arrive=&depart=&guests=   the price with taxes
 *
 * Without the plugin the options still work: the chosen listing travels with the request, and the
 * rate is "confirmed by our reservations team".
 *
 * @package bpt
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** The bedroom options, largest first. Static, like the Villa Mayavee site. */
function bpt_listings() {
	return apply_filters(
		'bpt_listings',
		array(
			array( 'id' => '6a4c8259fecc9a0012527a0b', 'beds' => 6 ),
			array( 'id' => '6a4df654b26696001372962f', 'beds' => 5 ),
		)
	);
}

function bpt_listing_ids() {
	return wp_list_pluck( bpt_listings(), 'id' );
}

function bpt_listing_beds( $id ) {
	foreach ( bpt_listings() as $l ) {
		if ( $l['id'] === $id ) {
			return (int) $l['beds'];
		}
	}
	return 0;
}

/** "6 bedrooms". */
function bpt_beds_label( $beds ) {
	/* translators: %d: number of bedrooms */
	return sprintf( _n( '%d bedroom', '%d bedrooms', (int) $beds, 'bpt' ), (int) $beds );
}

/** True when the Guesty Booking Engine plugin is active and its master key is set. */
function bpt_integration_ready() {
	return function_exists( 'gbp_api_request' ) && function_exists( 'gbp_calculate_quote_logic' ) && defined( 'GBP_INTERNAL_SECRET' ) && '' !== trim( (string) GBP_INTERNAL_SECRET );
}

/** The choice of bedrooms, as buttons. Nothing is printed when there is only one option. */
function bpt_beds_picker() {
	$list = bpt_listings();
	if ( count( $list ) < 2 ) {
		return;
	}
	echo '<div class="beds-pick" role="radiogroup" aria-label="' . esc_attr__( 'Bedrooms', 'bpt' ) . '" data-beds-pick>';
	foreach ( $list as $l ) {
		printf(
			'<button type="button" role="radio" aria-checked="false" data-beds data-listing="%s">'
			. '<span class="beds-pick__icon"><svg width="28" height="22" viewBox="0 0 28 22" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><rect x="0.5" y="9.5" width="27" height="12" rx="1.5" stroke="currentColor"/><path d="M5 9.5V3a1 1 0 0 1 1-1h16a1 1 0 0 1 1 1v6.5" stroke="currentColor"/><rect x="5" y="5.5" width="7" height="4" rx=".75" fill="currentColor" fill-opacity=".15" stroke="currentColor" stroke-width=".8"/><rect x="16" y="5.5" width="7" height="4" rx=".75" fill="currentColor" fill-opacity=".15" stroke="currentColor" stroke-width=".8"/><rect x="3" y="18" width="3" height="4" rx=".5" fill="currentColor"/><rect x="22" y="18" width="3" height="4" rx=".5" fill="currentColor"/></svg></span>'
			. '<span class="beds-pick__n">%d</span>'
			. '<span class="beds-pick__lbl">%s</span>'
			. '</button>',
			esc_attr( $l['id'] ),
			(int) $l['beds'],
			esc_html__( 'Bedrooms', 'bpt' )
		);
	}
	echo '</div>';
}

/* -------------------------------------------------------------------------
 * From Guesty (through the plugin), kept for a few minutes
 * ---------------------------------------------------------------------- */

/** What a listing is: how many bedrooms and how many guests. Kept for half a day. */
function bpt_listing_info( $id ) {
	if ( ! bpt_listing_beds( $id ) ) {
		return null;
	}
	$key  = 'bpt_li_' . md5( $id );
	$info = get_transient( $key );
	if ( is_array( $info ) ) {
		return $info;
	}
	if ( ! bpt_integration_ready() ) {
		return null;
	}
	$l = gbp_api_request( 'listings/' . rawurlencode( $id ) . '?fields=_id,bedrooms,accommodates,nickname,title' );
	if ( ! is_array( $l ) || isset( $l['error'] ) || empty( $l['_id'] ) ) {
		return null;
	}
	$info = array(
		'max'  => (int) ( $l['accommodates'] ?? 0 ),
		'beds' => (int) ( $l['bedrooms'] ?? 0 ),
	);
	set_transient( $key, $info, 12 * HOUR_IN_SECONDS );
	return $info;
}

/** The calendar of a listing for the next year: the nights taken and the minimum stays. */
function bpt_listing_calendar( $id, $force = false ) {
	if ( ! bpt_listing_beds( $id ) || ! bpt_integration_ready() ) {
		return new WP_Error( 'bpt_cal', __( 'Availability is not connected.', 'bpt' ) );
	}
	$key = 'bpt_lc_' . md5( $id );
	if ( ! $force ) {
		$c = get_transient( $key );
		if ( is_array( $c ) ) {
			return $c;
		}
	}
	$res = gbp_api_request( 'listings/' . rawurlencode( $id ) . '/calendar?from=' . gmdate( 'Y-m-d' ) . '&to=' . gmdate( 'Y-m-d', strtotime( '+1 year' ) ) );
	$days = is_array( $res ) ? ( $res['days'] ?? ( isset( $res['error'] ) ? null : $res ) ) : null;
	if ( ! is_array( $days ) || ! $days ) {
		$stale = get_transient( $key . '_old' );
		return is_array( $stale ) ? $stale : new WP_Error( 'bpt_cal', __( 'Availability could not be read.', 'bpt' ) );
	}
	$blocked = array();
	$min     = array();
	$count   = array();
	$last    = '';
	foreach ( $days as $d ) {
		if ( empty( $d['date'] ) ) {
			continue;
		}
		$date = substr( $d['date'], 0, 10 );
		$last = max( $last, $date );
		$free = 'available' === strtolower( (string) ( $d['status'] ?? '' ) );
		if ( ! $free ) {
			$blocked[] = $date;
		}
		$n = isset( $d['minNights'] ) ? (int) $d['minNights'] : 0;
		if ( $n > 0 ) {
			$min[ $date ] = $n;
			if ( $free ) {
				$count[ $n ] = ( $count[ $n ] ?? 0 ) + 1;
			}
		}
	}
	arsort( $count );
	$default = $count ? (int) array_key_first( $count ) : 1;
	foreach ( $min as $date => $n ) {
		if ( $n === $default ) {
			unset( $min[ $date ] );
		}
	}
	$data = array( 'blocked' => $blocked, 'minNights' => $min, 'minDefault' => $default, 'until' => $last );
	set_transient( $key, $data, 10 * MINUTE_IN_SECONDS );
	set_transient( $key . '_old', $data, DAY_IN_SECONDS );
	return $data;
}

function bpt_money( $amount, $currency ) {
	$symbols = array( 'USD' => '$', 'EUR' => "\u{20AC}", 'GBP' => "\u{00A3}", 'THB' => "\u{0E3F}", 'AUD' => 'A$', 'SGD' => 'S$', 'HKD' => 'HK$' );
	$prefix  = $symbols[ $currency ] ?? ( $currency ? $currency . ' ' : '' );
	$dec     = ( abs( $amount - round( $amount ) ) > 0.004 ) ? 2 : 0;
	return $prefix . number_format( (float) $amount, $dec );
}

/**
 * The price of a stay, from Guesty through the plugin: nights, nightly rate, taxes and the total.
 * Kept for three minutes per listing, dates and guests. Returns null when no price can be given.
 */
function bpt_stay_quote( $id, $arrive, $depart, $guests ) {
	if ( ! bpt_integration_ready() ) {
		return null;
	}
	$key = 'bpt_q_' . md5( $id . '|' . $arrive . '|' . $depart . '|' . (int) $guests );
	$q   = get_transient( $key );
	if ( false === $q ) {
		$res = gbp_calculate_quote_logic( $id, $arrive, $depart, (int) $guests );
		$q   = is_wp_error( $res ) ? 0 : $res;
		set_transient( $key, $q, 3 * MINUTE_IN_SECONDS );
	}
	if ( ! is_array( $q ) || empty( $q['total'] ) ) {
		return null;
	}
	$cur   = $q['currency'] ?? 'USD';
	$taxes = (float) ( $q['service'] ?? 0 ) + (float) ( $q['vat'] ?? 0 ) + (float) ( $q['hotel_tax'] ?? 0 );
	$out   = array(
		'nights'   => (int) $q['nights'],
		'avg'      => bpt_money( $q['nightly_rate'], $cur ),
		'subtotal' => bpt_money( $q['subtotal'], $cur ),
		'taxes'    => $taxes > 0 ? bpt_money( $taxes, $cur ) : '',
		'total'    => bpt_money( $q['total'], $cur ),
	);
	return $out;
}

/* -------------------------------------------------------------------------
 * The addresses the booking window reads
 * ---------------------------------------------------------------------- */
function bpt_rest_listings() {
	$out = array();
	foreach ( bpt_listings() as $l ) {
		$info  = bpt_listing_info( $l['id'] );
		$out[] = array(
			'id'   => $l['id'],
			'beds' => (int) $l['beds'],
			'max'  => $info && $info['max'] ? $info['max'] : 12,
		);
	}
	return $out;
}

function bpt_rest_calendar( WP_REST_Request $req ) {
	$id = (string) $req['listing'];
	if ( ! in_array( $id, bpt_listing_ids(), true ) ) {
		return new WP_REST_Response( array( 'error' => true, 'message' => 'listing' ), 400 );
	}
	$cal = bpt_listing_calendar( $id );
	if ( is_wp_error( $cal ) ) {
		return new WP_REST_Response( array( 'error' => true, 'message' => $cal->get_error_message() ), 503 );
	}
	return array(
		'blocked'    => $cal['blocked'],
		'minNights'  => (object) $cal['minNights'],
		'minDefault' => $cal['minDefault'],
		'until'      => $cal['until'],
	);
}

function bpt_rest_quote( WP_REST_Request $req ) {
	$id     = (string) $req['listing'];
	$arrive = (string) $req['arrive'];
	$depart = (string) $req['depart'];
	$guests = (int) $req['guests'];
	$no     = function ( $msg, $code = 200 ) {
		return new WP_REST_Response( array( 'available' => false, 'message' => $msg ), $code );
	};
	if ( ! in_array( $id, bpt_listing_ids(), true ) ) {
		return $no( __( 'Choose the number of bedrooms.', 'bpt' ), 400 );
	}
	$a = DateTimeImmutable::createFromFormat( '!Y-m-d', $arrive, new DateTimeZone( 'UTC' ) );
	$d = DateTimeImmutable::createFromFormat( '!Y-m-d', $depart, new DateTimeZone( 'UTC' ) );
	if ( ! $a || ! $d || $a->format( 'Y-m-d' ) !== $arrive || $d->format( 'Y-m-d' ) !== $depart ) {
		return $no( __( 'Those dates could not be read.', 'bpt' ), 400 );
	}
	$nights = (int) $a->diff( $d )->format( '%r%a' );
	$limit  = new DateTimeImmutable( '+1 year', new DateTimeZone( 'UTC' ) );
	if ( $nights < 1 || $nights > 90 || $arrive < gmdate( 'Y-m-d' ) || $a > $limit ) {
		return $no( __( 'Those dates are not available.', 'bpt' ) );
	}
	$info = bpt_listing_info( $id );
	$max  = $info && $info['max'] ? $info['max'] : 12;
	if ( $guests < 1 || $guests > $max ) {
		/* translators: %d: guests */
		return $no( sprintf( __( 'This villa sleeps up to %d guests.', 'bpt' ), $max ) );
	}
	$cal = bpt_listing_calendar( $id );
	if ( is_wp_error( $cal ) ) {
		return new WP_REST_Response( array( 'available' => true, 'nights' => $nights ), 200 ); // no price, no promise
	}
	$min = $cal['minNights'][ $arrive ] ?? $cal['minDefault'];
	if ( $nights < $min ) {
		/* translators: %d: nights */
		return $no( sprintf( __( 'The minimum stay for these dates is %d nights.', 'bpt' ), $min ) );
	}
	for ( $i = 0; $i < $nights; $i++ ) {
		if ( in_array( $a->modify( '+' . $i . ' days' )->format( 'Y-m-d' ), $cal['blocked'], true ) ) {
			return $no( __( 'Sorry, those dates are not available.', 'bpt' ) );
		}
	}
	// Prices are asked of Guesty through the plugin, so a visitor cannot ask for them without limit.
	$ip  = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
	$thr = 'bpt_qt_' . md5( $ip );
	$n   = (int) get_transient( $thr );
	if ( $n >= 30 ) {
		return $no( __( 'Too many requests. Please try again in a few minutes.', 'bpt' ), 429 );
	}
	set_transient( $thr, $n + 1, 10 * MINUTE_IN_SECONDS );

	$quote = bpt_stay_quote( $id, $arrive, $depart, $guests );
	$out   = array( 'available' => true, 'nights' => $nights );
	if ( $quote ) {
		$out = array_merge( $out, $quote, array( 'totalLabel' => __( 'Total', 'bpt' ) ) );
	}
	return $out;
}

add_action(
	'rest_api_init',
	function () {
		register_rest_route( 'bpt/v1', '/listings', array( 'methods' => 'GET', 'callback' => 'bpt_rest_listings', 'permission_callback' => '__return_true' ) );
		register_rest_route(
			'bpt/v1',
			'/calendar',
			array(
				'methods'             => 'GET',
				'callback'            => 'bpt_rest_calendar',
				'permission_callback' => '__return_true',
				'args'                => array( 'listing' => array( 'required' => true, 'sanitize_callback' => 'sanitize_text_field' ) ),
			)
		);
		register_rest_route(
			'bpt/v1',
			'/quote',
			array(
				'methods'             => 'GET',
				'callback'            => 'bpt_rest_quote',
				'permission_callback' => '__return_true',
				'args'                => array(
					'listing' => array( 'required' => true, 'sanitize_callback' => 'sanitize_text_field' ),
					'arrive'  => array( 'required' => true, 'sanitize_callback' => 'sanitize_text_field' ),
					'depart'  => array( 'required' => true, 'sanitize_callback' => 'sanitize_text_field' ),
					'guests'  => array( 'default' => 2, 'sanitize_callback' => 'absint' ),
				),
			)
		);
	}
);

/** The booking window learns about the options, and where to ask for availability and prices. */
add_filter(
	'bpt_booking_config',
	function ( $cfg ) {
		$cfg['listings'] = array();
		foreach ( bpt_listings() as $l ) {
			$cfg['listings'][] = array( 'id' => $l['id'], 'beds' => (int) $l['beds'], 'label' => bpt_beds_label( $l['beds'] ), 'max' => 12 );
		}
		if ( bpt_integration_ready() ) {
			$cfg['listingsUrl'] = rest_url( 'bpt/v1/listings' );
			$cfg['calendarUrl'] = rest_url( 'bpt/v1/calendar' );
			$cfg['quoteUrl']    = rest_url( 'bpt/v1/quote' );
		}
		return $cfg;
	},
	5
);
