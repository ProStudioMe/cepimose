<?php
/**
 * Timeline Block Template - FIXED VERSION.
 *
 * @param array $attributes The block attributes.
 * @param string $content The block default content.
 * @param WP_Block $block The block instance.
 * 
 * Last updated: <?php echo date('Y-m-d H:i:s'); ?> - Fixed WP_Post object handling
 */

// Get timeline posts from the vaccination-timeline category
$category = get_term_by('slug', 'vaccination-timeline', 'category');

if (!$category) {
    ?>
<div <?php echo get_block_wrapper_attributes(); ?>>
    <div style="padding: 20px; background: #f0f0f0; text-align: center;">
        <?php echo esc_html__('Error: Category "vaccination-timeline" not found. Please create this category and add posts to it.', 'prostudiome'); ?>
    </div>
</div>
<?php
    return;
}

$timeline_items = get_posts([
    'post_type' => 'post',
    'posts_per_page' => -1,
    'category' => $category->term_id,
    'post_status' => 'publish',
    'orderby' => 'menu_order',
    'order' => 'ASC',
]);

if (empty($timeline_items)) {
    ?>
<div <?php echo get_block_wrapper_attributes(); ?>>
    <div style="padding: 20px; background: #f0f0f0; text-align: center;">
        <?php echo esc_html__('No timeline items found. Please add posts to the "vaccination-timeline" category.', 'prostudiome'); ?>
    </div>
</div>
<?php
    return;
}

// Get block options
$options = isset($attributes['swiperOptions']) ? $attributes['swiperOptions'] : [];
$default_options = [
    'speed' => 800,
    'autoplay' => false,
    'loop' => false,
    'navigation' => true,
    'effect' => 'slide',
    'pagination' => true,
    'paginationType' => 'bullets',
    'slidesPerView' => 6,
    'spaceBetween' => 20,
    'centeredSlides' => false,
    'grabCursor' => true,
    'breakpoints' => [
        '320' => [
            'slidesPerView' => 1,
            'spaceBetween' => 10
        ],
        '480' => [
            'slidesPerView' => 2,
            'spaceBetween' => 15
        ],
        '768' => [
            'slidesPerView' => 3,
            'spaceBetween' => 15
        ],
        '1024' => [
            'slidesPerView' => 4,
            'spaceBetween' => 20
        ],
        '1280' => [
            'slidesPerView' => 5,
            'spaceBetween' => 20
        ],
        '1440' => [
            'slidesPerView' => 6,
            'spaceBetween' => 20
        ]
    ]
];

$options = wp_parse_args($options, $default_options);

$wrapper_attributes = get_block_wrapper_attributes([
    'data-swiper-options' => json_encode($options),
]);
?>

<div <?php echo $wrapper_attributes; ?>>
    <div class="swiper">
        <div class="swiper-wrapper">
            <?php foreach ($timeline_items as $item) : 
                $age = get_field('vaccination_age', $item->ID);
                $image = get_field('vaccination_image', $item->ID);
                $text = get_field('vaccination_text', $item->ID);
                $link = get_field('vaccination_link', $item->ID);
                $permalink = get_permalink($item->ID);
                
                // Handle ACF link field - it can be an array, string, post object, or other types
                $link_url = '';
                
                // Debug logging to identify the exact type and value
                error_log('Timeline block: link field type: ' . gettype($link) . ', value: ' . print_r($link, true));
                
                if (is_array($link)) {
                    $link_url = isset($link['url']) && !empty($link['url']) ? $link['url'] : '';
                } elseif (is_string($link)) {
                    $link_url = $link;
                } elseif (is_numeric($link)) {
                    $link_url = (string) $link;
                } elseif (is_object($link) && method_exists($link, 'get_permalink')) {
                    // Handle WP_Post objects and other objects with get_permalink method
                    $link_url = $link->get_permalink();
                } elseif (is_object($link) && isset($link->ID)) {
                    // Handle objects with ID property (like WP_Post)
                    $link_url = get_permalink($link->ID);
                } else {
                    $link_url = '';
                }
                
                // Force string conversion and sanitization
                $link_url = trim((string) $link_url);
                
                // Additional safety - if still not a string, force empty string
                if (!is_string($link_url) || $link_url === null) {
                    $link_url = '';
                }
                
                // Final debug check
                error_log('Timeline block: final link_url type: ' . gettype($link_url) . ', value: ' . $link_url);
            ?>
            <div class="swiper-slide">
                <a href="<?php echo esc_url($link_url); ?>" class="timeline-link">
                    <?php if ($age) : ?>
                    <div class="timeline-age"><?php echo esc_html($age); ?></div>
                    <?php endif; ?>
                    <div class="timeline-content">
                        <?php if ($image) : 
                            // If image is returned as array (ACF image field)
                            $image_url = '';
                            $image_alt = '';
                            if (is_array($image)) {
                                $image_url = isset($image['url']) && !empty($image['url']) ? $image['url'] : '';
                                $image_alt = isset($image['alt']) ? $image['alt'] : '';
                            } elseif (is_string($image)) {
                                $image_url = $image;
                            } elseif (is_numeric($image)) {
                                $image_url = (string) $image;
                            } elseif (is_object($image) && method_exists($image, 'get_permalink')) {
                                // Handle WP_Post objects and other objects with get_permalink method
                                $image_url = $image->get_permalink();
                            } elseif (is_object($image) && isset($image->ID)) {
                                // Handle objects with ID property (like WP_Post)
                                $image_url = get_permalink($image->ID);
                            } else {
                                $image_url = '';
                            }
                            
                            // Force string conversion and sanitization
                            $image_url = trim((string) $image_url);
                            
                            // Additional safety - if still not a string, force empty string
                            if (!is_string($image_url) || $image_url === null) {
                                $image_url = '';
                            }
                            
                            if (!empty($image_url)) :
                        ?>
                        <img class="timeline-image" src="<?php echo esc_url($image_url); ?>"
                            alt="<?php echo esc_attr($image_alt); ?>">
                        <?php endif; endif; ?>
                        <?php if ($text) : ?>
                        <div class="timeline-description"><?php echo wp_kses_post($text); ?></div>
                        <?php endif; ?>
                        <div class="timeline-button">
                            <span class="dashicons dashicons-arrow-right-alt"></span>
                        </div>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>

        <?php if ($options['navigation'] && count($timeline_items) > 1) : ?>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
        <?php endif; ?>

        <?php if ($options['pagination'] && count($timeline_items) > 1) : ?>
        <div class="swiper-pagination"></div>
        <?php endif; ?>
    </div>
</div>
