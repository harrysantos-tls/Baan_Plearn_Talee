<?php
/**
 * One click setup: creates the pages, assigns their templates, fills the room
 * details and sets the home page. Safe to run again. It never deletes or
 * overwrites content you have written: existing page text is kept, and only
 * empty fields are filled.
 *
 * Runs automatically when the theme is activated, and from
 * Appearance > Villa setup.
 *
 * @package bpt
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function bpt_setup_pages() {
	$made    = 0;
	$updated = 0;

	// Home page.
	$home = bpt_find_page( 'home' );
	if ( ! $home ) {
		$front_id = (int) get_option( 'page_on_front' );
		$home     = $front_id ? get_post( $front_id ) : null;
	}
	if ( ! $home ) {
		$id = wp_insert_post( array(
			'post_type'   => 'page',
			'post_status' => 'publish',
			'post_title'  => 'Home',
			'post_name'   => 'home',
		) );
		if ( $id && ! is_wp_error( $id ) ) {
			$home = get_post( $id );
			$made++;
		}
	}
	if ( $home ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $home->ID );
	}

	$ul = function ( $items ) {
		return "<ul>\n<li>" . implode( "</li>\n<li>", array_map( 'esc_html', $items ) ) . "</li>\n</ul>";
	};

	foreach ( bpt_data() as $slug => $p ) {
		if ( 'checkout' === $slug && ! bpt_engine_active() ) {
			continue; // the checkout page belongs to the Guesty Booking Engine plugin
		}
		$existing = bpt_find_page( $slug );
		if ( ! $existing ) {
			// A page with this address that is in the Trash or a draft is brought back, instead of making a second one with "-2" on the end.
			$hidden = get_posts( array(
				'post_type'   => 'page',
				'name'        => $slug,
				'post_status' => array( 'trash', 'draft', 'pending', 'private', 'future' ),
				'numberposts' => 1,
			) );
			if ( $hidden ) {
				if ( 'trash' === $hidden[0]->post_status ) {
					wp_untrash_post( $hidden[0]->ID );
				}
				wp_update_post( array( 'ID' => $hidden[0]->ID, 'post_status' => 'publish' ) );
				$existing = get_post( $hidden[0]->ID );
			}
		}
		if ( $existing && in_array( $existing->post_status, array( 'trash', 'draft', 'pending', 'future' ), true ) ) {
			if ( 'trash' === $existing->post_status ) {
				wp_untrash_post( $existing->ID );
			}
			wp_update_post( array( 'ID' => $existing->ID, 'post_status' => 'publish' ) );
			$existing = get_post( $existing->ID );
		}
		$content  = '';
		if ( ! empty( $p['features'] ) && 'templates/template-room.php' === $p['template'] ) {
			$content = $ul( $p['features'] );
		}

		if ( ! $existing ) {
			$id = wp_insert_post( array(
				'post_type'    => 'page',
				'post_status'  => 'publish',
				'post_title'   => $p['title'],
				'post_name'    => $slug,
				'post_content' => $content,
			) );
			if ( ! $id || is_wp_error( $id ) ) {
				continue;
			}
			$made++;
		} else {
			$id = $existing->ID;
			if ( '' === trim( $existing->post_content ) && $content ) {
				wp_update_post( array( 'ID' => $id, 'post_content' => $content ) );
			}
			$updated++;
		}

		update_post_meta( $id, '_wp_page_template', $p['template'] );
		if ( ! empty( $p['intro'] ) ) {
			// Always sync the intro from pages-data.json so updates take effect on next version bump.
			update_post_meta( $id, '_bpt_intro', $p['intro'] );
		}
		if ( 'bedrooms' === ( $p['group'] ?? '' ) && ! get_post_meta( $id, '_bpt_bed', true ) ) {
			update_post_meta( $id, '_bpt_bed', $p['label'] );
		}
	}

	flush_rewrite_rules( false );
	return array( 'made' => $made, 'updated' => $updated );
}
/** When the theme is activated, clear the stored version so bpt_auto_setup always runs fresh. */
add_action(
	'after_switch_theme',
	function () {
		delete_option( 'bpt_setup_version' );
	}
);
add_action( 'after_switch_theme', 'bpt_setup_pages' );

function bpt_setup_menu() {
	add_theme_page( __( 'Villa setup', 'bpt' ), __( 'Villa setup', 'bpt' ), 'manage_options', 'bpt-setup', 'bpt_setup_screen' );
}
add_action( 'admin_menu', 'bpt_setup_menu' );

function bpt_setup_screen() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	echo '<div class="wrap"><h1>' . esc_html__( 'Villa setup', 'bpt' ) . '</h1>';
	if ( isset( $_GET['done'] ) ) {
		printf(
			'<div class="notice notice-success"><p>%s</p></div>',
			esc_html( sprintf( __( 'Done. %1$d pages created, %2$d existing pages updated to the new templates.', 'bpt' ), (int) $_GET['made'], (int) $_GET['updated'] ) )
		);
	}
	echo '<p>' . esc_html__( 'Creates every page the design uses (the rooms, services, floor plan, location, gallery, reviews and the booking summary), assigns the right template, and sets the home page. Text you have already written is kept.', 'bpt' ) . '</p>';
	echo '<form method="post" action="' . esc_url( admin_url( 'admin-post.php' ) ) . '">';
	echo '<input type="hidden" name="action" value="bpt_setup">';
	wp_nonce_field( 'bpt_setup', 'bpt_setup_nonce' );
	submit_button( __( 'Create or update the pages', 'bpt' ) );
	echo '</form></div>';
}

function bpt_setup_run() {
	if ( ! current_user_can( 'manage_options' ) || ! isset( $_POST['bpt_setup_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['bpt_setup_nonce'] ) ), 'bpt_setup' ) ) {
		wp_die( esc_html__( 'Not allowed.', 'bpt' ) );
	}
	$r = bpt_setup_pages();
	wp_safe_redirect( add_query_arg( array( 'page' => 'bpt-setup', 'done' => 1, 'made' => $r['made'], 'updated' => $r['updated'] ), admin_url( 'themes.php' ) ) );
	exit;
}
add_action( 'admin_post_bpt_setup', 'bpt_setup_run' );

/* The page template names, so they show up in the editor. */
function bpt_template_names( $templates ) {
	return $templates;
}


/* -------------------------------------------------------------------------
 * Self healing
 *
 * 1. The pages are created once, automatically, the first time the theme runs
 *    (and again when the theme is updated to a new version).
 * 2. A page whose template is missing or from an older theme is shown with the
 *    right template for its address.
 * 3. If an address belongs to one of the villa pages but the page does not
 *    exist yet, it is created and the visitor is sent straight to it.
 * ---------------------------------------------------------------------- */
function bpt_auto_setup() {
	if ( get_option( 'bpt_setup_version' ) === BPT_VERSION ) {
		return;
	}
	bpt_setup_pages();
	update_option( 'blogname', 'Baan Plearn Talee' );
	update_option( 'blogdescription', 'Luxury 6-Bedroom Private Villa in Kamala, Phuket' );
	update_option( 'bpt_setup_version', BPT_VERSION );
}
add_action( 'init', 'bpt_auto_setup', 20 );

/** Address to template. Aliases cover older page addresses. */
function bpt_template_map() {
	$map = array();
	foreach ( bpt_data() as $slug => $p ) {
		$map[ $slug ] = $p['template'];
	}
	$map['services-features'] = 'templates/template-services.php';
	$map['services']          = 'templates/template-services.php';
	$map['master-bedroom']    = 'templates/template-room.php';
	unset( $map['checkout'] );
	return $map;
}

function bpt_template_include( $template ) {
	if ( ! is_page() || is_front_page() ) {
		return $template;
	}
	$id       = get_queried_object_id();
	$assigned = get_page_template_slug( $id );
	if ( $assigned && file_exists( get_theme_file_path( $assigned ) ) ) {
		return $template; // already one of this theme's templates
	}
	$map  = bpt_template_map();
	$slug = get_post_field( 'post_name', $id );
	if ( isset( $map[ $slug ] ) ) {
		$found = locate_template( $map[ $slug ] );
		if ( $found ) {
			return $found;
		}
	}
	return $template;
}
add_filter( 'template_include', 'bpt_template_include', 20 );

function bpt_heal_missing_page() {
	if ( ! is_404() || is_admin() ) {
		return;
	}
	$path = trim( (string) wp_parse_url( isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '', PHP_URL_PATH ), '/' );
	$slug = basename( $path );
	if ( '' === $slug || ! isset( bpt_data()[ $slug ] ) ) {
		return;
	}
	$found = bpt_find_page( $slug );
	if ( $found && 'publish' === $found->post_status ) {
		return;
	}
	bpt_setup_pages();
	$page = bpt_find_page( $slug );
	if ( $page ) {
		wp_safe_redirect( get_permalink( $page ) );
		exit;
	}
}
add_action( 'template_redirect', 'bpt_heal_missing_page', 1 );
