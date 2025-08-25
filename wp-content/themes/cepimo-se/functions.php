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

                                // Add cache busting based on file modification time
                                $file_version = filemtime($sidebar_aside_file);

                                register_block_pattern(
                                'cepimo-se/sidebar-aside',
                                array(
                                'title' => __('Sidebar Aside Widget Area', 'cepimo-se'),
                                'description' => __('A custom tabbed aside sidebar with anchor links and related posts',
                                'cepimo-se'),
                                'content' => trim($sidebar_aside_content),
                                'categories' => array('cepimose'),
                                'keywords' => array('sidebar', 'aside', 'widgets', 'navigation', 'anchor links',
                                'related posts'),
                                'version' => $file_version, // Add version for cache busting
                                )
                                );
                                }
                                }
                                }
                                add_action('init', 'cepimo_se_register_patterns', 10);

                                // Force pattern cache refresh
                                add_action('init', function() {
                                // Clear any cached patterns
                                if (function_exists('wp_cache_flush')) {
                                wp_cache_flush();
                                }
                                // Force WordPress to re-read pattern files
                                delete_transient('block_patterns');
                                delete_transient('block_pattern_categories');
                                }, 5);

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
                                wp_add_inline_script('cepimo-se-archive-search', 'console.log("Script enqueued
                                successfully!");', 'before');
                                }
                                add_action('wp_enqueue_scripts', 'cepimo_se_scripts');

                                /**
                                * Enqueue admin dashboard styles
                                */
                                function cepimo_se_admin_styles() {
                                wp_enqueue_style(
                                'cepimo-se-admin-dashboard',
                                get_template_directory_uri() . '/src/admin-dashboard.css',
                                array(),
                                filemtime( get_stylesheet_directory() . '/src/admin-dashboard.css' )
                                );
                                }
                                add_action('admin_enqueue_scripts', 'cepimo_se_admin_styles');

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

                                /**
                                * Fix category archives to show all posts
                                */
                                function cepimo_se_category_posts_per_page($query) {
                                if (!is_admin() && $query->is_main_query() && is_category()) {
                                $query->set('posts_per_page', -1); // Show ALL posts without pagination
                                $query->set('ignore_sticky_posts', 1); // Ignore sticky posts
                                $query->set('post_status', array('publish', 'private')); // Include private posts too

                                // Clear any post exclusions that might have been set by other plugins
                                $query->set('post__not_in', array());
                                $query->set('post__in', array());

                                // Force a clean query
                                $query->set('suppress_filters', false);
                                }
                                }
                                add_action('pre_get_posts', 'cepimo_se_category_posts_per_page', 999); // High priority

                                /**
                                * Debug function to understand what's happening with category queries
                                */
                                function cepimo_se_debug_category_query($query) {
                                if (!is_admin() && $query->is_main_query() && is_category()) {
                                // Get the category
                                $category = get_queried_object();

                                // Get all posts in this category manually
                                $all_posts = get_posts(array(
                                'category' => $category->term_id,
                                'posts_per_page' => -1,
                                'post_status' => 'publish'
                                ));

                                // Log debug information
                                error_log('=== CATEGORY DEBUG ===');
                                error_log('Category: ' . $category->name . ' (ID: ' . $category->term_id . ')');
                                error_log('Total posts in category: ' . count($all_posts));
                                error_log('Post IDs: ' . implode(', ', wp_list_pluck($all_posts, 'ID')));
                                error_log('Query posts_per_page: ' . $query->get('posts_per_page'));
                                error_log('Query post__not_in: ' . print_r($query->get('post__not_in'), true));
                                error_log('Query post_status: ' . print_r($query->get('post_status'), true));
                                error_log('=====================');
                                }
                                }
                                add_action('pre_get_posts', 'cepimo_se_debug_category_query', 1);

                                /**
                                * Custom template hierarchy for news posts
                                */
                                function cepimo_se_custom_template_hierarchy($templates) {
                                // Check if we're on a single post
                                if (is_single()) {
                                $post = get_post();
                                $categories = get_the_category($post->ID);

                                // Check if the post is in the 'novice' category
                                foreach ($categories as $category) {
                                if ($category->slug === 'novice') {
                                // Add our custom template to the hierarchy
                                $templates[] = 'templates/single-novice.html';
                                break;
                                }
                                }
                                }

                                return $templates;
                                }
                                add_filter('theme_templates', 'cepimo_se_custom_template_hierarchy');

                                /**
                                * Force WordPress to use our custom template for news posts
                                */
                                function cepimo_se_force_news_template($template) {
                                if (is_single()) {
                                $post = get_post();
                                $categories = get_the_category($post->ID);

                                // Check if the post is in the 'novice' category
                                foreach ($categories as $category) {
                                if ($category->slug === 'novice') {
                                $custom_template = get_template_directory() . '/templates/single-novice.html';
                                if (file_exists($custom_template)) {
                                return $custom_template;
                                }
                                break;
                                }
                                }
                                }

                                return $template;
                                }
                                add_filter('single_template', 'cepimo_se_force_news_template');

                                /**
                                * Add custom CSS for news posts
                                */
                                function cepimo_se_news_template_styles() {
                                if (is_single()) {
                                $post = get_post();
                                $categories = get_the_category($post->ID);

                                foreach ($categories as $category) {
                                if ($category->slug === 'novice') {
                                echo '<style>
                                .single-novice .wp-block-post-title {
                                    font-size: 3.5rem !important;
                                    font-weight: 700 !important;
                                    line-height: 1.1 !important;
                                }

                                .single-novice .wp-block-post-featured-image img {
                                    border-radius: 16px !important;
                                }

                                .single-novice .related-news-section {
                                    margin-top: 4rem;
                                }

                                .single-novice .related-news-section h2 {
                                    font-size: 2.5rem !important;
                                    font-weight: 600 !important;
                                    text-align: center;
                                    margin-bottom: 3rem;
                                }
                                </style>';
                                break;
                                }
                                }
                                }
                                }
                                add_action('wp_head', 'cepimo_se_news_template_styles');

                                /**
                                * Debug function to check if news template is being used
                                */
                                function cepimo_se_debug_template_usage() {
                                if (is_single() && current_user_can('administrator')) {
                                $post = get_post();
                                $categories = get_the_category($post->ID);

                                echo '
                                <!-- Template Debug Info: ';
        foreach ($categories as $category) {
            echo 'Category: ' . $category->slug . ' ';
            if ($category->slug === 'novice') {
                echo '(This should use single-novice.html template) ';
            }
        }
        echo ' -->';
                                }
                                }
                                add_action('wp_head', 'cepimo_se_debug_template_usage');

                                /**
                                * Ensure the template is properly registered in WordPress
                                */
                                function cepimo_se_register_news_template() {
                                // This ensures WordPress knows about our custom template
                                add_theme_support('block-templates');
                                }
                                add_action('after_setup_theme', 'cepimo_se_register_news_template');

                                /**
                                * Flush rewrite rules and debug permalink structure
                                */
                                function cepimo_se_flush_rewrite_rules() {
                                // Only run this once after theme activation
                                if (get_option('cepimo_se_rewrite_flushed') !== 'yes') {
                                flush_rewrite_rules();
                                update_option('cepimo_se_rewrite_flushed', 'yes');
                                }
                                }
                                add_action('after_switch_theme', 'cepimo_se_flush_rewrite_rules');

                                /**
                                * Debug function to check permalink structure and category assignments
                                */
                                function cepimo_se_debug_permalinks() {
                                if (is_admin() && current_user_can('administrator')) {
                                // Check current permalink structure
                                $permalink_structure = get_option('permalink_structure');
                                error_log('Current permalink structure: ' . $permalink_structure);

                                // Check if rewrite rules are working
                                global $wp_rewrite;
                                error_log('Rewrite rules enabled: ' . ($wp_rewrite->using_permalinks() ? 'Yes' : 'No'));

                                // Check specific post
                                $post = get_page_by_path('cepljenja-skozi-zivljenje', OBJECT, 'post');
                                if ($post) {
                                $categories = get_the_category($post->ID);
                                error_log('Post "cepljenja-skozi-zivljenje" categories:');
                                foreach ($categories as $cat) {
                                error_log('- ' . $cat->name . ' (slug: ' . $cat->slug . ', ID: ' . $cat->term_id . ')');
                                }

                                // Check what URL WordPress generates
                                $generated_url = get_permalink($post->ID);
                                error_log('Generated URL: ' . $generated_url);
                                }
                                }
                                }
                                add_action('init', 'cepimo_se_debug_permalinks');

                                /**
                                * Force WordPress to use the correct category in permalinks
                                */
                                function cepimo_se_force_category_in_permalinks($permalink, $post) {
                                if ($post->post_type === 'post' && $post->post_status === 'publish') {
                                $categories = get_the_category($post->ID);

                                // Look for the specific category we want
                                $target_category = null;
                                foreach ($categories as $cat) {
                                if ($cat->slug === 'cepljenje-po-zivljenskih-obdobjih') {
                                $target_category = $cat;
                                break;
                                }
                                }

                                // If we found the target category, use it in the permalink
                                if ($target_category) {
                                $permalink = str_replace('%category%', $target_category->slug, $permalink);
                                }
                                }

                                return $permalink;
                                }
                                add_filter('post_link', 'cepimo_se_force_category_in_permalinks', 10, 2);

                                /**
                                * Manual rewrite rules flush function
                                */
                                function cepimo_se_manual_flush_rewrite_rules() {
                                if (isset($_GET['flush_rewrite_rules']) && current_user_can('administrator')) {
                                flush_rewrite_rules();
                                wp_die('Rewrite rules flushed successfully!');
                                }
                                }
                                add_action('init', 'cepimo_se_manual_flush_rewrite_rules');

                                /**
                                * Add admin notice about permalink debugging
                                */
                                function cepimo_se_admin_notice_permalinks() {
                                if (current_user_can('administrator')) {
                                echo '<div class="notice notice-info is-dismissible">';
                                    echo '<p><strong>Cepimo Se Theme:</strong> To debug permalinks, visit: <a
                                            href="' . admin_url('?flush_rewrite_rules=1') . '">Flush Rewrite Rules</a>
                                    </p>';
                                    echo '</div>';
                                }
                                }
                                add_action('admin_notices', 'cepimo_se_admin_notice_permalinks');