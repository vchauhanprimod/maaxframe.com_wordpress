<?php
/**
 * @package WordPress
 * @subpackage BigFoot
 * @since BigFoot 1.0
 * Template name: Archives
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
			<?php thb_page_start(); ?>

			<?php if(have_posts()) : while(have_posts()) : the_post(); ?>
				<?php the_content(); ?>
			<?php endwhile; endif; ?>

			<div class="search_404">
				<?php get_search_form(); ?>
			</div>

			<div class="col content-one-third">
				<h3><?php _e("Last 30 posts:", 'thb_text_domain'); ?></h3>
				<?php
					thb_query_posts('post', array(
					     'posts_per_page' => 30
					));
					if( have_posts() ) : ?>
				<ul>
					<?php while( have_posts() ) : the_post(); ?>
					<li>
						<a href="<?php the_permalink(); ?>" rel="permalink"><?php the_title(); ?></a>
					</li>
					<?php endwhile; ?>
				</ul>
				<?php endif; ?>
			</div>

			<div class="col content-one-third">
				<h3><?php _e("Archives by Month:", 'thb_text_domain'); ?></h3>
				<ul>
					<?php wp_get_archives('type=monthly'); ?>
				</ul>
			</div>

			<div class="col content-one-third last">
				<h3><?php _e("Archives by Subject:", 'thb_text_domain'); ?></h3>
				<ul>
					 <?php wp_list_categories(array("title_li" => "")); ?>
				</ul>
			</div>

			<?php thb_page_end(); ?>
		</section>
	<?php thb_page_after(); ?>

		<?php thb_page_sidebar(); ?>

<?php get_footer(); ?>