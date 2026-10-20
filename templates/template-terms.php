<?php
/**
 * Template Name: Terms and conditions
 *
 * The booking agreement. A list of the clauses on the left that follows the reader,
 * the full text on the right. The text is in inc/terms-data.json.
 *
 * @package bpt
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
$terms = bpt_terms();

while ( have_posts() ) :
	the_post();
	get_template_part( 'template-parts/pagehero', null, array(
		'image'    => bpt_asset( 'home/lifestyle.jpg' ),
		'title'    => __( 'Terms & Conditions', 'bpt' ),
		'crumb'    => __( 'Good to know', 'bpt' ),
		'size'     => 'short',
		'position' => '50% 55%',
	) );
endwhile;
?>
<section class="tc">
	<div class="wrap">
		<header class="tc__intro">
			<div class="kicker"><?php esc_html_e( 'Booking Agreement', 'bpt' ); ?></div>
			<h2><?php esc_html_e( 'Terms & Conditions', 'bpt' ); ?></h2>
			<div class="tc__meta"><?php echo esc_html( $terms['property'] ?? '' ); ?> &middot; <?php echo esc_html( $terms['revised'] ?? '' ); ?></div>
			<p><?php echo esc_html( $terms['preamble'] ?? '' ); ?></p>
		</header>

		<div class="tc__grid">
			<nav class="toc" aria-label="<?php esc_attr_e( 'Contents', 'bpt' ); ?>">
				<details class="toc__d" open>
					<summary><?php esc_html_e( 'Contents', 'bpt' ); ?></summary>
					<ol>
						<?php foreach ( $terms['clauses'] ?? array() as $c ) : ?>
							<li><a href="#c<?php echo (int) $c['n']; ?>"><span><?php echo (int) $c['n']; ?></span><span><?php echo esc_html( $c['title'] ); ?></span></a></li>
						<?php endforeach; ?>
					</ol>
				</details>
			</nav>

			<div class="tc__body">
				<?php foreach ( $terms['clauses'] ?? array() as $c ) : ?>
					<article class="tc-clause" id="c<?php echo (int) $c['n']; ?>">
						<div class="tc-clause__head"><span class="tc-clause__n"><?php echo (int) $c['n']; ?></span><h3><?php echo esc_html( $c['title'] ); ?></h3></div>
						<?php
						$open_list = false;
						foreach ( $c['blocks'] as $b ) {
							if ( 'li' !== $b['t'] && $open_list ) {
								echo '</ul>';
								$open_list = false;
							}
							if ( 'li' === $b['t'] ) {
								if ( ! $open_list ) {
									echo '<ul class="tc-list">';
									$open_list = true;
								}
								echo '<li>' . esc_html( $b['x'] ) . '</li>';
							} elseif ( 'h' === $b['t'] ) {
								echo '<p class="tc-sub">' . esc_html( $b['x'] ) . '</p>';
							} else {
								echo '<p>' . esc_html( $b['x'] ) . '</p>';
							}
						}
						if ( $open_list ) {
							echo '</ul>';
						}
						?>
					</article>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>
<?php get_footer(); ?>
