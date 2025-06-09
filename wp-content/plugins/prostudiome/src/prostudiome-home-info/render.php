<?php
/**
 * Home Info Block Template.
 *
 * @param array $attributes The block attributes.
 * @param string $content The block default content.
 * @param WP_Block $block The block instance.
 */

// Get the home page by its slug
$home_page = get_page_by_path('home-page-cepimose');

if (!$home_page) {
    return;
}

// Get the fields from the specific page
$title = get_field('title', $home_page->ID);
$subtitle = get_field('subtitle', $home_page->ID);
$info_box = get_field('info_box', $home_page->ID);
$text = get_field('text', $home_page->ID);
$background_image = get_field('background_image', $home_page->ID);
$link = get_field('link', $home_page->ID);

// Set up the wrapper attributes
$wrapper_attributes = get_block_wrapper_attributes();
// Note: We don't need to add additional classes here as WordPress will automatically
// add the wp-block-prostudiome-home-info class based on the block name
?>

<style>
.wp-block-prostudiome-home-info {
    border: 10px solid red !important;
    background-color: #f0f0f0 !important;
}
</style>

<div <?php echo $wrapper_attributes; ?>>
    <?php if ($background_image) : ?>
        <img src="<?php echo esc_url($background_image['url']); ?>" 
             alt="<?php echo esc_attr($background_image['alt']); ?>" 
             class="home-info-background-image" />
    <?php endif; ?>

    <div class="home-info-content">
        <div class="home-info-content-left">
            <img class="home-info-icon" src="<?php echo esc_url(get_template_directory_uri() . '/assets/icon-injection.svg'); ?>" alt="">
            <div class="home-info-titles-container">
                <?php if ($title) : ?>
                    <h2 class="home-info-title"><?php echo esc_html($title); ?></h2>
                <?php endif; ?>
                <?php if ($subtitle) : ?>
                    <div class="home-info-subtitle"><?php echo esc_html($subtitle); ?></div>
                <?php endif; ?>
            </div>
            <?php if ($info_box) : ?>
                <div class="home-info-box"><?php echo wp_kses_post($info_box); ?></div>
            <?php endif; ?>
            <?php if (!empty($link)) : ?>
                <a href="/<?php echo esc_attr($link); ?>" class="home-info-link">
                <svg width="14" height="14" fill="white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M502.6 278.6l-128 128c-12.51 12.51-32.76 12.49-45.25 0c-12.5-12.5-12.5-32.75 0-45.25L402.8 288H32C14.31 288 0 273.7 0 255.1S14.31 224 32 224h370.8l-73.38-73.38c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0l128 128C515.1 245.9 515.1 266.1 502.6 278.6z"></path></svg>
                </a>
            <?php endif; ?>
        </div>

        <?php if ($text) : ?>
            <div class="home-info-text"><?php echo wp_kses_post($text); ?></div>
        <?php endif; ?>

       
    </div>
</div>
