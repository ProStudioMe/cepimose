<?php
/**
 * Plugin Name:       ProStudio.me Blocks
 * Description:       Custom blocks for ProStudio.me
 * Requires at least: 6.1
 * Requires PHP:      7.0
 * Version:          1.0.0
 * Author:           ProStudio.me
 * License:          GPL-2.0-or-later
 * License URI:      https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:      prostudiome
 *
 * @package          prostudiome
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */
function prostudiome_blocks_init() {
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

	// Register blocks
	register_block_type(__DIR__ . '/build/prostudiome-banner-swiper');
	register_block_type(__DIR__ . '/build/prostudiome-timeline');
}
add_action('init', 'prostudiome_blocks_init');

/**
 * Enqueue scripts and styles for the frontend
 */
function prostudiome_enqueue_scripts() {
	if (has_block('prostudiome/banner-swiper') || has_block('prostudiome/timeline')) {
		wp_enqueue_style('swiper');
		wp_enqueue_script('swiper');
	}
}
add_action('wp_enqueue_scripts', 'prostudiome_enqueue_scripts');