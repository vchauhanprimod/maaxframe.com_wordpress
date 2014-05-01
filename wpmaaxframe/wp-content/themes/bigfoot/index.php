<?php get_header(); ?>

<?php get_template_part('partial-header-closure'); ?>

	<?php thb_page_before(); ?>
		<section id="content">
			<?php thb_page_start(); ?>

			<?php get_template_part("loop/blog", "classic"); ?>

			<?php thb_page_end(); ?>
		</section>
	<?php thb_page_after(); ?>

	<?php thb_display_sidebar('post-sidebar', 'main'); ?>

<?php get_footer(); ?>