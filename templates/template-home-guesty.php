<?php
/**
 * Template Name: Home (Guesty booking test)
 *
 * A copy of the home page whose booking is done by the Guesty Booking Engine plugin,
 * to compare with the home page. It is hidden from search engines and not in the menu.
 * Set the Listing ID in Settings > Booking (Guesty).
 *
 * @package bpt
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
get_template_part( 'template-parts/home' );
?>
<div class="gtest" aria-hidden="true"><?php esc_html_e( 'Guesty booking test', 'bpt' ); ?></div>
<?php get_footer(); ?>
