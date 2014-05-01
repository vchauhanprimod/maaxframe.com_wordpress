<?php
global $post, $product;
$src = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), false, '' );
?>

<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        var share_container = jQuery('.thb_product_share'),
            share_placeholders = jQuery('.thb_share_placeholder');

        share_container.find('li a')
            .on('mouseenter', function() {
                share_placeholders.filter("." + jQuery(this).data('type') + "").addClass('share_active');
            })
            .on('mouseleave', function() {
                share_placeholders.removeClass('share_active');
            });
    }, false);
</script>

<div class="thb_product_share">
    <div class="thb_share_placeholder facebook"><span>
                    <strong><?php _e('Share', 'thb_text_domain'); ?></strong> <?php _e('on Facebook', 'thb_text_domain'); ?>
                </span></div>
    <div class="thb_share_placeholder pinterest"><span>
                    <strong><?php _e('Pin', 'thb_text_domain'); ?></strong> <?php _e('this item', 'thb_text_domain'); ?>
                </span></div>
    <div class="thb_share_placeholder twitter"><span>
                    <strong><?php _e('Tweet', 'thb_text_domain'); ?></strong> <?php _e('this item', 'thb_text_domain'); ?>
                </span></div>
    <div class="thb_share_placeholder email"><span>
                    <strong><?php _e('Email', 'thb_text_domain'); ?></strong> <?php _e('a friend', 'thb_text_domain'); ?>
                </span></div>
    <ul>
        <li>
            <a data-type="facebook" href="http://www.facebook.com/sharer.php?u=<?php the_permalink(); ?>" target="_blank" class="product_share_facebook">
                <span>
                    <strong><?php _e('Share', 'thb_text_domain'); ?></strong> <?php _e('on Facebook', 'thb_text_domain'); ?>
                </span>
            </a>
        </li>
        <li>
            <a data-type="pinterest" href="//pinterest.com/pin/create/button/?url=<?php the_permalink(); ?>&media=<?php echo $src[0] ?>&description=<?php the_title(); ?>" target="_blank" class="product_share_pinterest">
                <span>
                    <strong><?php _e('Pin', 'thb_text_domain'); ?></strong> <?php _e('this item', 'thb_text_domain'); ?>
                </span>
            </a>
        </li>
        <li>
            <a data-type="twitter" href="https://twitter.com/share?url=<?php the_permalink(); ?>" target="_blank" class="product_share_twitter">
                <span>
                    <strong><?php _e('Tweet', 'thb_text_domain'); ?></strong> <?php _e('this item', 'thb_text_domain'); ?>
                </span>
            </a>
        </li>
        <li>
            <a data-type="email" href="mailto:enteryour@addresshere.com?subject=<?php the_title(); ?>&body=<?php echo strip_tags(apply_filters( 'woocommerce_short_description', $post->post_excerpt )); ?> <?php the_permalink(); ?>" class="product_share_email">
                <span>
                    <strong><?php _e('Email', 'thb_text_domain'); ?></strong> <?php _e('a friend', 'thb_text_domain'); ?>
                </span>
            </a>
        </li>
    </ul>
</div>