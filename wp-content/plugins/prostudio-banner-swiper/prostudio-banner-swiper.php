<?php
/**
 * Plugin Name:       ProStudio Banner Swiper
 * Description:       Banner slider block using Swiper
 * Requires at least: 6.1
 * Requires PHP:      7.0
 * Version:          1.0.0
 * Author:           ProStudio.me
 * License:          GPL-2.0-or-later
 * License URI:      https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:      prostudio-banner-swiper
 *
 * @package          prostudio-banner-swiper
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 */
function prostudio_banner_swiper_init() {
    // Register Swiper scripts and styles
    wp_register_style(
        'swiper',
        'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css',
        array(),
        '11.0.5'
    );

    wp_register_script(
        'swiper',
        'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js',
        array(),
        '11.0.5',
        true
    );

    register_block_type(__DIR__ . '/build/banner-swiper');
}
add_action('init', 'prostudio_banner_swiper_init'); 