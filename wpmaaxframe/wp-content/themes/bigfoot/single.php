<?php
/**
 * @package WordPress
 * @subpackage BigFoot
 * @since BigFoot 1.0
 */
$thb_page_id = get_the_ID();
$meta = thb_get_post_meta_all($thb_page_id);
extract($meta);
$subtitle = get_the_date();

get_header(); ?>

		<!-- Page header -->
		<?php if( thb_get_post_meta($thb_page_id, 'pageheader_disable') == 0 ) : ?>
		<header class="pageheader">
			<h1><?php the_title(); ?></h1>
			<?php if( thb_get_post_format() == 'link') : ?>
			<a class="linkurl" href="<?php echo $link_url; ?>" title="<?php the_title(); ?>">
				<?php echo $link_url; ?>
			</a>
			<?php endif; ?>
			<?php if( !empty($subtitle) ) : ?>
				<h2><?php echo $subtitle; ?></h2>
			<?php endif; ?>
		</header><!-- /.pageheader -->
		<?php endif; ?>

	<?php get_template_part('partial-header-closure'); ?>

	<?php thb_post_before(); ?>
	<section id="content">

		<?php if( have_posts() ) : while( have_posts() ) : the_post(); ?>
			<?php thb_post_start(); ?>

			<?php if( thb_get_post_format() == 'video') : {
					echo '<div class="thb-single-video-wrapper">';
						thb_post_format_video();
					echo '</div>';
				} endif; ?>

			<?php if( thb_get_post_format() == 'audio') : {
					thb_post_format_audio();
				} endif; ?>

			<?php if( thb_get_post_format() == 'gallery') : {
					thb_post_format_gallery(array(
						'size' => 'large-cropped'
					));
				} endif; ?>

			<?php if( thb_get_post_format() == 'quote') : {
					$quote = thb_get_post_format_quote_text();
					$quote_url = thb_get_post_format_quote_url();
					$quote_author = thb_get_post_format_quote_author();
				?>
				<div class="single-post-quote">
				<?php if( !empty($quote) ) : ?>
					<blockquote>
						<p><?php echo $quote; ?></p>
					</blockquote>
				<?php endif; ?>
				<?php if( !empty($quote_author) || !empty($quote_url) ) : ?>
				<p>
					<cite>
						<?php if( !empty($quote_url) ) : ?>
							<a href="<?php echo $quote_url; ?>">&mdash; <?php echo $quote_author; ?></a>
						<?php else : ?>
							<?php echo '&mdash; ' . $quote_author; ?>
						<?php endif; ?>
					</cite>
				</p>
				<?php endif; ?>
				</div>
			<?php } endif; ?>


			<?php // thb_get_template_part('part-single-'. thb_get_post_format()); ?>

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

			<aside class="meta details">
				<p>
					<?php
						$tags = get_the_tags();
						$category = get_the_category();
					?>

					<?php if( !empty($category) ) : ?>
						<?php _e('Filed under', 'thb_text_domain'); ?> <?php the_category(', '); ?>.
					<?php endif; ?>
					<?php if( !empty($tags) ) : ?>
						<?php _e('Tagged', 'thb_text_domain'); ?> <?php the_tags('', ', '); ?>.
					<?php endif; ?>
				</p>
			</aside>

			<aside class="meta author">
				<?php echo get_avatar( get_the_author_meta( 'ID' ) , 50 ); ?>

				<h1><?php _e('The author', 'thb_text_domain'); ?></h1>
				<h2><?php the_author_posts_link(); ?></h2>

				<?php
					$author_description = get_the_author_meta('user_description');
					if( !empty($author_description) ) :
				?>
					<div class="thb-text">
						<?php echo thb_text_format($author_description, true); ?>
					</div>
				<?php endif; ?>
			</aside>

			<?php if( thb_get_post_meta($thb_page_id, 'disable_navigation_block') != '1' ) : ?>
				<?php thb_pagination( array( 'type' => 'links', 'previousPostTitle' => __( 'Previous', 'thb_text_domain' ), 'nextPostTitle' => __( 'Next', 'thb_text_domain' ) ) ); ?>
			<?php endif; ?>

			<?php if( thb_show_related() ) : ?>
				<section class="related">
					<h3><?php _e('You might also like', 'thb_text_domain'); ?></h3>
					<?php thb_related(); ?>
				</section>
			<?php endif; ?>

			<?php if( thb_show_comments() ) : ?>
				<section class="secondary">
				<?php if( thb_show_comments() ) : ?>
					<?php thb_comments( array('title_reply' => __('Leave a reply', 'thb_text_domain') )); ?>
				<?php endif; ?>
				</section>
			<?php endif; ?>

			<?php thb_post_end(); ?>
		<?php endwhile; endif; ?>
	</section>
	<?php thb_post_after(); ?>

	<?php thb_page_sidebar(); ?>

<?php get_footer(); ?>