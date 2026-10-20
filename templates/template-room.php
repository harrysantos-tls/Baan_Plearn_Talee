<?php
/**
 * Template Name: Room (photos and details side by side)
 *
 * Used for every space and bedroom: pool, living, kitchen and dining, media and
 * game room, fitness, sala, and the six bedrooms.
 *
 * @package bpt
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();

while ( have_posts() ) :
	the_post();
	$id      = get_the_ID();
	$slug    = get_post_field( 'post_name', $id );
	$data    = bpt_data();
	$d       = isset( $data[ $slug ] ) ? $data[ $slug ] : array();
	$gallery = bpt_room_gallery( $id );
	$bed     = bpt_room_bed( $id );
	$intro   = get_post_meta( $id, '_bpt_intro', true );
	$intro   = $intro ? $intro : ( isset( $d['intro'] ) ? $d['intro'] : '' );
	$label   = $bed ? $bed : ( isset( $d['label'] ) ? $d['label'] : __( 'In this space', 'bpt' ) );
	$items   = bpt_inventory_items( get_the_content() );
	if ( ! $items && ! empty( $d['features'] ) ) {
		$items = $d['features'];
	}

	// Previous and next, inside the same group.
	$group = ( isset( $d['group'] ) && 'bedrooms' === $d['group'] )
		? array( 'master-bedroom-1', 'guest-bedroom-1', 'guest-bedroom-2', 'guest-bedroom-3', 'guest-bedroom-4', 'guest-bedroom-5' )
		: array( 'pool-area', 'living-area', 'kitchen-dining-area', 'media-game-room', 'fitness-room', 'outdoor-sala' );
	$pos  = array_search( $slug, $group, true );
	$prev = false !== $pos ? $group[ ( $pos + count( $group ) - 1 ) % count( $group ) ] : '';
	$next = false !== $pos ? $group[ ( $pos + 1 ) % count( $group ) ] : '';

	get_template_part( 'template-parts/pagehero', null, array(
		'image' => $gallery ? $gallery[0]['url'] : '',
		'title' => get_the_title(),
		'crumb' => __( 'The villa', 'bpt' ),
		'size'  => 'short',
	) );
	?>
	<section class="room">
		<div class="wrap">
			<div class="room__info">
				<div class="room__label"><?php echo esc_html( $label ); ?></div>
				<?php if ( $intro ) : ?><p class="room__intro"><?php echo nl2br( esc_html( $intro ) ); ?></p><?php endif; ?>
			</div>
			<?php if ( $items ) : ?>
			<div class="feat-cards"><?php foreach ( $items as $it ) : ?><span class="feat-card"><?php echo esc_html( $it ); ?></span><?php endforeach; ?></div>
			<?php endif; ?>
		</div>
		<div class="wrap">
			<div class="room__photos">
				<?php foreach ( $gallery as $i => $g ) : ?>
					<img src="<?php echo esc_url( $g['url'] ); ?>" alt="<?php echo esc_attr( $g['alt'] ); ?>" loading="<?php echo $i < 2 ? 'eager' : 'lazy'; ?>">
				<?php endforeach; ?>
			</div>
		</div>
		<?php if ( $prev && $next ) : ?>
			<div class="wrap">
				<div class="room__nav">
					<a class="tl" href="<?php echo esc_url( bpt_page_url( $prev ) ); ?>">&#8249; <?php echo esc_html( bpt_page_title( $prev ) ); ?></a>
					<a class="tl" href="<?php echo esc_url( bpt_page_url( $next ) ); ?>"><?php echo esc_html( bpt_page_title( $next ) ); ?> &#8250;</a>
				</div>
			</div>
		<?php endif; ?>
	</section>
	<?php
endwhile;

get_template_part( 'template-parts/cta' );
get_footer();
