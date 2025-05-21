<?php
/**
 * Timeline Block Template.
 *
 * @param array $attributes The block attributes.
 * @param string $content The block default content.
 * @param WP_Block $block The block instance.
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
                $permalink = get_permalink($item->ID);
            ?>
            <div class="swiper-slide">
                <a href="<?php echo esc_url($permalink); ?>" class="timeline-link">
                    <?php if ($age) : ?>
                    <div class="timeline-age"><?php echo esc_html($age); ?></div>
                    <?php endif; ?>
                    <div class="timeline-content">
                        <?php if ($image) : 
                            // If image is returned as array (ACF image field)
                            $image_url = is_array($image) ? $image['url'] : $image;
                            $image_alt = is_array($image) ? $image['alt'] : '';
                        ?>
                        <img class="timeline-image" src="<?php echo esc_url($image_url); ?>"
                            alt="<?php echo esc_attr($image_alt); ?>">
                        <?php endif; ?>
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
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
        <?php endif; ?>

        <?php if ($options['pagination'] && count($timeline_items) > 1) : ?>
        <div class="swiper-pagination"></div>
        <?php endif; ?>
    </div>
</div>