<?php
/**
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */

// Get posts from banner-front-page category
$args = array(
    'post_type' => 'post',
    'category_name' => 'banner-front-page',
    'posts_per_page' => -1,
);

$banner_posts = get_posts($args);

if (!empty($banner_posts)) :
    $swiper_options = wp_json_encode($attributes['swiperOptions'] ?? (object)[]);
?>
<div <?php echo get_block_wrapper_attributes(); ?> data-swiper-options="<?php echo esc_attr($swiper_options); ?>">
    <div class="swiper">
        <div class="swiper-wrapper">
            <?php foreach ($banner_posts as $post) : 
                // Get ACF fields
                $main_heading = get_field('banner_-_main_heading', $post->ID);
                $main_image = get_field('banner_main_image', $post->ID);
                $subheading = get_field('banner_subheading', $post->ID);
                $text = get_field('banner_text', $post->ID);
                $link = get_field('banner_link', $post->ID);
            ?>
                <div class="swiper-slide">
                    <div class="banner-content">
                        <div class="banner-text-content">
                            <?php if ($main_heading) : ?>
                                <h1 class="banner-heading"><?php echo esc_html($main_heading); ?></h1>
                            <?php endif; ?>

                            <?php if ($subheading) : ?>
                                <h2 class="banner-subheading"><?php echo esc_html($subheading); ?></h2>
                            <?php endif; ?>

                            <?php if ($text) : ?>
                                <div class="banner-text"><?php echo wp_kses_post($text); ?></div>
                            <?php endif; ?>

                            <?php if ($link) : ?>
                                <a href="<?php echo esc_url($link['url']); ?>" class="banner-link">
                                    <?php echo esc_html($link['title']); ?>
                                </a>
                            <?php endif; ?>
                        </div>

                        <?php if ($main_image) : ?>
                            <div class="banner-image">
                                <img src="<?php echo esc_url($main_image['url']); ?>"
                                     alt="<?php echo esc_attr($main_image['alt']); ?>" />
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php if ($attributes['swiperOptions']['pagination'] ?? true) : ?>
            <div class="swiper-pagination"></div>
        <?php endif; ?>
        
        <?php if ($attributes['swiperOptions']['navigation'] ?? true) : ?>
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?> 