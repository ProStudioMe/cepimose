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
 * Register ACF Blocks
 */
function prostudiome_register_acf_blocks() {
	if (function_exists('acf_register_block_type')) {
		// Register Home Info Block
		acf_register_block_type(array(
			'name'              => 'home-info',
			'title'             => __('Home Page Info Block'),
			'description'       => __('A custom block for the home page with title, subtitle, info box, text, and background image'),
			'render_template'   => plugin_dir_path(__FILE__) . 'src/prostudiome-home-info/render.php',
			'category'          => 'formatting',
			'icon'              => 'admin-comments',
			'keywords'          => array('home', 'info', 'content'),
			'mode'              => 'edit',
			'supports'          => array(
				'align' => true
			),
			'enqueue_style'     => plugins_url('src/prostudiome-home-info/style.css', __FILE__),
		));
	}
}
add_action('acf/init', 'prostudiome_register_acf_blocks');

/**
 * Register ACF Fields
 */
function prostudiome_register_acf_fields() {
	if (function_exists('acf_add_local_field_group')) {
		acf_add_local_field_group(array(
			'key' => 'group_home_info',
			'title' => 'Home Info Block',
			'fields' => array(
				array(
					'key' => 'field_title',
					'label' => 'Title',
					'name' => 'title',
					'type' => 'text',
					'required' => 1,
				),
				array(
					'key' => 'field_subtitle',
					'label' => 'Subtitle',
					'name' => 'subtitle',
					'type' => 'text',
					'required' => 1,
				),
				array(
					'key' => 'field_info_box',
					'label' => 'Info Box',
					'name' => 'info_box',
					'type' => 'wysiwyg',
					'required' => 1,
				),
				array(
					'key' => 'field_text',
					'label' => 'Text',
					'name' => 'text',
					'type' => 'wysiwyg',
					'required' => 1,
				),
				array(
					'key' => 'field_background_image',
					'label' => 'Background Image',
					'name' => 'background_image',
					'type' => 'image',
					'required' => 1,
					'return_format' => 'array',
				),
				array(
					'key' => 'field_link',
					'label' => 'Button Link',
					'name' => 'link',
					'type' => 'link',
					'required' => 1,
					'return_format' => 'array',
				),
			),
			'location' => array(
				array(
					array(
						'param' => 'block',
						'operator' => '==',
						'value' => 'acf/home-info',
					),
				),
			),
			'menu_order' => 0,
			'position' => 'normal',
			'style' => 'default',
			'label_placement' => 'top',
			'instruction_placement' => 'label',
			'hide_on_screen' => '',
			'active' => true,
			'description' => '',
		));
	}
}
add_action('acf/init', 'prostudiome_register_acf_fields');

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

	// Register block styles
	wp_register_style(
		'prostudiome-timeline-style',
		plugins_url('build/prostudiome-timeline/style-index.css', __FILE__),
		array('swiper'),
		filemtime(plugin_dir_path(__FILE__) . 'build/prostudiome-timeline/style-index.css')
	);

	wp_register_style(
		'prostudiome-home-info-style',
		plugins_url('src/prostudiome-home-info/style.css', __FILE__),
		array(),
		filemtime(plugin_dir_path(__FILE__) . 'src/prostudiome-home-info/style.css')
	);
}
add_action('init', 'prostudiome_blocks_init');

/**
 * Enqueue scripts and styles for the frontend
 */
function prostudiome_enqueue_scripts() {
	if (has_block('prostudiome/banner-swiper') || has_block('prostudiome/timeline')) {
		wp_enqueue_style('swiper');
		wp_enqueue_script('swiper');
		wp_enqueue_style('prostudiome-timeline-style');
	}

	if (has_block('acf/home-info')) {
		wp_enqueue_style('prostudiome-home-info-style');
	}
}
add_action('wp_enqueue_scripts', 'prostudiome_enqueue_scripts');