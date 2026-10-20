<?php
/**
 * Guesty Booking Engine (the plugin) on the Guesty test page.
 *
 * The booking on /home-guesty/ is done by the "Guesty Booking Engine" plugin, not by
 * the theme: its search bar (shortcode [guesty_calendar_picker]) reads the calendar
 * from Guesty, and its "Guesty Internal Checkout" page (/checkout/) quotes the price
 * and makes the reservation. The theme only places the search bar on the page, styles
 * it to match the site, and creates the /checkout/ page.
 *
 * What the plugin needs (the same as on your other villa sites):
 *   - the plugin installed and active
 *   - define( 'GBP_INTERNAL_SECRET', '...' ); in wp-config.php
 * What the theme needs: the Guesty Listing ID of Baan Plearn Talee, typed in
 * Settings > Booking (Guesty), or set with define( 'BPT_GUESTY_LISTING_ID', '...' ); in wp-config.php.
 *
 * @package bpt
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function bpt_engine_active() {
	return function_exists( 'gbp_api_request' ) && shortcode_exists( 'guesty_calendar_picker' );
}

function bpt_engine_listing() {
	if ( defined( 'BPT_GUESTY_LISTING_ID' ) && '' !== trim( (string) BPT_GUESTY_LISTING_ID ) ) {
		return trim( (string) BPT_GUESTY_LISTING_ID );
	}
	$saved = trim( (string) get_option( 'bpt_engine_listing', '' ) );
	return '' !== $saved ? $saved : implode( ',', bpt_listing_ids() );
}

function bpt_engine_secret_set() {
	return defined( 'GBP_INTERNAL_SECRET' ) && '' !== trim( (string) GBP_INTERNAL_SECRET );
}

function bpt_engine_ready() {
	return bpt_engine_active() && bpt_engine_secret_set() && '' !== bpt_engine_listing();
}

function bpt_is_engine_page() {
	return is_page_template( 'templates/template-home-guesty.php' );
}

/** A class on the pages that use the engine, so the theme can style the plugin's parts. */
add_filter(
	'body_class',
	function ( $classes ) {
		if ( bpt_is_engine_page() || is_page_template( 'guesty-checkout.php' ) ) {
			$classes[] = 'bpt-engine';
		}
		return $classes;
	}
);

/** On the test page the Check availability buttons take the visitor to the search bar. */
add_filter(
	'bpt_booking_config',
	function ( $cfg ) {
		if ( bpt_is_engine_page() ) {
			$cfg['engine'] = true;
		}
		return $cfg;
	}
);

/* -------------------------------------------------------------------------
 * Settings > Booking (Guesty)
 * ---------------------------------------------------------------------- */
add_action(
	'admin_menu',
	function () {
		add_options_page( __( 'Booking (Guesty)', 'bpt' ), __( 'Booking (Guesty)', 'bpt' ), 'manage_options', 'bpt-guesty', 'bpt_engine_page' );
	}
);

add_action(
	'admin_init',
	function () {
		register_setting(
			'bpt_engine_group',
			'bpt_engine_listing',
			array(
				'sanitize_callback' => function ( $v ) {
					return preg_replace( '/[^A-Za-z0-9,_-]/', '', (string) $v );
				},
			)
		);
	}
);

function bpt_engine_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$locked = defined( 'BPT_GUESTY_LISTING_ID' ) && '' !== trim( (string) BPT_GUESTY_LISTING_ID );
	$test   = wp_create_nonce( 'bpt_engine_test' );
	$page   = bpt_find_page( 'home-guesty' );
	$check  = bpt_find_page( 'checkout' );
	$row    = function ( $ok, $text ) {
		return '<li>' . ( $ok ? '<span style="color:#1a7f37">&#10003;</span> ' : '<span style="color:#b32d2e">&#10007;</span> ' ) . esc_html( $text ) . '</li>';
	};
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Booking (Guesty)', 'bpt' ); ?></h1>
		<p><?php esc_html_e( 'The booking on the Guesty test page is done by the Guesty Booking Engine plugin. It reads the calendar from Guesty, quotes the price, and makes the reservation on its checkout page.', 'bpt' ); ?></p>
		<ul>
			<?php
			echo $row( bpt_engine_active(), __( 'The Guesty Booking Engine plugin is installed and active', 'bpt' ) ); // phpcs:ignore WordPress.Security.EscapeOutput
			echo $row( bpt_engine_secret_set(), __( 'GBP_INTERNAL_SECRET is set in wp-config.php', 'bpt' ) ); // phpcs:ignore WordPress.Security.EscapeOutput
			echo $row( '' !== bpt_engine_listing(), __( 'The Listing ID is filled in', 'bpt' ) ); // phpcs:ignore WordPress.Security.EscapeOutput
			echo $row( (bool) $check, __( 'The /checkout/ page exists', 'bpt' ) ); // phpcs:ignore WordPress.Security.EscapeOutput
			?>
		</ul>
		<form method="post" action="options.php">
			<?php settings_fields( 'bpt_engine_group' ); ?>
			<table class="form-table" role="presentation">
				<tr><th scope="row"><label for="bpt_lid"><?php esc_html_e( 'Listing ID', 'bpt' ); ?></label></th>
					<td><input id="bpt_lid" class="regular-text" name="bpt_engine_listing" value="<?php echo esc_attr( bpt_engine_listing() ); ?>" autocomplete="off"<?php disabled( $locked ); ?>><?php echo $locked ? ' <em>' . esc_html__( 'set in wp-config.php', 'bpt' ) . '</em>' : ''; ?>
						<p class="description"><?php esc_html_e( 'The Guesty listing of Baan Plearn Talee: the long code in the address of the listing in Guesty.', 'bpt' ); ?></p></td></tr>
			</table>
			<?php submit_button(); ?>
		</form>
		<hr>
		<h2><?php esc_html_e( 'Check the connection', 'bpt' ); ?></h2>
		<p><button type="button" class="button" id="bpt-test"><?php esc_html_e( 'Test Guesty', 'bpt' ); ?></button></p>
		<pre id="bpt-test-out" style="background:#fff;border:1px solid #ccd0d4;padding:12px;max-width:760px;white-space:pre-wrap;display:none"></pre>
		<?php if ( $page ) : ?>
			<p><a class="button button-primary" href="<?php echo esc_url( get_permalink( $page ) ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Open the Guesty test page', 'bpt' ); ?></a></p>
		<?php endif; ?>
	</div>
	<script>
	(function () {
		var out = document.getElementById('bpt-test-out');
		document.getElementById('bpt-test').addEventListener('click', function () {
			out.style.display = 'block'; out.textContent = '...';
			var fd = new FormData(); fd.append('action', 'bpt_engine_test'); fd.append('nonce', '<?php echo esc_js( $test ); ?>');
			fetch(ajaxurl, { method: 'POST', body: fd, credentials: 'same-origin' }).then(function (r) { return r.json(); })
				.then(function (j) { out.textContent = (j && j.data && j.data.message) || JSON.stringify(j); }).catch(function (e) { out.textContent = String(e); });
		});
	})();
	</script>
	<?php
}

add_action(
	'wp_ajax_bpt_engine_test',
	function () {
		if ( ! current_user_can( 'manage_options' ) || ! check_ajax_referer( 'bpt_engine_test', 'nonce', false ) ) {
			wp_send_json_error( array( 'message' => 'Not allowed.' ), 403 );
		}
		$lines = array();
		if ( ! bpt_engine_active() ) {
			wp_send_json_error( array( 'message' => __( 'The Guesty Booking Engine plugin is not active on this site. Install and activate it first.', 'bpt' ) ) );
		}
		if ( ! bpt_engine_secret_set() ) {
			wp_send_json_error( array( 'message' => __( 'GBP_INTERNAL_SECRET is not set. Add it to wp-config.php, the same as on your other villa sites.', 'bpt' ) ) );
		}
		if ( '' === bpt_engine_listing() ) {
			wp_send_json_error( array( 'message' => __( 'Fill in the Listing ID and save first.', 'bpt' ) ) );
		}
		delete_transient( 'gbp_slave_tokens_cache' );
		$tokens = function_exists( 'gbp_fetch_tokens_from_master' ) ? gbp_fetch_tokens_from_master() : false;
		if ( ! $tokens ) {
			wp_send_json_error( array( 'message' => "Tokens from the master site: FAILED\n" . __( 'Check GBP_INTERNAL_SECRET, and that the master site answers.', 'bpt' ) ) );
		}
		$lines[] = 'Tokens from the master site: OK';
		$ids     = array_filter( array_map( 'trim', explode( ',', bpt_engine_listing() ) ) );
		foreach ( $ids as $id ) {
			$l = gbp_api_request( 'listings/' . rawurlencode( $id ) . '?fields=_id,bedrooms,accommodates,nickname,title' );
			if ( ! is_array( $l ) || isset( $l['error'] ) || empty( $l['_id'] ) ) {
				wp_send_json_error( array( 'message' => implode( "\n", $lines ) . "\nListing " . $id . ': FAILED' . ( isset( $l['error']['message'] ) ? ' (' . $l['error']['message'] . ')' : '' ) . "\n" . __( 'Check the Listing ID.', 'bpt' ) ) );
			}
			$lines[] = sprintf( 'Listing: %s | %d bedrooms | sleeps %d', $l['nickname'] ?? ( $l['title'] ?? $id ), (int) ( $l['bedrooms'] ?? 0 ), (int) ( $l['accommodates'] ?? 0 ) );
			$cal     = gbp_api_request( 'listings/' . rawurlencode( $id ) . '/calendar?from=' . gmdate( 'Y-m-d' ) . '&to=' . gmdate( 'Y-m-d', strtotime( '+1 year' ) ) );
			$days    = is_array( $cal ) ? ( $cal['days'] ?? $cal ) : array();
			$n       = 0;
			$busy    = 0;
			foreach ( (array) $days as $d ) {
				if ( isset( $d['date'] ) ) {
					$n++;
					if ( 'available' !== ( $d['status'] ?? '' ) ) {
						$busy++;
					}
				}
			}
			$lines[] = sprintf( 'Calendar: %d days read, %d not available', $n, $busy );
		}
		wp_send_json_success( array( 'message' => implode( "\n", $lines ) ) );
	}
);


/* -------------------------------------------------------------------------
 * The plugin's checkout page, made right for this villa
 * ---------------------------------------------------------------------- */

/**
 * The checkout reads the booking details from the address and writes them straight into its
 * script. Only what a booking can contain is let through, so a changed address cannot put
 * code into the page.
 */
add_action(
	'template_redirect',
	function () {
		if ( ! is_page_template( 'guesty-checkout.php' ) ) {
			return;
		}
		$get = function ( $key ) {
			return isset( $_GET[ $key ] ) ? wp_unslash( $_GET[ $key ] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification, WordPress.Security.ValidatedSanitizedInput
		};
		$date = function ( $v ) {
			return preg_match( '/^\d{4}-\d{2}-\d{2}$/', (string) $v ) ? $v : '';
		};
		$_GET['listingId']  = preg_replace( '/[^A-Za-z0-9_-]/', '', (string) $get( 'listingId' ) );
		$_GET['checkIn']    = $date( $get( 'checkIn' ) );
		$_GET['checkOut']   = $date( $get( 'checkOut' ) );
		$_GET['occupancy']  = max( 1, min( 40, absint( $get( 'occupancy' ) ) ) );
		$_GET['villaName']  = sanitize_text_field( (string) $get( 'villaName' ) );
	},
	0
);

/** The plugin's checkout names another villa in its terms line. It is changed to this villa and its terms page. */
function bpt_engine_fix_checkout( $html ) {
	$name  = bpt_villa_name();
	$terms = bpt_page_url( 'terms-and-conditions' );
	$html  = preg_replace( '#href="/LEELAWADEE-Terms-and-Conditions\.pdf"#', 'href="' . esc_url( $terms ) . '"', $html );
	$html  = str_replace( 'of Villa Leelawadee.', 'of ' . esc_html( $name ) . '.', $html );
	return $html;
}
