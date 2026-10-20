<?php
/**
 * Template Name: Reviews (featured and grid)
 *
 * @package bpt
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
$d       = bpt_data();
$reviews = isset( $d['reviews']['reviews'] ) ? $d['reviews']['reviews'] : array();
$lead    = $reviews ? array_shift( $reviews ) : null;

get_template_part( 'template-parts/pagehero', null, array(
	'image' => bpt_asset( 'home/sala.jpg' ),
	'title' => get_the_title(),
	'crumb' => __( 'Guests', 'bpt' ),
	'size'  => 'short',
	'position' => '50% 58%',
) );
?>
<section class="block">
	<div class="wrap">
		<?php if ( $lead ) : ?>
			<figure class="featured"><div class="featured__stars" aria-label="5 out of 5 stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div><blockquote><?php echo esc_html( $lead['quote'] ); ?></blockquote>
				<figcaption><?php echo esc_html( $lead['name'] ); ?></figcaption>
			</figure>
		<?php endif; ?>
		<div class="rgrid">
			<?php foreach ( $reviews as $r ) : ?>
				<figure class="rbox">
					<?php if ( ! empty( $r['sample'] ) ) : ?>
						<p><strong><?php esc_html_e( 'Fictional example — not a guest review', 'bpt' ); ?></strong></p>
					<?php else : ?>
						<div class="rbox__stars" aria-label="5 out of 5 stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
					<?php endif; ?>
					<blockquote><?php echo esc_html( $r['quote'] ); ?></blockquote>
					<?php if ( empty( $r['sample'] ) ) : ?>
						<figcaption><?php echo esc_html( $r['name'] ); ?></figcaption>
					<?php endif; ?>
				</figure>
			<?php endforeach; ?>
		</div>
		<div class="google-line">
			<div class="serif"><?php esc_html_e( 'More reviews on Google', 'bpt' ); ?></div>
			<a class="tl" href="<?php echo esc_url( bpt_detail( 'google_reviews' ) ); ?>" rel="noopener"><?php esc_html_e( 'Read our reviews on Google', 'bpt' ); ?></a>
		</div>
	</div>
</section>
<?php
get_template_part( 'template-parts/cta' );
get_footer();
