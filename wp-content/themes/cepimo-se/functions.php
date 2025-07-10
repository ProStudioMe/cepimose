<?php
/**
 * Cepimo Se Theme functions and definitions
 */

/**
 * Theme Setup
 */
function cepimo_se_setup() {
    // Add support for block patterns
    add_theme_support('block-patterns');
}
add_action('after_setup_theme', 'cepimo_se_setup'); 

/**
 * Register custom pattern categories
 */
function cepimo_se_register_pattern_categories() {
    if (function_exists('register_block_pattern_category')) {
        register_block_pattern_category(
            'cepimose',
            array('label' => __('Cepimo Se', 'cepimo-se'))
        );
    }
}
add_action('init', 'cepimo_se_register_pattern_categories', 9);

/**
 * Register patterns manually to ensure they show up
 */
function cepimo_se_register_patterns() {
    if (function_exists('register_block_pattern')) {
        // Text + paragraph block - solid, outlined
        $pattern_file = get_template_directory() . '/patterns/text-paragraph-block-solid-outlined.php';
        if (file_exists($pattern_file)) {
            ob_start();
            include $pattern_file;
            $pattern_content = ob_get_clean();
            
            // Extract only the HTML content (after the PHP header)
            $pattern_content = preg_replace('/^<\?php.*?\?>\s*/s', '', $pattern_content);

register_block_pattern(
'cepimo-se/text-paragraph-block-solid-outlined',
array(
'title' => __('Text + paragraph block - solid, outlined', 'cepimo-se'),
'description' => __('A styled text block with blue background and border', 'cepimo-se'),
'content' => trim($pattern_content),
'categories' => array('cepimose'),
'keywords' => array('text', 'paragraph', 'blue', 'border', 'outlined'),
)
);
}

// Link solid outline with icon
$link_pattern_file = get_template_directory() . '/patterns/link-solid-outline-with-icon.php';
if (file_exists($link_pattern_file)) {
ob_start();
include $link_pattern_file;
$link_pattern_content = ob_get_clean();

// Extract only the HTML content (after the PHP header)
$link_pattern_content = preg_replace('/^<\?php.*?\?>\s*/s', '', $link_pattern_content);

    register_block_pattern(
    'cepimo-se/link-solid-outline-with-icon',
    array(
    'title' => __('Link solid outline with icon', 'cepimo-se'),
    'description' => __('A styled link button with icon and solid outline', 'cepimo-se'),
    'content' => trim($link_pattern_content),
    'categories' => array('cepimose'),
    'keywords' => array('link', 'button', 'icon', 'outline', 'arrow'),
    )
    );
    }

    // Title, text, image right
    $title_text_image_file = get_template_directory() . '/patterns/title-text-image-right.php';
    if (file_exists($title_text_image_file)) {
    ob_start();
    include $title_text_image_file;
    $title_text_image_content = ob_get_clean();

    // Extract only the HTML content (after the PHP header)
    $title_text_image_content = preg_replace('/^<\?php.*?\?>\s*/s', '', $title_text_image_content);

        register_block_pattern(
        'cepimo-se/title-text-image-right',
        array(
        'title' => __('Title, text, image right', 'cepimo-se'),
        'description' => __('A two-column layout with title and text on the left, image on the right', 'cepimo-se'),
        'content' => trim($title_text_image_content),
        'categories' => array('cepimose'),
        'keywords' => array('title', 'text', 'image', 'columns', 'layout'),
        )
        );
        }

        // Title, text, image left
        $title_text_image_left_file = get_template_directory() . '/patterns/title-text-image-left.php';
        if (file_exists($title_text_image_left_file)) {
        ob_start();
        include $title_text_image_left_file;
        $title_text_image_left_content = ob_get_clean();

        // Extract only the HTML content (after the PHP header)
        $title_text_image_left_content = preg_replace('/^<\?php.*?\?>\s*/s', '', $title_text_image_left_content);

            register_block_pattern(
            'cepimo-se/title-text-image-left',
            array(
            'title' => __('Title, text, image left', 'cepimo-se'),
            'description' => __('A two-column layout with image on the left, title and text on the right', 'cepimo-se'),
            'content' => trim($title_text_image_left_content),
            'categories' => array('cepimose'),
            'keywords' => array('title', 'text', 'image', 'columns', 'layout', 'left'),
            )
            );
            }

            // Button + icon - Solid
            $button_icon_solid_file = get_template_directory() . '/patterns/button-icon-solid.php';
            if (file_exists($button_icon_solid_file)) {
            ob_start();
            include $button_icon_solid_file;
            $button_icon_solid_content = ob_get_clean();

            // Extract only the HTML content (after the PHP header)
            $button_icon_solid_content = preg_replace('/^<\?php.*?\?>\s*/s', '', $button_icon_solid_content);

                register_block_pattern(
                'cepimo-se/button-icon-solid',
                array(
                'title' => __('Button + icon - Solid', 'cepimo-se'),
                'description' => __('A solid blue button with icon for call-to-action', 'cepimo-se'),
                'content' => trim($button_icon_solid_content),
                'categories' => array('cepimose'),
                'keywords' => array('button', 'icon', 'solid', 'blue', 'cta'),
                )
                );
                }

                // Button + icon - Outlined
                $button_icon_outlined_file = get_template_directory() . '/patterns/button-icon-outlined.php';
                if (file_exists($button_icon_outlined_file)) {
                ob_start();
                include $button_icon_outlined_file;
                $button_icon_outlined_content = ob_get_clean();

                // Extract only the HTML content (after the PHP header)
                $button_icon_outlined_content = preg_replace('/^<\?php.*?\?>\s*/s', '', $button_icon_outlined_content);

                    register_block_pattern(
                    'cepimo-se/button-icon-outlined',
                    array(
                    'title' => __('Button + icon - Outlined', 'cepimo-se'),
                    'description' => __('An outlined button with icon for call-to-action', 'cepimo-se'),
                    'content' => trim($button_icon_outlined_content),
                    'categories' => array('cepimose'),
                    'keywords' => array('button', 'icon', 'outlined', 'border', 'cta'),
                    )
                    );
                    }

                    // Quotes
                    $quotes_file = get_template_directory() . '/patterns/quotes.php';
                    if (file_exists($quotes_file)) {
                    ob_start();
                    include $quotes_file;
                    $quotes_content = ob_get_clean();

                    // Extract only the HTML content (after the PHP header)
                    $quotes_content = preg_replace('/^<\?php.*?\?>\s*/s', '', $quotes_content);

                        register_block_pattern(
                        'cepimo-se/quotes',
                        array(
                        'title' => __('Quotes', 'cepimo-se'),
                        'description' => __('A centered quotes section with quote icons and text content', 'cepimo-se'),
                        'content' => trim($quotes_content),
                        'categories' => array('cepimose'),
                        'keywords' => array('quotes', 'testimonial', 'container', 'centered'),
                        )
                        );
                        }

                        // Media text - Blue
                        $media_text_blue_file = get_template_directory() . '/patterns/media-text-blue.php';
                        if (file_exists($media_text_blue_file)) {
                        ob_start();
                        include $media_text_blue_file;
                        $media_text_blue_content = ob_get_clean();

                        // Extract only the HTML content (after the PHP header)
                        $media_text_blue_content = preg_replace('/^<\?php.*?\?>\s*/s', '', $media_text_blue_content);

                            register_block_pattern(
                            'cepimo-se/media-text-blue',
                            array(
                            'title' => __('Media text - Blue', 'cepimo-se'),
                            'description' => __('A media-text block with blue styling and rounded borders',
                            'cepimo-se'),
                            'content' => trim($media_text_blue_content),
                            'categories' => array('cepimose'),
                            'keywords' => array('media', 'text', 'image', 'blue', 'rounded', 'border'),
                            )
                            );
                            }

                            // Sidebar Aside Widget Area
                            $sidebar_aside_file = get_template_directory() . '/patterns/sidebar-aside.php';
                            if (file_exists($sidebar_aside_file)) {
                            ob_start();
                            include $sidebar_aside_file;
                            $sidebar_aside_content = ob_get_clean();

                            // Extract only the HTML content (after the PHP header)
                            $sidebar_aside_content = preg_replace('/^<\?php.*?\?>\s*/s', '', $sidebar_aside_content);

                                register_block_pattern(
                                'cepimo-se/sidebar-aside',
                                array(
                                'title' => __('Sidebar Aside Widget Area', 'cepimo-se'),
                                'description' => __('A customizable aside sidebar with widgets for recent posts,
                                categories, tags, and archives', 'cepimo-se'),
                                'content' => trim($sidebar_aside_content),
                                'categories' => array('cepimose'),
                                'keywords' => array('sidebar', 'aside', 'widgets', 'navigation', 'recent posts',
                                'categories'),
                                )
                                );
                                }
                                }
                                }
                                add_action('init', 'cepimo_se_register_patterns', 10);

                                function cepimo_se_scripts() {
                                // Deregister default styles
                                wp_dequeue_style('wp-block-library');
                                wp_dequeue_style('wp-block-library-theme');

                                // Enqueue Tailwind styles
                                wp_enqueue_style(
                                'cepimo-se-style',
                                get_template_directory_uri() . '/style.css',
                                array(),
                                filemtime( get_stylesheet_directory() . '/style.css' )
                                );

                                // Enqueue mobile navigation JavaScript
                                wp_enqueue_script(
                                    'cepimo-se-mobile-nav',
                                    get_template_directory_uri() . '/src/mobile-nav.js',
                                    array(),
                                    filemtime( get_stylesheet_directory() . '/src/mobile-nav.js' ),
                                    true
                                );

                                // Enqueue archive search JavaScript
                                wp_enqueue_script(
                                'cepimo-se-archive-search',
                                get_template_directory_uri() . '/src/archive-search.js',
                                array(),
                                filemtime( get_stylesheet_directory() . '/src/archive-search.js' ),
                                true
                                );

                                                // Enqueue search modal JavaScript
                wp_enqueue_script(
                    'cepimo-se-search-modal',
                    get_template_directory_uri() . '/src/search-modal.js',
                    array(),
                    filemtime( get_stylesheet_directory() . '/src/search-modal.js' ),
                    true
                );

                // Enqueue sidebar tabs JavaScript (conflict-resistant)
                wp_enqueue_script(
                    'cepimo-se-sidebar-tabs',
                    get_template_directory_uri() . '/src/sidebar-tabs.js',
                    array(), // No dependencies to avoid conflicts
                    filemtime( get_stylesheet_directory() . '/src/sidebar-tabs.js' ),
                    true // Load in footer after other scripts
                );

                                                

                                // Debug: Add inline script to confirm enqueuing
                                wp_add_inline_script('cepimo-se-archive-search', 'console.log("Script enqueued successfully!");', 'before');
                                }
                                add_action('wp_enqueue_scripts', 'cepimo_se_scripts');

                                // Add support for custom logo
                                add_theme_support('custom-logo', array(
                                'height' => 48,
                                'width' => 48,
                                'flex-height' => true,
                                'flex-width' => true,
                                ));

                                // Add support for title-tag
                                add_theme_support('title-tag');

                                /**
                                * Register custom blocks
                                */
                                function cepimo_se_register_blocks() {
                                // Register the post slider block

                                }
                                add_action('init', 'cepimo_se_register_blocks');

                                /**
                                * ACF Shortcodes for Aside Elements
                                */
                                function cepimo_se_aside_button_shortcode() {
                                $aside_button = get_field('aside_button');

                                // Hide button if text is empty
                                if (!$aside_button || empty($aside_button['aside_button_text'])) {
                                return '';
                                }

                                if (!empty($aside_button['aside_button_link'])) {
                                return sprintf(
                                '<a href="%s" class="btn btn-primary">%s</a>',
                                esc_url($aside_button['aside_button_link']),
                                esc_html($aside_button['aside_button_text'])
                                );
                                }

                                // If no link but has text, show button without link
                                return sprintf(
                                '<span class="btn btn-primary">%s</span>',
                                esc_html($aside_button['aside_button_text'])
                                );
                                }
                                add_shortcode('aside_button', 'cepimo_se_aside_button_shortcode');

                                function cepimo_se_aside_banner_shortcode() {
                                $aside_banner = get_field('aside_banner');

                                // Hide banner if image is empty
                                if (!$aside_banner || empty($aside_banner['aside_banner_image'])) {
                                return '';
                                }

                                $banner_image = $aside_banner['aside_banner_image'];
                                if ($banner_image) {
                                if (!empty($aside_banner['aside_banner_link'])) {
                                // Image with link
                                return sprintf(
                                '<a href="%s"><img src="%s" alt="%s"></a>',
                                esc_url($aside_banner['aside_banner_link']),
                                esc_url($banner_image['url']),
                                esc_attr($banner_image['alt'])
                                );
                                } else {
                                // Image without link
                                return sprintf(
                                '<img src="%s" alt="%s">',
                                esc_url($banner_image['url']),
                                esc_attr($banner_image['alt'])
                                );
                                }
                                }

                                return '';
                                }
                                add_shortcode('aside_banner', 'cepimo_se_aside_banner_shortcode');

                                