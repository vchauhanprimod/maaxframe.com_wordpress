<?php
/**
 * @package WordPress
 * @subpackage BigFoot
 * @since BigFoot 1.0
 */
$thb_page_id = get_the_ID();

get_header(); ?>

		<!-- Page header -->
		<?php if( thb_get_post_meta($thb_page_id, 'pageheader_disable') == 0 ) : ?>
		<header class="pageheader">
			<h1><?php the_title(); ?></h1>
		</header><!-- /.pageheader -->
		<?php endif; ?>

	<?php get_template_part('partial-header-closure'); ?>

	<?php thb_page_before(); ?>
		<section id="content">
			<?php thb_page_start(); ?>

			<?php 
				get_template_part('loop/attachments');
			?>

			<?php thb_page_end(); ?>
		</section>
	<?php thb_page_after(); ?>

<?php get_footer(); ?>