<?php
/**
 * @package WordPress
 * @subpackage BigFoot
 * @since BigFoot 1.0
 */

$thb_page_id = get_the_ID();

$work_categories = wp_get_object_terms($thb_page_id, 'portfolio_categories');
$cats = array();
foreach( $work_categories as $cat ) {
	$cats[] = $cat->name;
}

$prj_info = thb_duplicable_get('prj_info', $thb_page_id);
$has_prj_info = !empty($prj_info);

get_header(); ?>

		<!-- Page header -->
		<?php if( thb_get_post_meta($thb_page_id, 'pageheader_disable') == 0 ) : ?>
		<header class="pageheader">
			<h1><?php the_title(); ?></h1>
			<?php if( ! empty($cats) ) : ?>
				<h2><?php echo implode(', ', $cats); ?></h2>
			<?php endif; ?>
		</header><!-- /.pageheader -->
		<?php endif; ?>

		<?php if( have_posts() ) : while( have_posts() ) : the_post(); ?>
			<?php
				$slideshow = new THB_Collection($thb_page_id, 'work_slide');
				$slideshow->setSlidesImagesClickable();
				$slideshow->setSize('large');
			?>

			<div class="single-work-slideshow-container">
			<?php if( count($slideshow->getSlides()) > 0 ) : ?>
				<?php
					if( thb_get_featured_image($thb_page_id) != '' ) {
						$slideshow->addCoverSlide( get_post_thumbnail_id($thb_page_id) );
					}

					thb_get_template_part( 'config/modules/core/slideshows/submodules/flexslider/templates/slideshow', array(
						'slideshow' => $slideshow,
						// 'meta'      => thb_get_post_meta_all($thb_page_id),
						'meta' => array(
							'flexslider_smoothHeight' => '1',
							'slideshowHeight'         => '0',
							'flexslider_effects'      => 'fade',
							'transition_speed'        => 0.25,
							'delay'                   => 4
						),
						'id' => 'thb-single-work-slideshow'
					) );
				?>
			<?php else : ?>
				<?php
					thb_post_format_image_markup(array(
						'full' => thb_get_post_thumbnail_src($thb_page_id, 'full'),
						'scaled' => thb_get_post_thumbnail_src($thb_page_id, 'large-cropped')
					));
				?>
			<?php endif; ?>
			</div>
		<?php endwhile; endif; ?>

	<?php get_template_part('partial-header-closure'); ?>

	<?php thb_post_before(); ?>
	<section id="content">
		<?php if( have_posts() ) : while( have_posts() ) : the_post(); ?>
			<?php thb_post_start(); ?>
				<?php if( get_the_content() != '' ) : ?>
					<div class="thb-text">
						<?php the_content(); ?>
						<?php
							wp_link_pages(array(
								'pagelink' => '<span>%</span>',
								'before'   => '<div id="page-links"><p><span class="pages">'. __('Pages', 'thb_text_domain').'</span>',
								'after'    => '</p></div>'
							));
						?>
					</div>
				<?php endif; ?>

				<?php if( thb_show_comments() ) : ?>
					<section class="secondary">
						<?php if( thb_show_comments() ) : ?>
							<?php thb_comments( array('title_reply' => '<span>' . __('Leave a reply', 'thb_text_domain') . '</span>' )); ?>
						<?php endif; ?>
					</section>
				<?php endif; ?>

			<?php thb_post_end(); ?>
		<?php endwhile; endif; ?>
	</section>
	<?php thb_post_after(); ?>

	<?php thb_page_sidebar(); ?>

<?php get_footer(); ?>