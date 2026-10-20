<?php
/**
 * Baan Plearn Talee theme functions.
 *
 * @package bpt
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'BPT_VERSION', '5.37.0' );

/* -------------------------------------------------------------------------
 * Setup
 * ---------------------------------------------------------------------- */
function bpt_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'custom-logo', array(
		'height'      => 120,
		'width'       => 480,
		'flex-height' => true,
		'flex-width'  => true,
	) );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );

	add_image_size( 'bpt_hero', 2400, 1400, true );
	add_image_size( 'bpt_wide', 1600, 1200, true );
	add_image_size( 'bpt_portrait', 900, 1200, true );
	add_image_size( 'bpt_tile', 800, 600, true );

	register_nav_menus( array(
		'primary' => __( 'Primary', 'bpt' ),
		'footer'  => __( 'Footer', 'bpt' ),
	) );
}
add_action( 'after_setup_theme', 'bpt_setup' );

/* -------------------------------------------------------------------------
 * Assets
 * ---------------------------------------------------------------------- */
function bpt_assets() {
	$css = get_theme_file_path( 'style.css' );
	$js  = get_theme_file_path( 'assets/js/site.js' );

	wp_enqueue_style(
		'bpt-style',
		get_stylesheet_uri(),
		array(),
		file_exists( $css ) ? filemtime( $css ) : BPT_VERSION
	);
	wp_enqueue_script(
		'bpt-site',
		get_theme_file_uri( 'assets/js/site.js' ),
		array(),
		file_exists( $js ) ? filemtime( $js ) : BPT_VERSION,
		true
	);
	wp_localize_script( 'bpt-site', 'BPT', bpt_booking_config() );

	// The Guesty Booking Engine plugin writes its scripts into the page and needs jQuery before them.
	if ( bpt_is_engine_page() || is_page_template( 'guesty-checkout.php' ) ) {
		wp_enqueue_script( 'jquery' );
	}

	if ( is_page_template( 'templates/template-contact-us.php' ) ) {
		wp_enqueue_script( 'jquery-ui-datepicker' );
		// Load jQuery at the end of the page (the form's scripts are moved after it), and without the migration helper.
		global $wp_scripts;
		if ( isset( $wp_scripts->registered['jquery'] ) ) {
			$wp_scripts->registered['jquery']->deps = array( 'jquery-core' );
		}
		foreach ( array( 'jquery-core', 'jquery', 'jquery-ui-core', 'jquery-ui-datepicker' ) as $handle ) {
			wp_script_add_data( $handle, 'group', 1 );
		}
	}
}
add_action( 'wp_enqueue_scripts', 'bpt_assets' );

/** Add defer to the theme script to reduce render-blocking time. */
add_filter(
	'script_loader_tag',
	function ( $tag, $handle ) {
		if ( 'bpt-site' === $handle ) {
			return str_replace( ' src=', ' defer src=', $tag );
		}
		return $tag;
	},
	10,
	2
);



/* -------------------------------------------------------------------------
 * Villa details
 *
 * Edited in Appearance > Customize > Villa Details, so the contact block,
 * footer and enquiry page all read from one place.
 * ---------------------------------------------------------------------- */
function bpt_detail( $key ) {
	$defaults = array(
		'villa_name' => 'Baan Plearn Talee',
		'hero_tag'   => 'Estate',
		'meta_desc'  => 'Baan Plearn Talee is a six bedroom private villa in Kamala, Phuket, with an infinity pool, ocean views and a private chef. It sleeps up to twelve guests.',
		'hero_video' => 'https://baanplearntalee.com/wp-content/uploads/2026/10/plearnwebsite.mp4', // the home hero film. It can be changed in Appearance > Customize > Villa Details
		'address'  => '28 18 M.6 Kamala, Kathu District, Phuket 83150, Thailand',
		'phone'    => '+852 2435 4898',
		'email'    => 'reservations@theluxurysignature.com',
		'whatsapp' => '+852 2435 4898',
		'agency'   => 'The Luxury Signature',
		'terms'    => '/BAAN-PLEARN-TALEE-Terms-and-Conditions.pdf',
		'google_reviews' => 'https://share.google/F4LKLEObC4cuy2sIq',
	);
	$value = get_theme_mod( 'bpt_' . $key, isset( $defaults[ $key ] ) ? $defaults[ $key ] : '' );
	return $value;
}

function bpt_customize( $wp_customize ) {
	$wp_customize->add_section( 'bpt_details', array(
		'title'    => __( 'Villa Details', 'bpt' ),
		'priority' => 25,
	) );

	$fields = array(
		'villa_name' => __( 'Villa name (shown in the hero, booking window and footer)', 'bpt' ),
		'hero_tag'   => __( 'Line under the name in the home hero (leave empty to hide it)', 'bpt' ),
		'meta_desc'  => __( 'Search description for the home page (up to about 155 characters)', 'bpt' ),
		'address'  => __( 'Address', 'bpt' ),
		'phone'    => __( 'Phone', 'bpt' ),
		'email'    => __( 'Reservations email', 'bpt' ),
		'whatsapp' => __( 'WhatsApp number', 'bpt' ),
		'agency'   => __( 'Marketed by', 'bpt' ),
		'google_reviews' => __( 'Google reviews link', 'bpt' ),
	);

	foreach ( $fields as $key => $label ) {
		$wp_customize->add_setting( 'bpt_' . $key, array(
			'default'           => bpt_detail( $key ),
			'sanitize_callback' => 'wp_kses_post',
		) );
		$wp_customize->add_control( 'bpt_' . $key, array(
			'label'   => $label,
			'section' => 'bpt_details',
			'type'    => 'text',
		) );
	}

	$wp_customize->add_setting( 'bpt_logo_light', array( 'sanitize_callback' => 'esc_url_raw' ) );
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'bpt_logo_light', array(
		'label'       => __( 'Logo, light version', 'bpt' ),
		'description' => __( 'Used over the hero photograph and in the footer. The main logo under Site Identity is used everywhere else.', 'bpt' ),
		'section'     => 'bpt_details',
	) ) );

	$wp_customize->add_setting( 'bpt_hero_video', array( 'default' => bpt_detail( 'hero_video' ), 'sanitize_callback' => 'esc_url_raw' ) );
	$wp_customize->add_control( 'bpt_hero_video', array(
		'label'       => __( 'Home hero video link (MP4)', 'bpt' ),
		'description' => __( 'Paste the direct link to the film (a web address that ends in .mp4). Until one is set, the home hero shows a still photograph.', 'bpt' ),
		'section'     => 'bpt_details',
		'type'        => 'url',
	) );

	$wp_customize->add_setting( 'bpt_hero_image', array( 'sanitize_callback' => 'esc_url_raw' ) );
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'bpt_hero_image', array(
		'label'   => __( 'Home hero image', 'bpt' ),
		'section' => 'bpt_details',
	) ) );
}
add_action( 'customize_register', 'bpt_customize' );

/* -------------------------------------------------------------------------
 * Helpers
 * ---------------------------------------------------------------------- */

/* -------------------------------------------------------------------------
 * Favicon
 *
 * Bundled with the theme so it works on activation. If a Site Icon is set
 * under Appearance > Customize > Site Identity, WordPress handles it and this
 * stays out of the way.
 * ---------------------------------------------------------------------- */
function bpt_favicon() {
	if ( function_exists( 'has_site_icon' ) && has_site_icon() ) {
		return;
	}

	$dir = get_theme_file_uri( 'assets/img/favicon' );
	printf( '<link rel="icon" href="%s/favicon.ico" sizes="any">' . "\n", esc_url( $dir ) );
	printf( '<link rel="icon" type="image/png" sizes="32x32" href="%s/favicon-32.png">' . "\n", esc_url( $dir ) );
	printf( '<link rel="icon" type="image/png" sizes="16x16" href="%s/favicon-16.png">' . "\n", esc_url( $dir ) );
	printf( '<link rel="apple-touch-icon" sizes="180x180" href="%s/favicon-180.png">' . "\n", esc_url( $dir ) );
	printf( '<meta name="theme-color" content="%s">' . "\n", '#1A3E6C' );
}
add_action( 'wp_head', 'bpt_favicon', 5 );

/* -------------------------------------------------------------------------
 * Menu label overrides
 *
 * Applied at render time so the labels survive a theme re-upload and do not
 * depend on anyone editing Appearance > Menus. Edit or empty this list to
 * hand control back to the WordPress menu screen.
 * ---------------------------------------------------------------------- */
function bpt_menu_labels() {
	return array(
		'Check Availability' => __( 'Contact', 'bpt' ),
		'Check availability' => __( 'Contact', 'bpt' ),
	);
}

function bpt_rename_menu_items( $title, $item = null, $args = null ) {
	// Only touch the site's own navigation, never the admin menu screen.
	if ( is_admin() ) {
		return $title;
	}

	$map = bpt_menu_labels();
	$key = trim( wp_strip_all_tags( $title ) );

	return isset( $map[ $key ] ) ? $map[ $key ] : $title;
}
add_filter( 'nav_menu_item_title', 'bpt_rename_menu_items', 10, 3 );

/* -------------------------------------------------------------------------
 * Divi compatibility
 *
 * Pages built with Divi store their layout as [et_pb_*] shortcodes. This theme
 * does not run Divi, so those tags would print as raw text. We strip the tags
 * and keep the real content inside them, and we lift the header image out of
 * the builder markup so existing pages keep the photograph they were set up
 * with.
 * ---------------------------------------------------------------------- */

/**
 * Remove builder shortcode tags, keeping whatever sits between them.
 */
function bpt_strip_divi( $content ) {
	if ( ! is_string( $content ) || false === strpos( $content, '[et_pb_' ) ) {
		return $content;
	}
	$content = preg_replace( '/\[\/?et_pb_[a-z0-9_]*(?:[^\]]*)?\]/i', '', $content );
	return $content;
}
add_filter( 'the_content', 'bpt_strip_divi', 5 );

/**
 * The header image Divi stored on the page, if there is one.
 */
function bpt_divi_hero( $post_id = null ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	$raw     = get_post_field( 'post_content', $post_id );

	if ( ! $raw || false === strpos( $raw, 'background_image' ) ) {
		return '';
	}

	// Straight or curly quotes, since WordPress texturises stored content.
	$pattern = '/background_image=[\x{0022}\x{0027}\x{201C}\x{201D}\x{2018}\x{2019}]\s*(https?:\/\/[^\x{0022}\x{0027}\x{201C}\x{201D}\x{2018}\x{2019}\s]+\.(?:jpe?g|png|webp))/iu';

	if ( preg_match( $pattern, $raw, $m ) ) {
		return $m[1];
	}
	return '';
}

/**
 * Logo URL. 'dark' is the main logo set under Site Identity, 'light' is the
 * reversed version used over photography. Neither is ever filtered, so the
 * original artwork is preserved.
 */
function bpt_logo( $variant = 'dark' ) {
	if ( 'light' === $variant ) {
		$light = get_theme_mod( 'bpt_logo_light' );
		return $light ? $light : get_theme_file_uri( 'assets/img/logo-white.png' );
	}

	$logo_id = get_theme_mod( 'custom_logo' );
	if ( $logo_id ) {
		$url = wp_get_attachment_image_url( $logo_id, 'full' );
		if ( $url ) {
			return $url;
		}
	}
	return get_theme_file_uri( 'assets/img/logo-colour.png' );
}

/**
 * Image URL for a post, falling back to a supplied placeholder.
 */
function bpt_image( $post_id = null, $size = 'bpt_wide', $fallback = '' ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	if ( has_post_thumbnail( $post_id ) ) {
		return get_the_post_thumbnail_url( $post_id, $size );
	}
	return $fallback;
}

/**
 * Render an image tag with sensible loading attributes.
 */
function bpt_img( $url, $alt = '', $eager = false ) {
	if ( ! $url ) {
		return;
	}
	printf(
		'<img src="%s" alt="%s" loading="%s" decoding="async" %s>',
		esc_url( $url ),
		esc_attr( $alt ),
		$eager ? 'eager' : 'lazy',
		$eager ? 'fetchpriority="high"' : ''
	);
}

/**
 * Images attached to a page, used for the per room galleries.
 */
function bpt_attached_images( $post_id = null, $limit = 24 ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	return get_posts( array(
		'post_parent'    => $post_id,
		'post_type'      => 'attachment',
		'post_mime_type' => 'image',
		'posts_per_page' => $limit,
		'orderby'        => 'menu_order ID',
		'order'          => 'ASC',
	) );
}

/**
 * Split a page's list content into an inventory of rows.
 * Accepts a WordPress list block or one item per line.
 */
function bpt_inventory_items( $content ) {
	$content = bpt_strip_divi( $content );
	$items   = array();

	if ( preg_match_all( '/<li[^>]*>(.*?)<\/li>/is', $content, $m ) ) {
		$items = $m[1];
	} else {
		$plain = wp_strip_all_tags( $content );
		$items = preg_split( '/\r\n|\r|\n/', $plain );
	}

	$items = array_filter( array_map( function ( $i ) {
		return trim( wp_strip_all_tags( $i ) );
	}, $items ) );

	return array_values( $items );
}

/**
 * The bedroom pages, in display order, for the room rail.
 */
function bpt_room_pages() {
	$slugs = array( 'master-bedroom-1', 'guest-bedroom-1', 'guest-bedroom-2', 'guest-bedroom-3', 'guest-bedroom-4', 'guest-bedroom-5' );
	$rooms = array();
	foreach ( $slugs as $slug ) {
		$page = get_page_by_path( $slug );
		if ( $page ) {
			$rooms[] = $page;
		}
	}
	return $rooms;
}

/**
 * A short bed summary stored per bedroom page.
 */
function bpt_room_bed( $post_id ) {
	$bed = get_post_meta( $post_id, '_bpt_bed', true );
	return $bed ? $bed : '';
}

/* -------------------------------------------------------------------------
 * Bed summary field on pages
 * ---------------------------------------------------------------------- */
function bpt_meta_box() {
	add_meta_box( 'bpt_room', __( 'Room summary', 'bpt' ), 'bpt_meta_box_render', 'page', 'side' );
}
add_action( 'add_meta_boxes', 'bpt_meta_box' );

function bpt_meta_box_render( $post ) {
	wp_nonce_field( 'bpt_room_save', 'bpt_room_nonce' );
	$bed = get_post_meta( $post->ID, '_bpt_bed', true );
	echo '<p><label for="bpt_bed">' . esc_html__( 'Shown under the room name, for example: King bed, ocean view', 'bpt' ) . '</label>';
	echo '<input type="text" style="width:100%" id="bpt_bed" name="bpt_bed" value="' . esc_attr( $bed ) . '"></p>';
}

function bpt_meta_box_save( $post_id ) {
	if ( ! isset( $_POST['bpt_room_nonce'] ) || ! wp_verify_nonce( $_POST['bpt_room_nonce'], 'bpt_room_save' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	if ( isset( $_POST['bpt_bed'] ) ) {
		update_post_meta( $post_id, '_bpt_bed', sanitize_text_field( wp_unslash( $_POST['bpt_bed'] ) ) );
	}
}
add_action( 'save_post', 'bpt_meta_box_save' );

/* -------------------------------------------------------------------------
 * Enquiry form handling
 *
 * Posts to admin-post.php, stores the enquiry as a private record and emails
 * reservations. Works without a form plugin. If you prefer Gravity Forms or
 * WPForms, drop its shortcode into the Contact page instead.
 * ---------------------------------------------------------------------- */
function bpt_register_enquiry_cpt() {
	register_post_type( 'bpt_enquiry', array(
		'label'           => __( 'Enquiries', 'bpt' ),
		'public'          => false,
		'show_ui'         => true,
		'menu_icon'       => 'dashicons-email-alt',
		'capability_type' => 'post',
		'supports'        => array( 'title', 'editor' ),
	) );
}
add_action( 'init', 'bpt_register_enquiry_cpt' );

function bpt_handle_enquiry() {
	if ( ! isset( $_POST['bpt_enquiry_nonce'] ) || ! wp_verify_nonce( $_POST['bpt_enquiry_nonce'], 'bpt_enquiry' ) ) {
		wp_safe_redirect( home_url( '/contact-us/?sent=0' ) );
		exit;
	}

	// Simple honeypot.
	if ( ! empty( $_POST['bpt_website'] ) ) {
		wp_safe_redirect( home_url( '/contact-us/?sent=1' ) );
		exit;
	}

	$fields = array(
		'first_name'   => __( 'First name', 'bpt' ),
		'last_name'    => __( 'Last name', 'bpt' ),
		'email'        => __( 'Email', 'bpt' ),
		'phone'        => __( 'Phone', 'bpt' ),
		'arrival'      => __( 'Arrival', 'bpt' ),
		'departure'    => __( 'Departure', 'bpt' ),
		'flexibility'  => __( 'Date flexibility', 'bpt' ),
		'country'      => __( 'Country of residence', 'bpt' ),
		'adults'       => __( 'Adults', 'bpt' ),
		'children'     => __( 'Children', 'bpt' ),
		'child_ages'   => __( 'Ages of children', 'bpt' ),
		'bedrooms'     => __( 'Bedrooms required', 'bpt' ),
		'budget'       => __( 'Budget per night', 'bpt' ),
		'purpose'      => __( 'Purpose of stay', 'bpt' ),
		'channel'      => __( 'Preferred channel', 'bpt' ),
		'source'       => __( 'Heard about us', 'bpt' ),
		'comments'     => __( 'Comments', 'bpt' ),
		'consent'      => __( 'Marketing consent', 'bpt' ),
	);

	$lines = array();
	foreach ( $fields as $key => $label ) {
		$value = isset( $_POST[ $key ] ) ? sanitize_textarea_field( wp_unslash( $_POST[ $key ] ) ) : '';
		if ( 'consent' === $key ) {
			$value = empty( $_POST['consent'] ) ? __( 'No', 'bpt' ) : __( 'Yes', 'bpt' );
		}
		if ( '' !== $value ) {
			$lines[] = $label . ': ' . $value;
		}
	}

	$name = trim( sanitize_text_field( wp_unslash( $_POST['first_name'] ?? '' ) ) . ' ' . sanitize_text_field( wp_unslash( $_POST['last_name'] ?? '' ) ) );
	$body = implode( "\n", $lines );

	wp_insert_post( array(
		'post_type'    => 'bpt_enquiry',
		'post_status'  => 'private',
		'post_title'   => $name ? $name : __( 'Enquiry', 'bpt' ),
		'post_content' => $body,
	) );

	wp_mail(
		bpt_detail( 'email' ),
		sprintf( 'Baan Plearn Talee enquiry: %s', $name ),
		$body,
		array( 'Content-Type: text/plain; charset=UTF-8' )
	);

	wp_safe_redirect( home_url( '/contact-us/?sent=1' ) );
	exit;
}
add_action( 'admin_post_nopriv_bpt_enquiry', 'bpt_handle_enquiry' );
add_action( 'admin_post_bpt_enquiry', 'bpt_handle_enquiry' );

/** The home page, and the copy of it used to test the Guesty booking. */
function bpt_is_home_like() {
	return is_front_page() || is_page_template( 'templates/template-home-guesty.php' );
}

/* -------------------------------------------------------------------------
 * Body classes
 * ---------------------------------------------------------------------- */
function bpt_body_class( $classes ) {
	if ( bpt_is_home_like() ) {
		$classes[] = 'bpt-home';
	}
	if ( is_page_template( 'templates/template-booking.php' ) ) {
		$classes[] = 'bpt-booking-page';
	}
	return $classes;
}
add_filter( 'body_class', 'bpt_body_class' );

/* -------------------------------------------------------------------------
 * Content seeder
 * ---------------------------------------------------------------------- */
require_once get_theme_file_path( 'inc/integration.php' );
require_once get_theme_file_path( 'inc/booking.php' );
require_once get_theme_file_path( 'inc/engine.php' );
require_once get_theme_file_path( 'inc/setup.php' );


/** The villa's name, set in Appearance > Customize > Villa Details. */
function bpt_villa_name() {
	$name = bpt_detail( 'villa_name' );
	return $name ? $name : 'Baan Plearn Talee';
}


/**
 * The browser tab and search results use the villa name, not the WordPress Site Title.
 * Home: "Baan Plearn Talee". Other pages: "Pool Area - Baan Plearn Talee".
 */
function bpt_document_title_parts( $parts ) {
	$name = bpt_villa_name();
	if ( is_front_page() ) {
		// Use the full SEO title on the homepage — no site name appended.
		return array( 'title' => 'Baan Plearn Talee Phuket - Luxury Villa Rental in Phuket with 6 Bedrooms' );
	}
	if ( isset( $parts['site'] ) ) {
		$parts['site'] = $name;
	}
	return $parts;
}
add_filter( 'document_title_parts', 'bpt_document_title_parts', 20 );


/** Splits a title into words and letters, so each letter can arrive on its own (home hero). */
function bpt_split_title( $text ) {
	$words = preg_split( '/\s+/', trim( $text ) );
	$i     = 0;
	$out   = '';
	foreach ( $words as $wi => $word ) {
		$out .= '<span class="w">';
		foreach ( preg_split( '//u', $word, -1, PREG_SPLIT_NO_EMPTY ) as $ch ) {
			$out .= '<span class="l" aria-hidden="true" style="--i:' . (int) $i . '">' . esc_html( $ch ) . '</span>';
			$i++;
		}
		$out .= '</span>' . ( $wi < count( $words ) - 1 ? ' ' : '' );
	}
	return $out;
}

/** Marks the page as script-enabled before it paints, so the reveal animations never hide content for visitors without JavaScript. */
function bpt_js_class() {
	echo "<script>document.documentElement.className+=' js';</script>\n";
}
add_action( 'wp_head', 'bpt_js_class', 1 );


/**
 * The home hero film: the link set in Appearance > Customize > Villa Details,
 * otherwise assets/video/hero.mp4 if one has been placed in the theme, otherwise none.
 */
function bpt_hero_video_url() {
	$set = bpt_detail( 'hero_video' );
	if ( $set ) {
		return $set;
	}
	if ( file_exists( get_theme_file_path( 'assets/video/hero.mp4' ) ) ) {
		return get_theme_file_uri( 'assets/video/hero.mp4' );
	}
	return '';
}


/** The video type from the link, so the browser knows what it is getting. */
function bpt_video_type( $url ) {
	$path = (string) wp_parse_url( $url, PHP_URL_PATH );
	if ( preg_match( '/\.webm$/i', $path ) ) {
		return 'video/webm';
	}
	if ( preg_match( '/\.(mp4|m4v)$/i', $path ) ) {
		return 'video/mp4';
	}
	return '';
}


/* -------------------------------------------------------------------------
 * Speed
 * ---------------------------------------------------------------------- */

/** Load the two fonts used above the fold ahead of time, and the home photograph that is painted first. */
/** Custom title for the home page. */
add_filter(
	'pre_get_document_title',
	function ( $title ) {
		if ( is_front_page() ) {
			return 'Baan Plearn Talee Phuket - Luxury Villa Rental in Phuket with 6 Bedrooms';
		}
		return $title;
	}
);

function bpt_preloads() {
	foreach ( array( 'jost-latin-400-normal.woff2', 'prata-latin-400-normal.woff2' ) as $font ) {
		printf( '<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n", esc_url( get_theme_file_uri( 'assets/fonts/' . $font ) ) );
	}
	if ( bpt_is_home_like() ) {
		$webp = get_theme_file_path( 'assets/video/hero-poster.webp' );
		$poster_uri = file_exists( $webp ) ? get_theme_file_uri( 'assets/video/hero-poster.webp' ) : get_theme_file_uri( 'assets/video/hero-poster.jpg' );
		printf( '<link rel="preload" href="%s" as="image" fetchpriority="high" imagesizes="100vw">' . "\n", esc_url( $poster_uri ) );		printf( '<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n", esc_url( get_theme_file_uri( 'assets/fonts/eb-garamond-latin-500-normal.woff2' ) ) );
	}
}
add_action( 'wp_head', 'bpt_preloads', 2 );

/** Google Analytics 4 and site verification. */
function bpt_google_head() {
	echo '<meta name="google-site-verification" content="VIOygyb854qf9RdfddCsWUjoRINn1Olq-8Fqz43lrJA">' . "\n";
	echo '<script async src="https://www.googletagmanager.com/gtag/js?id=G-EWC83XH48J"></script>' . "\n";
	echo '<script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag(\'js\',new Date());gtag(\'config\',\'G-EWC83XH48J\');</script>' . "\n";
}
add_action( 'wp_head', 'bpt_google_head', 1 );

/** Remove what the theme does not use: emoji scripts, the embed script, and the block editor's front-end styles. */
function bpt_trim_head() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
	remove_action( 'wp_head', 'wp_generator' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
	remove_action( 'wp_head', 'rsd_link' );
	remove_action( 'wp_head', 'wp_shortlink_wp_head' );
}
add_action( 'init', 'bpt_trim_head' );

function bpt_dequeue_extras() {
	wp_dequeue_script( 'wp-embed' );
	wp_dequeue_style( 'jquery-ui-core' );
	wp_dequeue_style( 'wp-jquery-ui-dialog' );
	// The villa pages are built from the theme's own templates, so the block styles are only kept on plain pages and posts.
	if ( is_front_page() || is_page_template() ) {
		wp_dequeue_style( 'wp-block-library' );
		wp_dequeue_style( 'wp-block-library-theme' );
		wp_dequeue_style( 'classic-theme-styles' );
		wp_dequeue_style( 'global-styles' );
	}
}
add_action( 'wp_enqueue_scripts', 'bpt_dequeue_extras', 100 );

/**
 * Photographs from the theme get their width and height (so the page does not jump while they load),
 * and, where a smaller copy exists, a srcset so phones download the smaller one.
 */
function bpt_img_meta() {
	static $meta = null;
	if ( null === $meta ) {
		$file = get_theme_file_path( 'inc/img-meta.json' );
		$meta = file_exists( $file ) ? json_decode( file_get_contents( $file ), true ) : array();
		if ( ! is_array( $meta ) ) {
			$meta = array();
		}
	}
	return $meta;
}

function bpt_optimise_img( $m ) {
	$tag = $m[0];
	if ( ! preg_match( '/\ssrc="([^"]*\/assets\/img\/([^"?#]+))"/', $tag, $s ) ) {
		return $tag;
	}
	$meta = bpt_img_meta();
	$rel  = $s[2];
	if ( empty( $meta[ $rel ] ) ) {
		return $tag;
	}
	$info = $meta[ $rel ];
	$add  = '';
	if ( ! preg_match( '/\swidth=/', $tag ) ) {
		$add .= ' width="' . (int) $info['w'] . '" height="' . (int) $info['h'] . '"';
	}
	if ( ! empty( $info['v'] ) && ! preg_match( '/\ssrcset=/', $tag ) ) {
		$set = array();
		foreach ( $info['v'] as $w ) {
			$set[] = preg_replace( '/\.jpg$/', '-' . (int) $w . '.jpg', $s[1] ) . ' ' . (int) $w . 'w';
		}
		$set[] = $s[1] . ' ' . (int) $info['w'] . 'w';
		$sizes = preg_match( '/fetchpriority="high"/', $tag ) ? '100vw' : '(max-width: 800px) 100vw, 800px';
		$add  .= ' srcset="' . esc_attr( implode( ', ', $set ) ) . '" sizes="' . esc_attr( $sizes ) . '"';
	}
	if ( ! preg_match( '/\sdecoding=/', $tag ) ) {
		$add .= ' decoding="async"';
	}
	return rtrim( substr( $tag, 0, -1 ), '/ ' ) . $add . '>';
}

function bpt_label_fields( $html ) {
	return preg_replace_callback(
		'/(<div class="field[^"]*">\s*)<label([^>]*)>(.*?)<\/label>(\s*)<(input|select|textarea)\b([^>]*)>/s',
		function ( $m ) {
			$attrs = $m[6];
			if ( preg_match( '/\sid="([^"]+)"/', $attrs, $id ) ) {
				$field_id = $id[1];
			} elseif ( preg_match( '/\sname="([^"]+)"/', $attrs, $name ) ) {
				$field_id = 'f_' . preg_replace( '/[^A-Za-z0-9_-]/', '', $name[1] );
				$attrs   .= ' id="' . $field_id . '"';
			} else {
				return $m[0];
			}
			return $m[1] . '<label' . $m[2] . ' for="' . esc_attr( $field_id ) . '">' . $m[3] . '</label>' . $m[4] . '<' . $m[5] . $attrs . '>';
		},
		$html
	);
}

function bpt_move_form_scripts( $html ) {
	$moved = '';
	$html  = preg_replace_callback(
		'/<script>(?:(?!<\/script>).)*jQuery(?:(?!<\/script>).)*<\/script>/s',
		function ( $m ) use ( &$moved ) {
			$moved .= $m[0] . "\n";
			return '';
		},
		$html
	);
	return $moved ? preg_replace( '/<\/body>/', $moved . '</body>', $html, 1 ) : $html;
}

function bpt_optimise_html( $html ) {
	$html = preg_replace_callback( '/<img\b[^>]*>/i', 'bpt_optimise_img', $html );
	if ( function_exists( 'is_page_template' ) && is_page_template( 'templates/template-contact-us.php' ) ) {
		$html = bpt_move_form_scripts( bpt_label_fields( $html ) );
	}
	if ( function_exists( 'is_page_template' ) && is_page_template( 'guesty-checkout.php' ) && function_exists( 'bpt_engine_fix_checkout' ) ) {
		$html = bpt_engine_fix_checkout( $html );
	}
	return $html;
}

function bpt_start_buffer() {
	if ( is_admin() || is_feed() || is_embed() || ( function_exists( 'wp_doing_ajax' ) && wp_doing_ajax() ) ) {
		return;
	}
	ob_start( 'bpt_optimise_html' );
}
add_action( 'template_redirect', 'bpt_start_buffer', 0 );

/* -------------------------------------------------------------------------
 * Search engines
 *
 * Every page gets a description, and the home page gets the details a search
 * engine uses for a villa listing. A page can have its own description in the
 * box on the right of the page editor; otherwise one is made from the page.
 * ---------------------------------------------------------------------- */
function bpt_meta_description() {
	if ( is_singular() ) {
		$custom = get_post_meta( get_the_ID(), '_bpt_meta_desc', true );
		if ( $custom ) {
			return $custom;
		}
		if ( has_excerpt() ) {
			return wp_strip_all_tags( get_the_excerpt() );
		}
		if ( ! is_front_page() ) {
			$data  = bpt_data();
			$slug  = get_post_field( 'post_name', get_the_ID() );
			$fixed = array(
				'gallery'                    => 'Photographs of Baan Plearn Talee in Kamala, Phuket: the infinity pool, the living and dining areas, the media room, the bedrooms and the villa from above.',
				'reviews'                    => 'What guests say about their stay at Baan Plearn Talee: the pool, the staff, the private chef and the ocean views.',
				'services-features-overview' => 'Everything included at Baan Plearn Talee: a private chef and four staff, daily housekeeping, airport transfers, 24 hour security, the pool, a media room, a fitness room and more.',
				'floor-plan'                 => 'The layout of Baan Plearn Talee: the living and dining areas open onto the pool deck, with the six bedrooms set along the terrace.',
				'location'                   => "Between Kamala and Patong on Phuket's west coast, Baan Plearn Talee is 2 km from Yae Beach and 31.4 km from Phuket International Airport.",
				'terms-and-conditions'      => 'The booking agreement terms and conditions for Baan Plearn Talee: check-in and check-out, payment, security deposit, cancellation and house rules.',
				'contact-us'                 => 'Send your dates and a few details and the reservations team at The Luxury Signature will come back with availability and rates for Baan Plearn Talee.',
			);
			if ( isset( $fixed[ $slug ] ) ) {
				return $fixed[ $slug ];
			}
			if ( ! empty( $data[ $slug ]['intro'] ) ) {
				return wp_strip_all_tags( is_array( $data[ $slug ]['intro'] ) ? implode( ' ', $data[ $slug ]['intro'] ) : $data[ $slug ]['intro'] );
			}
			$text = wp_trim_words( wp_strip_all_tags( strip_shortcodes( get_post_field( 'post_content', get_the_ID() ) ) ), 28, '' );
			if ( strlen( $text ) > 60 ) {
				return $text;
			}
		}
	}
	return bpt_detail( 'meta_desc' );
}

function bpt_head_seo() {
	$desc = trim( preg_replace( '/\s+/', ' ', bpt_meta_description() ) );
	if ( strlen( $desc ) > 158 ) {
		$desc = rtrim( mb_substr( $desc, 0, 155 ) ) . '...';
	}
	$title = wp_get_document_title();
	$url   = is_singular() ? get_permalink() : home_url( '/' );
	$image = 'https://baanplearntalee.com/wp-content/uploads/2023/07/Villa-Plearn-Talee-Phuket-Pool-Area-2.jpg';
	if ( is_singular() && has_post_thumbnail() ) {
		$image = get_the_post_thumbnail_url( null, 'large' );
	}
	// On the home page use the fixed SEO title, description, image and URL.
	if ( is_front_page() ) {
		$title = esc_attr( 'Baan Plearn Talee Phuket - Luxury Villa Rental in Phuket with 6 Bedrooms' );
		$desc  = esc_attr( 'Baan Plearn Talee is a six bedroom private villa in Kamala, Phuket, with an infinity pool, ocean views and a private chef. It sleeps up to twelve guests.' );
		$url   = esc_url( 'https://baanplearntalee.com/' );
		$image = esc_url( 'https://baanplearntalee.com/wp-content/uploads/2023/07/Villa-Plearn-Talee-Phuket-Pool-Area-2.jpg' );
	} else {
		$title = esc_attr( $title );
		$desc  = esc_attr( $desc );
		$url   = esc_url( $url );
		$image = esc_url( $image );
	}
	echo '<meta name="title" content="' . $title . '">' . "\n";
	// meta name="description" is output by Yoast SEO — skip here to avoid duplicates.
	echo '<meta property="og:type" content="' . ( is_front_page() ? 'website' : 'article' ) . '">' . "\n";
	echo '<meta property="og:site_name" content="' . esc_attr( bpt_villa_name() ) . '">' . "\n";
	echo '<meta property="og:title" content="' . $title . '">' . "\n";
	echo '<meta property="og:description" content="' . $desc . '">' . "\n";
	echo '<meta property="og:url" content="' . $url . '">' . "\n";
	echo '<meta property="og:image" content="' . $image . '">' . "\n";
	echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
	echo '<meta property="twitter:url" content="' . $url . '">' . "\n";
	echo '<meta property="twitter:title" content="' . $title . '">' . "\n";
	echo '<meta property="twitter:description" content="' . $desc . '">' . "\n";
	echo '<meta property="twitter:image" content="' . $image . '">' . "\n";
	if ( is_front_page() ) {
		$tel = preg_replace( '/[^0-9+]/', '', bpt_detail( 'phone' ) );
		$ld  = array(
			'@context'    => 'https://schema.org',
			'@type'       => 'LodgingBusiness',
			'name'        => bpt_villa_name(),
			'description' => $desc,
			'url'         => home_url( '/' ),
			'image'       => $image,
			'telephone'   => $tel,
			'email'       => bpt_detail( 'email' ),
			'address'     => array(
				'@type'           => 'PostalAddress',
				'streetAddress'   => '28 18 M.6',
				'addressLocality' => 'Kamala, Kathu District',
				'addressRegion'   => 'Phuket',
				'postalCode'      => '83150',
				'addressCountry'  => 'TH',
			),
		);
		echo '<script type="application/ld+json">' . wp_json_encode( $ld, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . "</script>\n";
	}
}
add_action( 'wp_head', 'bpt_head_seo', 3 );

/** A box on the right of the page editor for the page's own description. */
function bpt_seo_box() {
	add_meta_box( 'bpt_meta_desc', __( 'Search description', 'bpt' ), 'bpt_seo_box_html', 'page', 'side', 'default' );
}
add_action( 'add_meta_boxes', 'bpt_seo_box' );

function bpt_seo_box_html( $post ) {
	wp_nonce_field( 'bpt_meta_desc', 'bpt_meta_desc_nonce' );
	printf(
		'<p><textarea name="bpt_meta_desc" rows="4" style="width:100%%" maxlength="170">%s</textarea></p><p class="description">%s</p>',
		esc_textarea( get_post_meta( $post->ID, '_bpt_meta_desc', true ) ),
		esc_html__( 'The text shown under the page title in Google. One or two sentences, up to about 155 characters.', 'bpt' )
	);
}

function bpt_seo_box_save( $post_id ) {
	if ( ! isset( $_POST['bpt_meta_desc_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['bpt_meta_desc_nonce'] ) ), 'bpt_meta_desc' ) || ! current_user_can( 'edit_page', $post_id ) ) {
		return;
	}
	update_post_meta( $post_id, '_bpt_meta_desc', sanitize_textarea_field( wp_unslash( $_POST['bpt_meta_desc'] ?? '' ) ) );
}
add_action( 'save_post_page', 'bpt_seo_box_save' );


/** The booking summary is a step in a booking, not a page to find in Google. */
function bpt_robots( $robots ) {
	$is_private = is_page( 'booking-summary' )
		|| is_page_template( 'templates/template-home-guesty.php' )
		|| is_page_template( 'guesty-checkout.php' );
	if ( $is_private ) {
		$robots['noindex'] = true;
		$robots['follow']  = true;
		unset( $robots['max-image-preview'] );
	} else {
		unset( $robots['noindex'] );
		$robots['index']  = true;
		$robots['follow'] = true;
	}
	return $robots;
}
add_filter( 'wp_robots', 'bpt_robots' );

/** Force index/follow for public pages, bypassing any WordPress "discourage" setting. */
function bpt_force_index() {
	$is_private = is_page( 'booking-summary' )
		|| is_page_template( 'templates/template-home-guesty.php' )
		|| is_page_template( 'guesty-checkout.php' );
	if ( $is_private ) {
		echo '<meta name="robots" content="noindex,follow">' . "\n";
	} else {
		echo '<meta name="robots" content="index,follow">' . "\n";
	}
}
add_action( 'wp_head', 'bpt_force_index', 1 );

function bpt_canonical() {
	if ( is_singular() ) {
		echo '<link rel="canonical" href="' . esc_url( get_permalink() ) . '">' . "\n";
	} elseif ( is_front_page() ) {
		echo '<link rel="canonical" href="' . esc_url( home_url( '/' ) ) . '">' . "\n";
	}
}
add_action( 'wp_head', 'bpt_canonical', 4 );


/** The booking agreement, from inc/terms-data.json. */
function bpt_terms() {
	static $terms = null;
	if ( null === $terms ) {
		$file  = get_theme_file_path( 'inc/terms-data.json' );
		$terms = file_exists( $file ) ? json_decode( file_get_contents( $file ), true ) : array();
		if ( ! is_array( $terms ) ) {
			$terms = array();
		}
	}
	return $terms;
}


/** Remove jQuery Migrate from the front end — it is only needed by very old plugins. */
add_action(
	'wp_default_scripts',
	function ( $scripts ) {
		if ( ! is_admin() && isset( $scripts->registered['jquery'] ) ) {
			$script = $scripts->registered['jquery'];
			if ( $script->deps ) {
				$script->deps = array_diff( $script->deps, array( 'jquery-migrate' ) );
			}
		}
	}
);

/** Inline the critical CSS for above-the-fold content so the page renders without waiting for style.css. */
function bpt_critical_css() {
	$css = ':root{--deep:#0E343F;--ink:#1A282C;--mute:#5a6b6f;--foam-l:#DCE8E5;--display:"Prata",serif;--sans:"Jost",sans-serif;--serif:"Prata",serif;--head-h:96px}*,*::before,*::after{box-sizing:border-box}body{margin:0;font-family:var(--sans);color:var(--ink);background:#fff}.site-head{position:fixed;top:0;left:0;right:0;z-index:100;display:flex;align-items:center;height:var(--head-h);padding:0 32px}.brand{display:block;height:56px;flex:none}.brand img{height:56px;width:auto}.hero{position:relative;overflow:hidden;width:100%;height:100svh;min-height:560px;background:var(--deep)}.hero__media{position:absolute;inset:0;width:100%;height:100%;overflow:hidden;background:#0e1e22}.hero__video{width:100%;height:100%;object-fit:cover;display:block}';
	echo '<style>' . $css . '</style>' . "\n";
}
add_action( 'wp_head', 'bpt_critical_css', 0 );



