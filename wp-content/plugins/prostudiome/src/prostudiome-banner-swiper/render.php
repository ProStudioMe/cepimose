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

// Debug: List all categories
$all_categories = get_categories();
error_log('Banner Swiper: All categories:');
foreach ($all_categories as $cat) {
    error_log("- {$cat->name} (slug: {$cat->slug}, ID: {$cat->term_id})");
}

if (!$category) {
    error_log('Banner Swiper: Category "banner-front-page" not found');
    ?>
<div <?php echo get_block_wrapper_attributes(); ?> class="banner-swiper">
    <div style="padding: 20px; background: #f0f0f0; text-align: center;">
        <?php echo esc_html__('Error: Category "banner-front-page" not found. Please create this category and add posts to it.', 'prostudiome'); ?>
    </div>
</div>
<?php
    return;
}

error_log('Banner Swiper: Found category with ID ' . $category->term_id);

$banners = get_posts([
    'post_type' => 'post',
    'posts_per_page' => -1,
    'category' => $category->term_id,
    'post_status' => 'publish',
]);

if (empty($banners)) {
    error_log('Banner Swiper: No posts found in category ' . $category->term_id);
    ?>
<div <?php echo get_block_wrapper_attributes(); ?>>
    <div style="padding: 20px; background: #f0f0f0; text-align: center;">
        <?php echo esc_html__('No banner posts found. Please add posts to the "banner-front-page" category.', 'prostudiome'); ?>
    </div>
</div>
<?php
    return;
}

error_log('Banner Swiper: Found ' . count($banners) . ' posts');

// Debug: Check ACF
if (!function_exists('get_field')) {
    error_log('Banner Swiper: ACF plugin is not active!');
    ?>
<div <?php echo get_block_wrapper_attributes(); ?>>
    <div style="padding: 20px; background: #f0f0f0; text-align: center;">
        <?php echo esc_html__('Error: Advanced Custom Fields plugin is required but not active.', 'prostudiome'); ?>
    </div>
</div>
<?php
    return;
}

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
                error_log('Banner Swiper: Processing post ' . $banner->ID . ' - ' . $banner->post_title);
                
                // Debug ACF fields
                $fields = get_fields($banner->ID);
                error_log('Banner Swiper: Available ACF fields for post ' . $banner->ID . ': ' . print_r($fields, true));
                
                $main_heading = get_field('banner_-_main_heading', $banner->ID);
                $main_image = get_field('banner_main_image', $banner->ID);
                $subheading = get_field('banner_subheading', $banner->ID);
                $text = get_field('banner_text', $banner->ID);
                $link = get_field('banner_link', $banner->ID);
                $link_text = get_field('banner_link_text', $banner->ID);

                error_log('Banner Swiper: Fields for post ' . $banner->ID . ':');
                error_log('- Main heading: ' . ($main_heading ? $main_heading : 'not set'));
                error_log('- Main image: ' . ($main_image ? 'set' : 'not set'));
                error_log('- Subheading: ' . ($subheading ? $subheading : 'not set'));
                error_log('- Text: ' . ($text ? 'set' : 'not set'));
                error_log('- Link: ' . ($link ? 'set' : 'not set'));

                if (!$main_image) {
                    error_log('Banner Swiper: Skipping post ' . $banner->ID . ' - no main image');
                    continue;
                }

                $valid_banners++;
            ?>
            <div class="swiper-slide banner-swiper-slide lazy-loaded-image object-cover w-full h-full max-h-full aspect-[1920/640]"
                >
                <div class="banner-swiper-content">
                    <?php if ($main_heading) : ?>
                    <h1 class="banner-swiper-title"><?php echo esc_html($main_heading); ?></h1>
                    <?php endif; ?>

                    <?php if ($subheading) : ?>
                    <div class="banner-swiper-subtitle"><?php echo esc_html($subheading); ?></div>
                    <?php endif; ?>

                    <?php if ($text) : ?>
                    <div class="banner-swiper-text"><?php echo esc_html($text); ?></div>
                    <?php endif; ?>

                    <?php if ($link) : ?>
                    <a href="<?php echo esc_url($link['url']); ?>" class="banner-swiper-link"
                        <?php echo $link['target'] ? 'target="' . esc_attr($link['target']) . '"' : ''; ?>>
                        <?php echo esc_html($link_text); ?>
                        <img src="wp-content/themes/cepimo-se/assets/icon-arrow-right-circle.svg" alt='<?php echo esc_html($link_text); ?>'>
                    </a>
                    <?php endif; ?>
                </div>
                <img class="banner-swiper-image" src="<?php echo esc_url($main_image['url']); ?>" />
            </div>
            <?php endforeach; 
            
            error_log('Banner Swiper: Total valid banners: ' . $valid_banners);

            if ($valid_banners === 0) : ?>
            <div style="padding: 20px; background: #f0f0f0; text-align: center;">
                <?php echo esc_html__('No valid banner posts found. Please ensure posts have the required image field set.', 'prostudiome'); ?>
            </div>
            <?php endif; ?>
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