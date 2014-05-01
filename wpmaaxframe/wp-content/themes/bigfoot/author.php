<?php
/**
 * @package WordPress
 * @subpackage BigFoot
 * @since BigFoot 1.0
 */
get_header(); ?>

	<?php if(have_posts()) : the_post();
		$pagetitle = get_the_author();
		$pagesubtitle = __("Author", 'thb_text_domain');
	?>
		<!-- Page header -->
		<header class="pageheader">
			<h1><?php echo $pagetitle; ?></h1>
			<h2><?php echo $pagesubtitle; ?></h2>
		</header><!-- /.pageheader -->
	<?php endif; ?>

	<?php get_template_part('partial-header-closure'); ?>

	<?php thb_page_before(); ?>
		<section id="content">
		<?php thb_page_start(); ?>
			<?php
				rewind_posts();
				get_template_part("loop/archive");
			?>
		<?php thb_page_end(); ?>
		</section>
	<?php thb_page_after(); ?>

		<?php thb_archives_sidebar(); ?>

<?php get_footer(); ?>