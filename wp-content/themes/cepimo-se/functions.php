<?php
/**
 * Cepimo Se Theme functions and definitions
 */

function cepimo_se_scripts() {
    // Deregister default styles
    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('wp-block-library-theme');
    
    // Enqueue Tailwind styles
    wp_enqueue_style(
        'cepimo-se-style', // Handle
        get_template_directory_uri() . '/style.css', // Source
        array(), // Dependencies
        filemtime( get_stylesheet_directory() . '/style.css' ) // Version - USES FILE MODIFICATION TIME
    );
}
add_action('wp_enqueue_scripts', 'cepimo_se_scripts');

// Add support for custom logo
add_theme_support('custom-logo', array(
    'height'      => 48,
    'width'       => 48,
    'flex-height' => true,
    'flex-width'  => true,
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