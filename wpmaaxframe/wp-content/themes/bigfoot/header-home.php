<?php
/**
 * @package WordPress
 * @subpackage BigFoot
 * @since BigFoot 1.0
 */
?>
<!doctype html>
<html <?php language_attributes(); ?> <?php thb_html_class(); ?>>
	<head>
		<?php thb_head_meta(); ?>

		<title><?php thb_title(); ?></title>

		<?php wp_head(); ?>
	</head>
	<body <?php body_class(); ?>>

		<?php thb_body_start(); ?>

		<div id="page">

			<?php thb_header_before(); ?>

				<?php thb_header_start(); ?>

				<div class="header-container">

					<div class="wrapper">
						<?php
							$logo = thb_get_option('main_logo');
							$logo_2x = thb_get_option('main_logo_retina');

							if( !empty($logo['id']) && !empty($logo_2x['id']) ) : ?>
							<?php $logo_metadata = wp_get_attachment_metadata($logo['id']); ?>
							<style>
								@media all and (-webkit-min-device-pixel-ratio: 1.5) {
									#logo {
										background-image: url('<?php echo thb_image_get_size($logo_2x['id'], 'full'); ?>');
										background-size: <?php echo $logo_metadata['width']; ?>px, <?php echo $logo_metadata['height']; ?>px;
									}

									#logo img { visibility: hidden; }
								}
							</style>

							<?php endif;
						?>
						<h1 id="logo">
							<a href="<?php echo home_url(); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>">
								<?php if( isset($logo['id']) && $logo['id'] != '' ) : ?>
									<img src="<?php echo thb_image_get_size($logo['id'], 'full'); ?>" alt="">
								<?php else : ?>
									<?php bloginfo( 'name' ); ?>
								<?php endif; ?>
							</a>
						</h1>

						<?php thb_nav_before(); ?>

						<a href="#" id="nav-trigger">m</a>

						<div class="nav-wrapper">
							<nav id="main-nav" class="main-navigation primary">
								<?php thb_nav_start(); ?>

								<?php wp_nav_menu( array( 'theme_location' => 'primary' ) ); ?>

								<?php thb_nav_end(); ?>
							</nav>

							<nav id="mobile-nav" class="main-navigation primary">
								<?php thb_nav_start(); ?>

								<?php wp_nav_menu( array( 'theme_location' => 'primary' ) ); ?>

								<?php thb_nav_end(); ?>
							</nav>
						</div>
						<?php thb_nav_after(); ?>
					</div>

				</div>
