<?php
/**
 * Banner Slider Block Template.
 *
 * @param array $attributes The block attributes.
 * @param string $content The block default content.
 * @param WP_Block $block The block instance.
 */

// Get banner posts from the banner-front-page category
$category = get_term_by('slug', 'banner-front-page', 'category');
if (!$category) {
    error_log('Banner Swiper: Category "banner-front-page" not found');
    return;
}

error_log('Banner Swiper: Found category with ID ' . $category->term_id);

$banners = get_posts([
    'post_type' => 'post',
    'posts_per_page' => -1,
    'category' => $category->term_id,
]);

if (empty($banners)) {
    error_log('Banner Swiper: No posts found in category ' . $category->term_id);
    return;
}

error_log('Banner Swiper: Found ' . count($banners) . ' posts');

// Get block options
$options = [
    'speed' => 800,
    'autoplay' => true,
    'autoplayDelay' => 5000,
    'pauseOnMouseEnter' => true,
    'loop' => true,
    'navigation' => true,
    'effect' => 'fade',
    'pagination' => true,
    'paginationType' => 'bullets',
];

$wrapper_attributes = get_block_wrapper_attributes([
    'data-swiper-options' => json_encode($options),
]);
?>

<div <?php echo $wrapper_attributes; ?>>
    <div class="swiper">
        <div class="swiper-wrapper">
            <?php 
            $valid_banners = 0;
            foreach ($banners as $banner) : 
                $main_heading = get_field('banner_-_main_heading', $banner->ID);
                $main_image = get_field('banner_main_image', $banner->ID);
                $subheading = get_field('banner_subheading', $banner->ID);
                $text = get_field('banner_text', $banner->ID);
                $link = get_field('banner_link', $banner->ID);

                error_log('Banner Swiper: Processing post ' . $banner->ID);
                error_log('Banner Swiper: Main heading: ' . ($main_heading ? 'yes' : 'no'));
                error_log('Banner Swiper: Main image: ' . ($main_image ? 'yes' : 'no'));

                if (!$main_image) {
                    error_log('Banner Swiper: Skipping post ' . $banner->ID . ' - no main image');
                    continue;
                }

                $valid_banners++;
            ?>
            <div class="swiper-slide" style="background-image: url('<?php echo esc_url($main_image['url']); ?>');">
                <div class="banner-content">
                    <?php if ($main_heading) : ?>
                    <h2 class="banner-title"><?php echo esc_html($main_heading); ?></h2>
                    <?php endif; ?>

                    <?php if ($subheading) : ?>
                    <div class="banner-subtitle"><?php echo esc_html($subheading); ?></div>
                    <?php endif; ?>

                    <?php if ($text) : ?>
                    <div class="banner-text"><?php echo esc_html($text); ?></div>
                    <?php endif; ?>

                    <?php if ($link) : ?>
                    <a href="<?php echo esc_url($link['url']); ?>" class="banner-link"
                        <?php echo $link['target'] ? 'target="' . esc_attr($link['target']) . '"' : ''; ?>>
                        <?php echo esc_html($link['title']); ?>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; 
            
            error_log('Banner Swiper: Total valid banners: ' . $valid_banners);
            ?>
        </div>

        <?php if ($options['navigation'] && $valid_banners > 1) : ?>
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
        <?php endif; ?>

        <?php if ($options['pagination'] && $valid_banners > 1) : ?>
        <div class="swiper-pagination"></div>
        <?php endif; ?>
    </div>
</div>