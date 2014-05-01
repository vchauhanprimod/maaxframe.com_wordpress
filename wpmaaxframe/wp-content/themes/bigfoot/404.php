<?php
/**
 * @package WordPress
 * @subpackage BigFoot
 * @since BigFoot 1.0
 */
get_header(); ?>

	<!-- Page header -->
	<header class="pageheader">
		<h1><?php _e('404', 'thb_text_domain'); ?></h1>
		<h2><?php _e('page not found', 'thb_text_domain'); ?></h2>
	</header><!-- /.pageheader -->

	<?php get_template_part('partial-header-closure'); ?>

	<?php thb_page_before(); ?>
		<section id="content">
			<div class="thb-text">
				<p id="disclaimer"><?php _e( 'Apologies, but the page you requested could not be found. Perhaps searching will help.', 'thb_text_domain' ); ?></p>
				<?php get_search_form(); ?>
				<script type="text/javascript">
					// focus on search field after it has loaded
					document.getElementById('s') && document.getElementById('s').focus();
				</script>
			</div>
		</section>
	<?php thb_page_after(); ?>

<?php get_footer(); ?>