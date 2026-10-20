<?php
/**
 * The home page sections. Used by the home page and by the Guesty booking test page.
 *
 * @package bpt
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$icon = function ( $paths, $size = 28 ) {
	return '<svg width="' . (int) $size . '" height="' . (int) $size . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $paths . '</svg>';
};
$ico = array(
	'chef'    => '<path d="M7 14.5V19h10v-4.5"/><path d="M7 14.5c-2 0-3.5-1.6-3.5-3.6S5 7.5 7 7.5c.4-1.8 2.3-3 5-3s4.6 1.2 5 3c2 0 3.5 1.6 3.5 3.4S19 14.5 17 14.5"/><path d="M7 17h10"/>',
	'cup'     => '<path d="M5 9h11v5a4 4 0 0 1-4 4H9a4 4 0 0 1-4-4V9z"/><path d="M16 10h1.5a2.5 2.5 0 0 1 0 5H16"/><path d="M9 3.5V6M12.5 3.5V6"/>',
	'bed'     => '<path d="M3 6v12"/><path d="M3 15h18v3"/><path d="M21 15v-3a3 3 0 0 0-3-3h-7v6"/><circle cx="7" cy="11.5" r="1.5"/>',
	'bell'    => '<path d="M5 17a7 7 0 0 1 14 0"/><path d="M3 17h18"/><path d="M3 20h18"/><path d="M12 10V7.5M10.5 7.5h3"/>',
	'send'    => '<path d="M22 2 11 13"/><path d="M22 2l-7 20-4-9-9-4 20-7z"/>',
	'shield'  => '<path d="M12 3l7.5 3v5.5c0 4.4-3.1 8-7.5 9.5-4.4-1.5-7.5-5.1-7.5-9.5V6L12 3z"/><path d="M8.8 12l2.2 2.2 4.2-4.4"/>',
	'lotus'   => '<path d="M12 5c2 2.3 2 6.3 0 9-2-2.7-2-6.7 0-9z"/><path d="M12 20c-3.6 0-6.5-2.4-7-6 3.2-.2 5.4 1 7 3"/><path d="M12 20c3.6 0 6.5-2.4 7-6-3.2-.2-5.4 1-7 3"/>',
	'shirt'   => '<path d="M8.5 3L3 6.5l2.5 4L8 9v12h8V9l2.5 1.5 2.5-4L15.5 3C15 4.5 13.7 5.5 12 5.5S9 4.5 8.5 3z"/>',
	'plane'   => '<path d="M3 13.5l8-2.5V5.5a1.5 1.5 0 0 1 3 0V11l8 2.5v2l-8-1.5v3.5l2.5 1.5V21l-4-1-4 1v-1.5L11 18v-3.5l-8 1.5v-2.5z"/>',
	'waves'   => '<path d="M3 8c2 0 2-2 4.5-2S10 8 12 8s2.5-2 4.5-2S19 8 21 8"/><path d="M3 13c2 0 2-2 4.5-2S10 13 12 13s2.5-2 4.5-2S19 13 21 13"/><path d="M3 18c2 0 2-2 4.5-2S10 18 12 18s2.5-2 4.5-2S19 18 21 18"/>',
	'glass'   => '<path d="M5 4h14l-7 8z"/><path d="M12 12v8"/><path d="M8 20h8"/>',
	'anchor'  => '<circle cx="12" cy="5" r="2"/><path d="M12 7v14"/><path d="M6 12H4c0 4.5 3.5 8 8 8s8-3.5 8-8h-2"/><path d="M9 11h6"/>',
	'flag'    => '<path d="M8 21V3"/><path d="M8 4l9 3.5L8 11"/><path d="M4 21h10"/>',
	'bag'     => '<path d="M5 8h14l-1 12H6L5 8z"/><path d="M9 8V6.5a3 3 0 0 1 6 0V8"/>',
	'quote'   => '<path d="M10 7.5C7 8 5 10 5 13v3.5h4.5V12H7.2c.2-1.7 1.2-2.7 2.8-3z"/><path d="M19 7.5c-3 .5-5 2.5-5 5.5v3.5h4.5V12h-2.3c.2-1.7 1.2-2.7 2.8-3z"/>',
);

$spaces = array(
	array( 'img' => 'home/space-living.jpg', 'slug' => 'living-area', 'h' => __( 'Modern spaces for relaxing together', 'bpt' ), 'link' => __( 'See the living area', 'bpt' ),
		'p1' => __( 'Spacious open plan interiors filled with natural light and a relaxed tropical feel. The living and dining areas offer comfortable spaces for shared meals, conversations, and time together, with large windows connecting the indoors to the terraces beyond.', 'bpt' ),
		'p2' => __( 'Guests can also enjoy a billiards table, media and game room, and fitness room, giving everyone plenty of ways to relax during their stay.', 'bpt' ), 'pos' => '58% 55%' ),
	array( 'img' => 'home/space-pool.jpg', 'slug' => 'pool-area', 'h' => __( 'Outdoor living with ocean views', 'bpt' ), 'link' => __( 'See the pool area', 'bpt' ),
		'p1' => __( 'A nineteen metre infinity pool takes centre stage, with ocean views creating a peaceful backdrop for swimming and relaxing. The sun deck includes a bar and BBQ area for casual gatherings and meals.', 'bpt' ),
		'p2' => __( 'An outdoor jacuzzi offers another place to unwind, while the sala and patio provide comfortable spaces for open air dining, quiet mornings, and relaxing evenings.', 'bpt' ), 'pos' => '55% 55%' ),
	array( 'img' => 'home/space-media.jpg', 'slug' => 'media-game-room', 'h' => __( 'Something for every age', 'bpt' ), 'link' => __( 'See the media and game room', 'bpt' ),
		'p1' => __( 'Spend time together over a game of billiards or foosball, watch a movie on the large screen TVs, or enjoy an evening of karaoke and gaming. The fitness room gives anyone who wants to stay active the space to do it.', 'bpt' ),
		'p2' => __( 'Sonos sound systems run throughout the villa, and high speed Wi-Fi keeps guests connected whenever they need to be.', 'bpt' ), 'pos' => '52% 55%' ),
);

$beds = array(
	array( 'master-bedroom-1', 'bed-master.jpg', 'King bed, ocean view', '62% 60%' ),
	array( 'guest-bedroom-1', 'bed-1.jpg', 'King bed, ocean and pool view', '72% 60%' ),
	array( 'guest-bedroom-2', 'bed-2.jpg', 'King bed, ocean view', '42% 62%' ),
	array( 'guest-bedroom-3', 'bed-3.jpg', 'King bed, direct pool access', '52% 62%' ),
	array( 'guest-bedroom-4', 'bed-4.jpg', 'King bed, connects to Bedroom 5', '52% 62%' ),
	array( 'guest-bedroom-5', 'bed-5.jpg', 'Two single beds, connects to Bedroom 4', '68% 62%' ),
);

$services = array(
	array( 'chef', 'Four staff members including a private chef' ),
	array( 'cup', 'Continental or Asian breakfast, 7am to 11am' ),
	array( 'bed', 'Daily housekeeping and turndown' ),
	array( 'bell', 'Guest Experience service' ),
	array( 'send', 'Roundtrip airport transfer included' ),
	array( 'shield', '24 hour security' ),
	array( 'lotus', 'In-house massage on request' ),
	array( 'shirt', 'Laundry service on request' ),
);

$reviews = array(
	array( 'The villa is huge, the rooms are comfortable, and the ocean view from the pool is amazing.', 'James T.' ),
	array( 'Amazing stay! The villa has everything we needed for a relaxing week with friends, from the big pool and jacuzzi to the entertainment rooms. We especially liked the sunset views from the outdoor areas.', 'Anthony P.' ),
	array( 'We loved the jacuzzi and the outdoor sala, especially in the evening. The kids were always in the game room while the adults enjoyed the pool. The private chef was excellent, and we had some of our best meals in Phuket here.', 'Caroline M.' ),
	array( 'We came with friends and there was enough space that nobody felt crowded, even when everyone was in the villa. Billiards, foosball and karaoke kept us entertained at night.', 'Robert K.' ),
	array( 'Our family stay was wonderful. The villa is spacious and the staff take very good care of us. We spend most of the day swimming and eating outside. The chef make very delicious Thai food, everyone was happy!', 'Maria G.' ),
	array( 'Very peaceful place and the view is beautiful. Staff helped us with everything and the food was good. We had a very nice holiday here.', 'Sophie L.' ),
	array( 'The service was exceptional from arrival to departure. The staff anticipated every need and the private chef prepared incredible meals each evening. This is luxury done right.', 'Catherine W.' ),
	array( 'Absolutely breathtaking property. The infinity pool appears to merge with the horizon and waking up to those ocean views every morning was unforgettable. We will be back.', 'Michael D.' ),
	array( 'Perfect villa for a large group. Plenty of space for everyone to relax, the chef was outstanding, and the concierge arranged everything we asked for. Highly recommend.', 'David & Sarah L.' ),
);


$places = array(
	array( 'plane', 'Phuket International Airport', '31.4 km' ),
	array( 'waves', 'Yae Beach', '2 km' ),
	array( 'glass', 'Kamala restaurants and bars', '2.2 km' ),
	array( 'anchor', 'Royal Phuket Marina', '24.8 km' ),
	array( 'flag', 'Laguna Golf', '15.6 km' ),
	array( 'bag', 'Jungceylon shopping centre', '7.8 km' ),
);
?>

<section class="hero">
	<?php $hero_video = bpt_hero_video_url(); $hero_poster = get_theme_file_uri( 'assets/video/hero-poster.jpg' ); $hero_poster_webp = get_theme_file_uri( 'assets/video/hero-poster.webp' ); ?>
	<?php if ( $hero_video ) : ?>
		<video class="hero__video" muted loop playsinline preload="none" poster="<?php echo esc_url( $hero_poster ); ?>" data-src="<?php echo esc_url( $hero_video ); ?>" data-type="<?php echo esc_attr( bpt_video_type( $hero_video ) ); ?>" width="<?php echo (int) bpt_img_meta()['__poster']['w']; ?>" height="<?php echo (int) bpt_img_meta()['__poster']['h']; ?>" aria-label="<?php esc_attr_e( 'A film of Baan Plearn Talee: the terrace, the pool, the living spaces and the villa at night', 'bpt' ); ?>"></video>
	<?php else : ?>
		<img class="hero__video" src="<?php echo esc_url( $hero_poster ); ?>" alt="" fetchpriority="high" width="<?php echo (int) bpt_img_meta()['__poster']['w']; ?>" height="<?php echo (int) bpt_img_meta()['__poster']['h']; ?>">
	<?php endif; ?>
	<div class="hero__wash"></div>
	<div class="wrap hero__in">
		<div class="hero__lock">
			<img class="hero__swoosh" src="<?php echo esc_url( bpt_asset( 'hero-swoosh.png' ) ); ?>" alt="" width="720" height="133">
			<h1 class="hero__title" aria-label="<?php echo esc_attr( bpt_villa_name() ); ?>"><?php echo bpt_split_title( bpt_villa_name() ); // phpcs:ignore WordPress.Security.EscapeOutput ?></h1>
			<?php if ( bpt_detail( 'hero_tag' ) ) : ?>
				<div class="hero__estate"><span><?php echo esc_html( bpt_detail( 'hero_tag' ) ); ?></span></div>
			<?php endif; ?>
		</div>
		<div class="hero__specs"><span><?php esc_html_e( 'Six bedrooms', 'bpt' ); ?></span><span><?php esc_html_e( 'Twelve guests', 'bpt' ); ?></span><span><?php esc_html_e( 'Kamala, Phuket', 'bpt' ); ?></span></div>
	</div>
	<a class="scroll-cue" href="#booking" aria-label="<?php esc_attr_e( 'Scroll down', 'bpt' ); ?>">
		<span class="scroll-cue__in">
			<span class="scroll-cue__mouse" aria-hidden="true"></span>
			<span class="scroll-cue__label" aria-hidden="true"><?php esc_html_e( 'Scroll', 'bpt' ); ?></span>
		</span>
	</a>
</section>

<?php if ( function_exists( 'bpt_is_engine_page' ) && bpt_is_engine_page() ) {
	get_template_part( 'template-parts/booking-engine' );
} else {
	get_template_part( 'template-parts/booking-bar' );
} ?>

<section class="intro2">
	<div class="wrap">
		<div class="intro2__top">
			<div class="intro2__lead">
				<svg class="intro2__mark" width="96" height="12" viewBox="0 0 120 14" fill="none" aria-hidden="true"><path d="M1 8 C 20 1, 40 13, 60 7 S 100 1, 119 7" stroke="currentColor" stroke-width="1.4"/></svg>
				<h2><?php esc_html_e( 'A house by the sea', 'bpt' ); ?></h2>
				<p><?php esc_html_e( 'Plearn Talee is a refined six-bedroom private villa within the prestigious Jomchang Estate in Phuket, accommodating up to twelve guests.', 'bpt' ); ?></p>
			</div>
			<div class="intro2__tabs" data-tabs>
				<div class="tabs" role="tablist">
					<button type="button" role="tab" aria-selected="true" data-tab="0"><?php esc_html_e( 'The villa', 'bpt' ); ?></button>
					<button type="button" role="tab" aria-selected="false" data-tab="1"><?php esc_html_e( 'The setting', 'bpt' ); ?></button>
					<button type="button" role="tab" aria-selected="false" data-tab="2"><?php esc_html_e( 'The collection', 'bpt' ); ?></button>
				</div>
				<div class="intro2__panel panel" data-panel="0"><?php echo nl2br( esc_html( 'Baan Plearn Talee is a refined six-bedroom private villa within Jomchang Estate in Kamala, designed for families and groups of up to twelve guests.
Spread across three levels, the villa balances generous shared spaces with private areas to retreat, all framed by ocean views and easy indoor-outdoor living.' ) ); ?></div>
				<div class="intro2__panel panel" data-panel="1" hidden><?php echo nl2br( esc_html( 'Set on Phuket\'s west coast, Plearn Talee looks out across the Andaman Sea from a peaceful hillside position in Kamala.
The villa opens toward terraces, gardens, and a 20-meter infinity pool, with outdoor spaces designed around the view.
From quiet mornings by the water to sunset gatherings at the sala, the setting makes it easy to settle into the slower rhythm of the island.' ) ); ?></div>
				<div class="intro2__panel panel" data-panel="2" hidden><?php echo nl2br( 'Baan Plearn Talee is part of <a href="https://www.theluxurysignature.com" target="_blank" rel="noopener" class="link-light">The Luxury Signature</a>\'s collection of private villas in Phuket, selected for their location, character, and ability to offer a complete stay.
With dedicated staff, a private chef, generous living spaces, and a setting along Phuket\'s Millionaire\'s Mile, the villa reflects the kind of privacy, service, and sense of place that define the collection.' ); ?></div>
			</div>
		</div>
		<dl class="facts2">
			<div><dt><?php esc_html_e( 'Bedrooms', 'bpt' ); ?></dt><dd><?php esc_html_e( 'Six', 'bpt' ); ?></dd></div>
			<div><dt><?php esc_html_e( 'Guests', 'bpt' ); ?></dt><dd><?php esc_html_e( 'Up to twelve', 'bpt' ); ?></dd></div>
			<div><dt><?php esc_html_e( 'Setting', 'bpt' ); ?></dt><dd><?php esc_html_e( 'Jomchang Estate, Phuket', 'bpt' ); ?></dd></div>
			<div><dt><?php esc_html_e( 'Outdoors', 'bpt' ); ?></dt><dd><?php esc_html_e( 'Infinity pool, ocean views', 'bpt' ); ?></dd></div>
		</dl>
	</div>
</section>

<section class="spaces2" id="spaces">
	<div class="wrap spaces2__grid">
		<?php $main = $spaces[0]; ?>
		<article class="mag-main">
			<img src="<?php echo esc_url( bpt_asset( $main['img'] ) ); ?>" alt="" loading="lazy" style="object-position:<?php echo esc_attr( $main['pos'] ); ?>">
			<h3><?php echo esc_html( $main['h'] ); ?></h3>
			<p><?php echo esc_html( $main['p1'] ); ?></p>
			<p><?php echo esc_html( $main['p2'] ); ?></p>
			<a class="tl" href="<?php echo esc_url( bpt_page_url( $main['slug'] ) ); ?>"><?php echo esc_html( $main['link'] ); ?></a>
		</article>
		<div class="mag-side">
			<?php foreach ( array_slice( $spaces, 1 ) as $s ) : ?>
				<article>
					<img src="<?php echo esc_url( bpt_asset( $s['img'] ) ); ?>" alt="" loading="lazy" style="object-position:<?php echo esc_attr( $s['pos'] ); ?>">
					<h3><?php echo esc_html( $s['h'] ); ?></h3>
					<p><?php echo esc_html( $s['p1'] ); ?></p>
					<a class="tl" href="<?php echo esc_url( bpt_page_url( $s['slug'] ) ); ?>"><?php echo esc_html( $s['link'] ); ?></a>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section class="beds">
	<div class="wrap">
		<div class="beds__head">
			<h2><?php esc_html_e( 'Comfortable bedrooms for a restful stay', 'bpt' ); ?></h2>
			<p><?php esc_html_e( 'Every bedroom has an ensuite bathroom. Several open directly onto the pool deck, and two connect for families travelling together.', 'bpt' ); ?></p>
		</div>
		<div class="bento">
			<?php foreach ( $beds as $i => $b ) : ?>
				<a class="bento__item<?php echo 0 === $i ? ' bento__item--lead' : ''; ?>" href="<?php echo esc_url( bpt_page_url( $b[0] ) ); ?>">
					<img src="<?php echo esc_url( bpt_asset( 'home/' . $b[1] ) ); ?>" alt="" loading="lazy" style="object-position:<?php echo esc_attr( $b[3] ); ?>">
					<div class="bento__cap"><div class="bento__name"><?php echo esc_html( bpt_page_title( $b[0] ) ); ?></div><div class="bento__type"><?php echo esc_html( $b[2] ); ?></div></div>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section class="services">
	<div class="wrap">
		<div class="services__top">
			<h2><?php esc_html_e( 'Looked after, not hovered over', 'bpt' ); ?></h2>
			<div class="services__copy">
				<p><?php esc_html_e( 'A team of four professional staff, including a private chef, is on hand throughout your stay. Daily housekeeping and turndown service keep the villa fresh, while the Guest Experience service can arrange the day around you and handle special requests.', 'bpt' ); ?></p>
				<p><?php esc_html_e( 'Roundtrip airport transfers are included. Car and van rental with a driver, in-house massage, and laundry can be arranged at an additional charge, and a selection of champagne, red, white, and rosé wines is available on request.', 'bpt' ); ?></p>
			</div>
		</div>
		<div class="tiles">
			<?php foreach ( $services as $sv ) : ?>
				<div class="tile"><?php echo $icon( $ico[ $sv[0] ], 32 ); // phpcs:ignore WordPress.Security.EscapeOutput ?><div><?php echo esc_html( $sv[1] ); ?></div></div>
			<?php endforeach; ?>
		</div>
		<a class="btn-line-dark" href="<?php echo esc_url( bpt_page_url( 'services-features-overview' ) ); ?>"><?php esc_html_e( 'Full list of services and features', 'bpt' ); ?></a>
	</div>
</section>

<section class="reviews2">
	<div class="wrap reviews2__head">
		<div>
			<div class="kicker"><?php esc_html_e( 'Guest reviews', 'bpt' ); ?></div>
			<h2><?php esc_html_e( 'What our guests say', 'bpt' ); ?></h2>
		</div>
		<a class="tl" href="<?php echo esc_url( bpt_page_url( 'reviews' ) ); ?>"><?php esc_html_e( 'Read all guest reviews', 'bpt' ); ?></a>
	</div>
	<div class="marq" role="region" aria-label="<?php esc_attr_e( 'Guest reviews', 'bpt' ); ?>">
		<div class="marq__track">
			<?php foreach ( array( false, true ) as $copy ) : // the strip is shown twice so it can loop without a gap; the second copy is hidden from screen readers ?>
				<?php foreach ( $reviews as $r ) : ?>
					<figure class="mcard"<?php echo $copy ? ' aria-hidden="true"' : ''; ?>>
						<div>
							<div class="mcard__q"><?php echo $icon( $ico['quote'], 34 ); // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
							<blockquote><?php echo esc_html( $r[0] ); ?></blockquote>
						</div>
						<figcaption><?php echo esc_html( $r[1] ); ?></figcaption>
					</figure>
				<?php endforeach; ?>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section class="location">
	<div class="wrap">
		<div class="sec-head sec-head--centre">
			<h2><?php esc_html_e( 'Between Kamala and Patong', 'bpt' ); ?></h2>
			<p><?php esc_html_e( "Plearn Talee sits high on Phuket's west coast, between the calm of Kamala and the energy of Patong. Tropical greenery surrounds the villa and the Andaman Sea fills the view, yet the island's beaches, restaurants and marinas stay within easy reach.", 'bpt' ); ?></p>
		</div>
		<div class="dcards">
			<?php foreach ( $places as $pl ) : ?>
				<div class="dcard"><?php echo $icon( $ico[ $pl[0] ], 34 ); // phpcs:ignore WordPress.Security.EscapeOutput ?><div class="dcard__km"><?php echo esc_html( $pl[2] ); ?></div><div><?php echo esc_html( $pl[1] ); ?></div></div>
			<?php endforeach; ?>
		</div>
		<div class="centre"><a class="tl" href="<?php echo esc_url( bpt_page_url( 'location' ) ); ?>"><?php esc_html_e( 'All distances and directions', 'bpt' ); ?></a></div>
	</div>
</section>

<?php get_template_part( 'template-parts/cta' ); ?>

