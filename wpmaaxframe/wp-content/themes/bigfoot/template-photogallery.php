<?php
/**
 * @package WordPress
 * @subpackage BigFoot
 * @since BigFoot 1.0
 * Template name: Photogallery
 */

$thb_page_id = get_the_ID();
$subtitle = thb_get_post_meta( $thb_page_id, 'subtitle' );
get_header(); ?>

		<!-- Page header -->
		<?php if( thb_get_post_meta($thb_page_id, 'pageheader_disable') == 0 ) : ?>
		<header class="pageheader">
			<h1><?php the_title(); ?></h1>
			<?php if( !empty($subtitle) ) : ?>
				<h2><?php echo $subtitle; ?></h2>
			<?php endif; ?>
		</header><!-- /.pageheader -->
		<?php endif; ?>

	<?php get_template_part('partial-header-closure'); ?>

	<?php thb_page_before(); ?>
		<section id="content">

			<?php thb_photogallery(); ?>

		</section>
	<?php thb_page_after(); ?>

<?php get_footer(); ?>