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
			'description'       => __('Displays content from the home page custom fields'),
			'render_template'   => plugin_dir_path(__FILE__) . 'src/prostudiome-home-info/render.php',
			'category'          => 'formatting',
			'icon'              => 'admin-comments',
			'keywords'          => array('home', 'info', 'content'),
			'supports'          => array(
				'align' => true,
				'mode' => false,
				'jsx' => false
			),
			'enqueue_style'     => plugins_url('build/prostudiome-home-info/style-index.css', __FILE__) . '?v=' . time(),
			'enqueue_assets'    => function(){
				wp_enqueue_style('prostudiome-home-info-style', plugins_url('build/prostudiome-home-info/style-index.css', __FILE__), array(), time());
			},
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
			'title' => 'Home Info Fields',
			'fields' => array(
				array(
					'key' => 'field_titel',
					'label' => 'Title',
					'name' => 'titel',
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
						'param' => 'post_slug',
						'operator' => '==',
						'value' => 'home-page-cepimose',
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
	// Debug: Log that this function is being called
	error_log('ProStudio.me: prostudiome_blocks_init() function called');
	
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

	// Register blocks with error handling
	$blocks_to_register = [
		'prostudiome-banner-swiper',
		'prostudiome-timeline',
		'prostudiome-ticker',
		'prostudio-same-category-posts'
		// Temporarily removed: 'prostudio-anchor-links', 'prostudio-test-copy'
	];

	foreach ($blocks_to_register as $block_dir) {
		$block_path = __DIR__ . '/build/' . $block_dir;
		
		// Debug: Always log what we're trying to register
		error_log('ProStudio.me: Attempting to register block: ' . $block_dir . ' from path: ' . $block_path);
		
		if (file_exists($block_path . '/block.json')) {
			error_log('ProStudio.me: block.json found for ' . $block_dir);
			
			// Try to register the block
			$result = register_block_type($block_path);
			
			if (is_wp_error($result)) {
				error_log('ProStudio.me: FAILED to register block ' . $block_dir . ': ' . $result->get_error_message());
			} else {
				error_log('ProStudio.me: SUCCESS - registered block ' . $block_dir . ' as ' . $result->name);
			}
		} else {
			error_log('ProStudio.me: ERROR - Block directory missing or no block.json found: ' . $block_path);
			
			// List what files actually exist
			if (is_dir($block_path)) {
				$files = scandir($block_path);
				error_log('ProStudio.me: Directory exists but files found: ' . implode(', ', $files));
			} else {
				error_log('ProStudio.me: Directory does not exist: ' . $block_path);
			}
		}
	}
	// Note: The home-info block is registered via ACF in prostudiome_register_acf_blocks()
	
	// Try direct registration of test copy block
	$test_block_path = __DIR__ . '/build/prostudio-test-copy';
	if (file_exists($test_block_path . '/block.json')) {
		error_log('ProStudio.me: Trying direct registration of test-copy block');
		$direct_result = register_block_type($test_block_path);
		if (is_wp_error($direct_result)) {
			error_log('ProStudio.me: DIRECT REGISTRATION FAILED: ' . $direct_result->get_error_message());
		} else {
			error_log('ProStudio.me: DIRECT REGISTRATION SUCCESS: ' . $direct_result->name);
		}
	}
	
	// Try direct registration of anchor-links block to see the error
	$anchor_block_path = __DIR__ . '/build/prostudio-anchor-links';
	if (file_exists($anchor_block_path . '/block.json')) {
		error_log('ProStudio.me: Trying direct registration of anchor-links block');
		$anchor_result = register_block_type($anchor_block_path);
		if (is_wp_error($anchor_result)) {
			error_log('ProStudio.me: ANCHOR LINKS REGISTRATION FAILED: ' . $anchor_result->get_error_message());
		} else {
			error_log('ProStudio.me: ANCHOR LINKS REGISTRATION SUCCESS: ' . $anchor_result->name);
		}
	} else {
		error_log('ProStudio.me: Anchor links block.json not found at: ' . $anchor_block_path);
	}

	// Register block styles
	$timeline_css_path = plugin_dir_path(__FILE__) . 'build/prostudiome-timeline/style-index.css';
	wp_register_style(
		'prostudiome-timeline-style',
		plugins_url('build/prostudiome-timeline/style-index.css', __FILE__),
		array('swiper'),
		file_exists($timeline_css_path) ? filemtime($timeline_css_path) : '1.0.0'
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
		// Enqueue Dashicons for frontend use
		wp_enqueue_style('dashicons');
	}

	// Always enqueue home-info styles to ensure they're available
	wp_enqueue_style(
		'prostudiome-home-info-style',
		plugins_url('src/prostudiome-home-info/style.css', __FILE__),
		array(),
		time() // Using current timestamp to force refresh
	);
}

/**
 * Simple admin notice to show registered blocks
 */
function prostudiome_show_blocks_notice() {
	$screen = get_current_screen();
	if ($screen && $screen->base === 'post' && current_user_can('manage_options')) {
		$registered_blocks = WP_Block_Type_Registry::get_instance()->get_all_registered();
		$prostudio_blocks = [];
		foreach ($registered_blocks as $name => $block) {
			if (strpos($name, 'prostudiome') !== false) {
				$prostudio_blocks[] = $name;
			}
		}
		
		echo '<div class="notice notice-info"><p>';
		echo '<strong>ProStudio Blocks:</strong> ';
		if (!empty($prostudio_blocks)) {
			echo implode(', ', $prostudio_blocks);
		} else {
			echo 'NONE FOUND!';
		}
		echo '</p></div>';
	}
}
add_action('admin_notices', 'prostudiome_show_blocks_notice');

add_action('wp_enqueue_scripts', 'prostudiome_enqueue_scripts');