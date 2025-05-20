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
	register_block_type(__DIR__ . '/build/prostudiome-banner-slider');
}
add_action('init', 'prostudiome_blocks_init');

/**
 * Enqueue the embla-carousel script and styles
 */
function prostudiome_enqueue_scripts() {
	// Enqueue Embla Carousel core script
	wp_enqueue_script(
		'embla-carousel',
		'https://unpkg.com/embla-carousel/embla-carousel.umd.js',
		array(),
		'8.0.0',
		true
	);

	// Register and enqueue our custom script that initializes the slider
	wp_enqueue_script(
		'prostudiome-banner-slider',
		plugins_url('build/prostudiome-banner-slider/view.js', __FILE__),
		array('embla-carousel'),
		'1.0.0',
		true
	);
}
add_action('wp_enqueue_scripts', 'prostudiome_enqueue_scripts');