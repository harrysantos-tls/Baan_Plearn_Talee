<?php
/**
 * Template Name: Location (distance cards)
 *
 * @package bpt
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
$d    = bpt_data();
$d    = isset( $d['location'] ) ? $d['location'] : array( 'paras' => array(), 'rows' => array() );
$tel  = preg_replace( '/[^0-9+]/', '', bpt_detail( 'phone' ) );

get_template_part( 'template-parts/pagehero', null, array(
	'image' => bpt_asset( 'home/bay.jpg' ),
	'title' => get_the_title(),
	'crumb' => __( 'The villa', 'bpt' ),
	'size'  => 'medium',
	'position' => '45% 52%',
) );
?>
<section class="block">
	<div class="wrap">
		<div class="loc-intro">
			<div class="loc-address">
				<div class="serif loc-address__t"><?php esc_html_e( 'Getting here', 'bpt' ); ?></div>
				<?php echo esc_html( bpt_detail( 'address' ) ); ?><br>
				<a href="mailto:<?php echo esc_attr( bpt_detail( 'email' ) ); ?>"><?php echo esc_html( bpt_detail( 'email' ) ); ?></a><br>
				<a href="tel:<?php echo esc_attr( $tel ); ?>"><?php echo esc_html( bpt_detail( 'phone' ) ); ?></a>
			</div>
			<div class="loc-paras"><?php foreach ( $d['paras'] as $p ) : ?><p><?php echo esc_html( $p ); ?></p><?php endforeach; ?></div>
		</div>
		<div class="loc-map">
			<iframe
				src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1117.0!2d98.26119!3d7.9254793!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x30503b00785c7c35%3A0x46c8561c03991ac9!2sBaan%20Plearn%20Talee!5e0!3m2!1sen!2sth!4v1"
				width="100%"
				height="480"
				style="border:0"
				allowfullscreen=""
				loading="lazy"
				referrerpolicy="no-referrer-when-downgrade"
				title="<?php esc_attr_e( 'Baan Plearn Talee location map', 'bpt' ); ?>"
			></iframe>
		</div>
		<h2 class="h-sec"><?php esc_html_e( 'Distances', 'bpt' ); ?></h2>
		<div class="dcards dcards--page">
			<?php foreach ( $d['rows'] as $r ) : ?>
				<div class="dcard"><div class="dcard__km"><?php echo esc_html( $r['km'] ); ?></div><div><?php echo esc_html( $r['place'] ); ?></div><div class="dcard__note"><?php echo esc_html( $r['note'] ); ?></div></div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
<?php
get_template_part( 'template-parts/cta' );
get_footer();
