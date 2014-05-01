<?php
/**
 * @package WordPress
 * @subpackage BigFoot
 * @since BigFoot 1.0
 */
get_header(); ?>

	<?php if(have_posts()) : the_post();
		$pagetitle = __( 'Search Results for :', 'thb_text_domain' );
		$pagesubtitle = get_search_query();
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
				if( have_posts()) : ?>
				<?php
					get_template_part("loop/search");
				else : ?>

				<div class="thb-text">
					<p class="sorry"><?php _e( 'Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'thb_text_domain' ); ?></p>
					<div class="search_404">
						<?php get_search_form(); ?>
					</div>
				</div>
			<?php endif; ?>
		<?php thb_page_end(); ?>
		</section>
	<?php thb_page_after(); ?>

		<?php thb_archives_sidebar(); ?>

<?php get_footer(); ?>