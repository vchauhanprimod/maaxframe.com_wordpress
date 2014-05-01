<?php
/**
 * @package WordPress
 * @subpackage BigFoot
 * @since BigFoot 1.0
 */

if( is_category() ) {
	$pagetitle = single_cat_title( '', false );
	$pagesubtitle = __("Category", 'thb_text_domain');
}
elseif( is_tag() ) {
	$pagetitle = single_cat_title( '', false );
	$pagesubtitle = __("Tag", 'thb_text_domain');
}
elseif( is_tax() ) {
	$pagetitle = single_cat_title( '', false );
	$pagesubtitle = __("Taxonomy", 'thb_text_domain');
}
elseif( is_search() ) {
	$pagetitle = sprintf( __( 'Search Results for: &ldquo;%s&rdquo;', 'thb_text_domain' ), '<span>' . get_search_query() . '</span>' );
}
elseif( is_author() ) {
	if(have_posts()) {
		the_post();
		$pagetitle = get_the_author();
		$pagesubtitle = __("Author", 'thb_text_domain');
		rewind_posts();
	}
}
elseif( is_404() ) {
	$pagetitle = __("Error 404", 'thb_text_domain');
	$pagesubtitle = __( 'Not Found', 'thb_text_domain' );
}
elseif ( is_day() ) {
	$pagetitle = get_the_date();
	$pagesubtitle = __("Archives", 'thb_text_domain');
}
elseif ( is_month() ) {
	$pagetitle = get_the_date( 'F Y' );
	$pagesubtitle = __("Archives", 'thb_text_domain');
}
elseif ( is_year() ) {
	$pagetitle = get_the_date( 'Y' );
	$pagesubtitle = __("Archives", 'thb_text_domain');
}
get_header(); ?>

		<!-- Page header -->
		<header class="pageheader">
			<h1><?php echo $pagetitle; ?></h1>
			<h2><?php echo $pagesubtitle; ?></h2>
		</header><!-- /.pageheader -->

	<?php get_template_part('partial-header-closure'); ?>

	<?php thb_page_before(); ?>
		<section id="content">
		<?php thb_page_start(); ?>

			<?php get_template_part("loop/archive"); ?>

		<?php thb_page_end(); ?>
		</section>
	<?php thb_page_after(); ?>

		<?php thb_page_sidebar(); ?>

<?php get_footer(); ?>