<?php
/**
 * @package WordPress
 * @subpackage BigFoot
 * @since BigFoot 1.0
 * Template name: Contact
 */

/**
 * Send mail script
 */
thb_system_send_mail( thb_get_option('contact_email') );

$thb_page_id = get_the_ID();
$subtitle = thb_get_post_meta( $thb_page_id, 'subtitle' );

$email = thb_get_option("contact_email");
$latlong = thb_get_option('contact_lat_long');
$zoom = thb_get_option("contact_zoom");
$contact_info = thb_duplicable_get('contact_info');

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
			<?php if( have_posts() ) : while( have_posts() ) : the_post(); ?>
				<?php thb_page_start(); ?>

				<?php if( !empty($latlong) ) : ?>
					<div id="contact-map">
						<?php thb_contact_map(array(
							'height' => 336
						)); ?>
					</div>
				<?php endif; ?>

				<div class="contact-content<?php if( count($contact_info) > 0 ) : ?> w-contact-info<?php endif; ?><?php if( !empty($latlong) ) : ?> w-map<?php endif; ?>">
					<div id="contactform">
						<?php thb_contact_form(); ?>
					</div>

					<?php if( count($contact_info) > 0 ) : ?>
						<div id="contactinfo">
							<ul>
								<?php foreach( $contact_info as $info ) : ?>
									<li class="thb-<?php echo $info['value']['type']; ?>">
										<?php if( !empty($info['value']['key']) ) : ?>
											<div class="thb-key"><?php echo thb_text_format($info['value']['key']); ?></div>
										<?php endif; ?>

										<?php if( !empty($info['value']['value']) ) : ?>
											<?php
												$class='';
												if( $info['value']['type'] == 'other' ) {
													$class = 'thb-text';
												}
											?>

											<div class="thb-value <?php echo $class; ?>">
												<?php if( $info['value']['type'] == 'other' ) : ?>
													<?php echo thb_text_format($info['value']['value'], true); ?>
												<?php elseif( $info['value']['type'] == 'address' ) : ?>
													<?php echo nl2br(thb_text_format($info['value']['value'])); ?>
												<?php else : ?>
													<?php echo thb_text_format($info['value']['value']); ?>
												<?php endif; ?>
											</div>
										<?php endif; ?>
									</li>
								<?php endforeach; ?>
							</ul>
						</div>
					<?php endif; ?>
				</div>

				<div class="thb-text">
					<?php the_content(); ?>
				</div>

				<?php if( thb_show_comments() ) : ?>
				<section class="secondary">
					<?php thb_comments(); ?>
				</section>
				<?php endif; ?>

				<?php thb_page_end(); ?>
			<?php endwhile; endif; ?>
		</section>
	<?php thb_page_after(); ?>

		<?php thb_page_sidebar(); ?>

<?php get_footer(); ?>