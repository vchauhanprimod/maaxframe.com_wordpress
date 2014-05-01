<?php
/**
 * @package WordPress
 * @subpackage BigFoot
 * @since BigFoot 1.0
 * Template name: Showcase
 */
$home_slides = thb_get_home_slides();

get_header('home'); ?>

<style type="text/css">
	html { height: 100%; height: calc(100% - 28px); }

	<?php foreach( $home_slides as $slide ) : ?>
		.page-template-template-showcase-php #thb-home-slides .thb-home-slide[data-i="<?php echo $slide->index; ?>"] .thb-home-slide-overlay {
			background-color: <?php echo $slide->bg_color; ?>;

			opacity: <?php echo $slide->bg_opacity; ?>;
			-khtml-opacity: <?php echo $slide->bg_opacity; ?>;
			-moz-opacity: <?php echo $slide->bg_opacity; ?>;
			filter: alpha(opacity=<?php echo $slide->bg_opacity*100; ?>);
			-ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=<?php echo $slide->bg_opacity*100; ?>)";
		}
	<?php endforeach; ?>
</style>

<div id="thb-home-slides" class="home-page-content-wrapper">
	<?php foreach( $home_slides as $slide ) : ?>
		<?php
			$slide_class = $slide->class;
			$slide_class .= $slide->index == 0 ? ' active' : '';
		?>
		<div data-i="<?php echo $slide->index; ?>" class="thb-home-slide <?php echo $slide_class; ?>">
			<?php if( $slide->bg_picture != '' ) : ?>
				<img src="" data-src="<?php echo $slide->bg_picture; ?>" alt="">
			<?php endif; ?>

			<div class="thb-home-slide-overlay"></div>

			<div class="thb-home-slide-caption">
				<div class="wrapper">
					<div class="thb-banner">
						<?php if( !empty($slide->big_text) ) : ?>
							<h1><?php echo nl2br(thb_text_format($slide->big_text)); ?></h1>
						<?php endif; ?>

						<?php if( !empty($slide->small_text) ) : ?>
							<div class="thb-paragraph">
								<?php echo nl2br(thb_text_format($slide->small_text)); ?>
							</div>
						<?php endif; ?>

						<?php if( !empty($slide->btn_text) && !empty($slide->btn_url) ) : ?>
							<a href="<?php echo $slide->btn_url; ?>" class="thb-button"><?php echo $slide->btn_text; ?></a>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	<?php endforeach; ?>

	<?php if( count($home_slides) > 1 ) : ?>
		<div class="thb-home-slides-controls">
			<a href="#" class="thb-home-slides-prev" data-icon="s"></a>
			<a href="#" class="thb-home-slides-next" data-icon="r"></a>
		</div>

		<div class="thb-home-slides-pager">
			<?php for($i=0; $i<count($home_slides); $i++) : ?>
				<a href="#" data-target="<?php echo $i; ?>" class="<?php echo $i==0 ? 'active' : ''; ?>"></a>
			<?php endfor; ?>
		</div>
	<?php endif; ?>
</div>

<?php get_footer('home'); ?>