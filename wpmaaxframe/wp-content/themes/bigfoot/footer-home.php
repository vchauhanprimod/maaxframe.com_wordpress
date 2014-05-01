<?php
	$page_id = thb_get_page_ID();
?>
			<?php if( thb_get_post_meta($page_id, 'enable_social_home') == '1' ) : ?>
				<a href="#" class="thb-home-expand" data-icon="u"></a>
			<?php endif; ?>

			<?php if( thb_get_post_meta($page_id, 'enable_twitter_home') == '1' || thb_get_post_meta($page_id, 'enable_social_home') == '1' ) : ?>

			<div class="home-footer-container">
				<div class="wrapper">

					<?php if( thb_get_post_meta($page_id, 'enable_twitter_home') == '1' ) : ?>
						<div class="thb-twitter-livefeed">
							<?php echo do_shortcode('[thb_twitter user="' . thb_get_post_meta($page_id, 'twitter_home_username') . '" num="' . thb_get_post_meta($page_id, 'twitter_home_count') . '"]'); ?>
						</div>
					<?php endif; ?>

					<?php if( thb_get_post_meta($page_id, 'enable_social_home') == '1' ) : ?>
						<div class="thb-social-home">
							<?php
								$thb_services = explode(',', thb_get_post_meta($page_id, 'social_home_services'));

								$thb_services_names = array(
									'twitter'    => 'Twitter',
									'facebook'   => 'Facebook',
									'googleplus' => 'Google+',
									'flickr'     => 'Flickr',
									'youtube'    => 'YouTube',
									'vimeo'      => 'Vimeo',
									'pinterest'  => 'Pinterest',
									'dribbble'   => 'Dribbble',
									'forrst'     => 'Forrst',
									'linkedin'   => 'LinkedIn'
								);

								$thb_services_dataicon = array(
									'twitter'    => '1',
									'facebook'   => '2',
									'googleplus' => '3',
									'flickr'     => '4',
									'youtube'    => '5',
									'vimeo'      => '6',
									'pinterest'  => '7',
									'dribbble'   => '8',
									'forrst'     => '9',
									'linkedin'   => 'v'
								);

								$services = $thb_services;
								if( !empty($show) ) {
									$show = explode(',', $show);
									$services = array();

									foreach( $show as $service_id ) {
										$service_id = trim($service_id);

										if( in_array($service_id, $thb_services) ) {
											$services[] = $service_id;
										}
									}
								}
							?>
							<?php foreach( $services as $id ) : ?>
								<?php
									$id = trim($id);

									$opt = thb_get_option('social_' . $id);
									$img = $thb_services_dataicon[$id];
									$name = $thb_services_names[$id];
								?>

								<?php if( $opt != '' ) : ?>
									<a href="<?php echo $opt; ?>" title="<?php echo $name; ?>">
										<span class="thb-social-icon"><?php echo $img; ?></span>
									</a>
								<?php endif; ?>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</div>
			</div>

			<?php endif; ?>

		</div><!-- /#page -->

		<?php thb_body_end(); ?>

		<?php thb_footer(); ?>
		<?php wp_footer(); ?>
	</body>
</html>