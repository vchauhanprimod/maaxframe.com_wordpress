<?php
	$work_types = wp_get_object_terms( get_the_ID(), "portfolio_categories" );
	$types = array();
		if( !empty($work_types) )
			foreach($work_types as $type)
				$types[] = $type->name;

	$subtitle = join($types, ", ");
?>
<div class="work-extra-wrapper">
<?php if( !empty($work_featured_image) ) : ?>
		<img src="<?php echo $work_featured_image; ?>" alt="">
		<article class="data">
			<header>
				<h1>
					<a data-id="<?php the_ID(); ?>" href="<?php the_permalink(); ?>" title="<?php _e('View', 'thb_text_domain'); ?> <?php echo get_the_title(); ?>"><?php the_title(); ?></a>
				</h1>
				<?php if( !empty($subtitle) ) : ?>
				<h2>
					<?php echo $subtitle; ?>
				</h2>
				<?php endif; ?>
			</header>
			<a data-id="<?php the_ID(); ?>" href="<?php the_permalink(); ?>" class="view-work" title="<?php _e('View', 'thb_text_domain'); ?> <?php echo get_the_title(); ?>">></a>
		</article>
<?php else : ?>
		<article class="data">
			<header>
				<h1>
					<a data-id="<?php the_ID(); ?>" href="<?php the_permalink(); ?>" title="<?php _e('View', 'thb_text_domain'); ?> <?php echo get_the_title(); ?>"><?php the_title(); ?></a>
				</h1>
				<?php if( !empty($subtitle) ) : ?>
				<h2>
					<?php echo $subtitle; ?>
				</h2>
				<?php endif; ?>
			</header>
		</article>
<?php endif; ?>
</div>