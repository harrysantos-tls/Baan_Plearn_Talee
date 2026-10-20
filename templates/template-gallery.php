<?php
/**
 * Template Name: Gallery (tabs)
 *
 * Photographs grouped by category, one category at a time, so the page stays short.
 *
 * @package bpt
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();

$room = function ( $slug, $nums = null ) {
	$dir   = get_theme_file_path( 'assets/img/rooms/' . $slug );
	$files = is_dir( $dir ) ? glob( $dir . '/*.jpg' ) : array();
	natsort( $files );
	$out = array();
	foreach ( $files as $f ) {
		$n = (int) basename( $f, '.jpg' );
		if ( null === $nums || in_array( $n, $nums, true ) ) {
			$out[] = bpt_asset( 'rooms/' . $slug . '/' . basename( $f ) );
		}
	}
	return $out;
};
$cats = array(
	__( 'Pool and outdoors', 'bpt' ) => array_merge( $room( 'pool-area' ), $room( 'outdoor-sala' ) ),
	__( 'Living and dining', 'bpt' ) => array_merge( $room( 'living-area' ), $room( 'kitchen-dining-area' ) ),
	__( 'Media and fitness', 'bpt' ) => array_merge( $room( 'media-game-room' ), $room( 'fitness-room' ) ),
	__( 'Bedrooms', 'bpt' )          => array_merge( $room( 'master-bedroom-1', array( 1, 2, 3, 4 ) ), $room( 'guest-bedroom-1', array( 1, 2 ) ), $room( 'guest-bedroom-2', array( 1 ) ), $room( 'guest-bedroom-3', array( 1, 2 ) ), $room( 'guest-bedroom-4', array( 1 ) ), $room( 'guest-bedroom-5', array( 1, 2 ) ) ),
	__( 'From above', 'bpt' )        => array( bpt_asset( 'home/aerial-night.jpg' ), bpt_asset( 'home/aerial-day.jpg' ), bpt_asset( 'home/bay.jpg' ), bpt_asset( 'home/entrance.jpg' ), bpt_asset( 'home/lifestyle.jpg' ) ),
);

get_template_part( 'template-parts/pagehero', null, array(
	'image' => bpt_asset( 'home/aerial-day.jpg' ),
	'title' => get_the_title(),
	'crumb' => __( 'Photos', 'bpt' ),
	'size'  => 'short',
	'position' => '62% 60%',
) );
?>
<section class="block">
	<div class="wrap">
		<div data-tabs>
			<div class="tabs" role="tablist">
				<?php $i = 0; foreach ( $cats as $name => $imgs ) : ?>
					<button type="button" role="tab" aria-selected="<?php echo 0 === $i ? 'true' : 'false'; ?>" data-tab="<?php echo (int) $i; ?>"><?php echo esc_html( $name ); ?></button>
				<?php $i++; endforeach; ?>
			</div>
			<?php $i = 0; foreach ( $cats as $name => $imgs ) : ?>
				<div class="panel ggrid" data-panel="<?php echo (int) $i; ?>"<?php echo 0 === $i ? '' : ' hidden'; ?>>
					<?php foreach ( $imgs as $n => $u ) : ?><img src="<?php echo esc_url( $u ); ?>" alt="<?php echo esc_attr( $name . ' ' . ( $n + 1 ) ); ?>" loading="lazy"><?php endforeach; ?>
				</div>
			<?php $i++; endforeach; ?>
		</div>
	</div>
</section>
<?php
get_template_part( 'template-parts/cta' );
get_footer();
