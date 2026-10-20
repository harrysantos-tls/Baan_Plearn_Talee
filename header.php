<?php
/**
 * Header: transparent over the home hero, white and sticky everywhere else.
 *
 * @package bpt
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$groups = array(
	__( 'Living spaces', 'bpt' ) => array( 'pool-area', 'living-area', 'kitchen-dining-area', 'media-game-room', 'fitness-room', 'outdoor-sala', 'outdoor-jacuzzi' ),
	__( 'Bedrooms', 'bpt' )      => array( 'master-bedroom-1', 'guest-bedroom-1', 'guest-bedroom-2', 'guest-bedroom-3', 'guest-bedroom-4', 'guest-bedroom-5' ),
	__( 'Practical', 'bpt' )     => array( 'services-features-overview', 'floor-plan', 'location' ),
);
$is_home    = bpt_is_home_like();
$is_booking = is_page_template( 'templates/template-booking.php' );
$current    = is_page() ? get_post_field( 'post_name', get_queried_object_id() ) : '';
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip" href="#main"><?php esc_html_e( 'Skip to content', 'bpt' ); ?></a>

<header class="site-head <?php echo $is_home ? 'is-home' : 'is-solid'; ?>" id="site-head">
	<a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( bpt_villa_name() ); ?>">
		<img class="brand__colour" src="<?php echo esc_url( bpt_logo( 'dark' ) ); ?>" alt="<?php echo esc_attr( bpt_villa_name() ); ?>">
		<img class="brand__white" src="<?php echo esc_url( bpt_logo( 'light' ) ); ?>" alt="" aria-hidden="true">
	</a>

	<nav class="primary" aria-label="<?php esc_attr_e( 'Primary', 'bpt' ); ?>">
		<ul>
			<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"<?php echo $is_home ? ' aria-current="page"' : ''; ?>><?php esc_html_e( 'Home', 'bpt' ); ?></a></li>
			<li class="has-dd">
				<button type="button" class="dd-toggle" aria-expanded="false" aria-haspopup="true"><?php esc_html_e( 'The villa', 'bpt' ); ?>
					<svg width="11" height="11" viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2.5 4.5l3.5 3.5 3.5-3.5"/></svg>
				</button>
				<div class="dd-panel">
					<div class="dd-inner">
						<?php foreach ( $groups as $label => $slugs ) : ?>
							<div class="dd-col">
								<div class="dd-title"><?php echo esc_html( $label ); ?></div>
								<ul>
									<?php foreach ( $slugs as $slug ) : ?>
										<li><a href="<?php echo esc_url( bpt_page_url( $slug ) ); ?>"<?php echo $current === $slug ? ' aria-current="page"' : ''; ?>><?php echo esc_html( bpt_page_title( $slug ) ); ?></a></li>
									<?php endforeach; ?>
								</ul>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</li>
			<li class="has-dd">
				<button type="button" class="dd-toggle" aria-expanded="false" aria-haspopup="true"><?php esc_html_e( 'Gallery', 'bpt' ); ?>
					<svg width="11" height="11" viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2.5 4.5l3.5 3.5 3.5-3.5"/></svg>
				</button>
				<div class="dd-panel">
					<div class="dd-inner">
						<div class="dd-col">
							<ul>
								<li><a href="<?php echo esc_url( bpt_page_url( 'gallery' ) ); ?>"<?php echo $current === 'gallery' ? ' aria-current="page"' : ''; ?>><?php esc_html_e( 'Gallery', 'bpt' ); ?></a></li>
								<li><a href="<?php echo esc_url( bpt_page_url( 'video' ) ); ?>"<?php echo $current === 'video' ? ' aria-current="page"' : ''; ?>><?php esc_html_e( 'Video', 'bpt' ); ?></a></li>
							</ul>
						</div>
					</div>
				</div>
			</li>
			<li><a href="<?php echo esc_url( bpt_page_url( 'reviews' ) ); ?>"><?php esc_html_e( 'Reviews', 'bpt' ); ?></a></li>
			<li><a href="<?php echo esc_url( bpt_page_url( 'contact-us' ) ); ?>"><?php esc_html_e( 'Contact', 'bpt' ); ?></a></li>
		</ul>
	</nav>

	<?php if ( ! $is_booking ) : ?>
		<button type="button" class="head-cta" data-bpt-book><?php esc_html_e( 'Check availability', 'bpt' ); ?></button>
	<?php endif; ?>

	<button type="button" class="nav-toggle" aria-expanded="false" aria-controls="mobile-nav">
		<span class="nav-toggle__bars" aria-hidden="true"></span><span class="screen-reader-text"><?php esc_html_e( 'Menu', 'bpt' ); ?></span>
	</button>
</header>

<div class="mobile-nav" id="mobile-nav" hidden>
	<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'bpt' ); ?></a>
	<?php foreach ( $groups as $label => $slugs ) : ?>
		<div class="mobile-nav__group"><?php echo esc_html( $label ); ?></div>
		<?php foreach ( $slugs as $slug ) : ?>
			<a class="sub" href="<?php echo esc_url( bpt_page_url( $slug ) ); ?>"><?php echo esc_html( bpt_page_title( $slug ) ); ?></a>
		<?php endforeach; ?>
	<?php endforeach; ?>
	<div class="mobile-nav__group"><?php esc_html_e( 'Gallery', 'bpt' ); ?></div>
	<a class="sub" href="<?php echo esc_url( bpt_page_url( 'gallery' ) ); ?>"><?php esc_html_e( 'Gallery', 'bpt' ); ?></a>
	<a class="sub" href="<?php echo esc_url( bpt_page_url( 'video' ) ); ?>"><?php esc_html_e( 'Video', 'bpt' ); ?></a>
	<a href="<?php echo esc_url( bpt_page_url( 'reviews' ) ); ?>"><?php esc_html_e( 'Reviews', 'bpt' ); ?></a>
	<a href="<?php echo esc_url( bpt_page_url( 'contact-us' ) ); ?>"><?php esc_html_e( 'Contact', 'bpt' ); ?></a>
	<?php if ( ! $is_booking ) : ?>
		<button type="button" class="head-cta head-cta--mobile" data-bpt-book><?php esc_html_e( 'Check availability', 'bpt' ); ?></button>
	<?php endif; ?>
</div>

<?php get_template_part( 'template-parts/booking-modal' ); ?>

<main id="main">
