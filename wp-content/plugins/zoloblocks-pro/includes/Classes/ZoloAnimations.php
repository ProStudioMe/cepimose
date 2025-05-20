<?php

/**
 * ZoloBlocks Pro Animations.
 */

namespace ZoloPro\Classes;

use ZoloPro\Traits\SingletonTrait;

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('ZoloAnimations')) {

	/**
	 * Class ZoloAnimations.
	 *
	 * @since 1.0.0
	 */
	class ZoloAnimations {

		use SingletonTrait;

		/**
		 * Constructor
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function __construct() {
			add_filter('render_block', [$this, 'add_floating_animation'], 10, 2);
			add_filter('render_block', [$this, 'add_entrance_animation'], 10, 2);
			add_filter('render_block', [$this, 'add_parallax_animation'], 10, 2);
			add_filter('render_block', [$this, 'add_sticky_animation'], 10, 2);
			add_filter('render_block', [$this, 'add_heading_animation'], 10, 2);
			add_filter('render_block', [$this, 'cursors_effects'], 10, 2);
			add_filter('render_block', [$this, 'tilt_effects'], 10, 2);
		}

		/**
		 * Add Sticky Animation
		 *
		 * @since 0.0.1
		 *
		 * @return array
		 */
		public function add_sticky_animation($block_content, $block) {
			if (isset($block['blockName']) && str_contains($block['blockName'], 'zolo/')) {

				$animationActive = $block['attrs']['isSticky'] ?? false;
				if ($animationActive) {
					$stickyAnimation = $block['attrs']['stickyAnimation'] ?? [
						'position' => 'top',
						'offset' => 0,
						'zIndex' => 1000,
					];

					// Convert the floating animation to JSON string
					$stickyAnimation = wp_json_encode($stickyAnimation);

					if (!empty($stickyAnimation)) {
						// Parse the block content as HTML
						$dom = new \DOMDocument();
						@$dom->loadHTML($block_content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

						// Retrieve the outermost div
						$outerDiv = $dom->firstChild;

						if ($outerDiv) {
							// Retrieve existing class attribute
							$existingClasses = $outerDiv->getAttribute('class');

							// Add the animation attribute
							$outerDiv->setAttribute('data-sticky', $stickyAnimation);

							// Restore existing classes
							if (!empty($existingClasses)) {
								$outerDiv->setAttribute('class', $existingClasses);
							}

							// Save the modified HTML
							$block_content = $dom->saveHTML();
						}
					}
				}
			}

			return $block_content;
		}
		/**
		 * Add Entrance Animation
		 *
		 * @since 0.0.1
		 *
		 * @return array
		 */
		public function add_floating_animation($block_content, $block) {
			if (isset($block['blockName']) && str_contains($block['blockName'], 'zolo/')) {

				$animationActive = $block['attrs']['floatingAnimationActive'] ?? false;
				if ($animationActive) {
					$floatingAnimation = $block['attrs']['floatingAnimation'] ?? [
						'translateX' => [
							'minValue' => -100,
							'maxValue' => 100,
							'unit' => 'px',
						],
						'translateY' => [
							'minValue' => 0,
							'maxValue' => 0,
							'unit' => 'px',
						],
						'translateZ' => [
							'minValue' => 0,
							'maxValue' => 0,
							'unit' => 'px',
						],
						'rotateX' => [
							'minValue' => 0,
							'maxValue' => 0,
							'unit' => 'deg',
						],
						'rotateY' => [
							'minValue' => 0,
							'maxValue' => 0,
							'unit' => 'deg',
						],
						'rotateZ' => [
							'minValue' => 0,
							'maxValue' => 0,
							'unit' => 'deg',
						],
						'scaleX' => [
							'minValue' => 0,
							'maxValue' => 0,
							'unit' => '',
						],
						'scaleY' => [
							'minValue' => 0,
							'maxValue' => 0,
							'unit' => '',
						],
						'scaleZ' => [
							'minValue' => 0,
							'maxValue' => 0,
							'unit' => '',
						],
						'skewX' => [
							'minValue' => 0,
							'maxValue' => 0,
							'unit' => 'deg',
						],
						'skewY' => [
							'minValue' => 0,
							'maxValue' => 0,
							'unit' => 'deg',
						],
						'opacity' => [
							'minValue' => 1,
							'maxValue' => 1,
							'unit' => '',
						],
						'easing' => 'ease-out',
						'easingCustom' => '',
						'repeat' => true,
						'perspective' => 1000,
						'duration' => 3000,
						'delay' => 0,
						'transformOrigin' => 'center',
						'presetAnimation' => 'bottomMedium',
						'transformOriginCustom' => '',


					];

					// Convert the floating animation to JSON string
					$floatingAnimation = wp_json_encode($floatingAnimation);

					if (!empty($floatingAnimation)) {
						// Parse the block content as HTML
						$dom = new \DOMDocument();
						@$dom->loadHTML($block_content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

						// Retrieve the outermost div
						$outerDiv = $dom->getElementsByTagName('div')->item(0);

						if ($outerDiv) {
							// Retrieve existing class attribute
							$existingClasses = $outerDiv->getAttribute('class');

							// Add the animation attribute
							$outerDiv->setAttribute('data-floating', $floatingAnimation);

							// Restore existing classes
							if (!empty($existingClasses)) {
								$outerDiv->setAttribute('class', $existingClasses);
							}

							// Save the modified HTML
							$block_content = $dom->saveHTML();
						}
					}
				}
			}

			return $block_content;
		}

		/**
		 * Add Entrance Animation
		 *
		 * @since 0.0.1
		 *
		 * @return array
		 */
		public function add_entrance_animation($block_content, $block) {
			if (isset($block['blockName']) && str_contains($block['blockName'], 'zolo/')) {

				$animationActive = isset($block['attrs']['entranceAnimationActive']) && $block['attrs']['entranceAnimationActive'] === true;
				if ($animationActive) {
					$entranceAnimation = $block['attrs']['entranceAnimation'] ?? [
						'translateX' => ['value' => 0, 'unit' => 'px'],
						'translateY' => ['value' => 50, 'unit' => 'px'],
						'translateZ' => ['value' => 0, 'unit' => 'px'],
						'rotateX' => ['value' => 0, 'unit' => 'deg'],
						'rotateY' => ['value' => 0, 'unit' => 'deg'],
						'rotateZ' => ['value' => 0, 'unit' => 'deg'],
						'scaleX' => ['value' => 1, 'unit' => ''],
						'scaleY' => ['value' => 1, 'unit' => ''],
						'scaleZ' => ['value' => 1, 'unit' => ''],
						'skewX' => ['value' => 0, 'unit' => 'deg'],
						'skewY' => ['value' => 0, 'unit' => 'deg'],
						'opacity' => 1,
						'easing' => 'power4.out',
						'easingCustom' => '',
						'repeat' => false,
						'perspective' => 1000,
						'duration' => 1800,
						'delay' => 180,
						'transformOrigin' => 'center',
						'presetAnimation' => 'bottomMedium',
						'transformOriginCustom' => '',
						'repeatable' => false,

					];

					// Convert the entrance animation to JSON string
					// Convert the entrance animation to JSON string
					$entranceAnimation = wp_json_encode($entranceAnimation);

					if (!empty($entranceAnimation)) {
						// Parse the block content as HTML
						$dom = new \DOMDocument();
						// Use explicit error handling to prevent warnings from causing issues
						libxml_use_internal_errors(true);
						$dom->loadHTML($block_content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

						// Retrieve the first div or the outermost element
						$outerDiv = $dom->firstChild;
						if ($outerDiv) {
							// Retrieve existing class attribute
							$existingClasses = $outerDiv->getAttribute('class');

							// Add the animation attribute
							$outerDiv->setAttribute('data-animation', $entranceAnimation);
							// Restore existing classes
							if (!empty($existingClasses)) {
								$outerDiv->setAttribute('class', $existingClasses);
							}

							// Save the modified HTML
							// Use saveHTML() with the specified node to avoid getting unwanted doctype/html/body tags
							$block_content = $dom->saveHTML($dom->documentElement);
						}
					}

					// Clean up any libxml errors
					libxml_clear_errors();
				}
			}

			return $block_content;
		}
		/**
		 * Add Heading Animation
		 *
		 * @since 0.0.1
		 *
		 * @return array
		 */
		public function add_heading_animation($block_content, $block) {
			if (isset($block['blockName']) && str_contains($block['blockName'], 'zolo/')) {

				$animationActive = $block['attrs']['splitTextActive'] ?? false;
				if ($animationActive) {
					$headingAnimation = $block['attrs']['headingAnimation'] ?? [
						'translateX' => ['value' => 0, 'unit' => 'px'],
						'translateY' => ['value' => 0, 'unit' => 'px'],
						'translateZ' => ['value' => 0, 'unit' => 'px'],
						'rotateX' => ['value' => 0, 'unit' => 'deg'],
						'rotateY' => ['value' => 0, 'unit' => 'deg'],
						'rotateZ' => ['value' => 0, 'unit' => 'deg'],
						'scaleX' => ['value' => 1, 'unit' => ''],
						'scaleY' => ['value' => 1, 'unit' => ''],
						'scaleZ' => ['value' => 1, 'unit' => ''],
						'skewX' => ['value' => 0, 'unit' => 'deg'],
						'skewY' => ['value' => 0, 'unit' => 'deg'],
						'rotae3dActive' => false,
						'opacity' => 0,
						'easing' => 'back',
						'easingCustom' => '',
						'repeat' => false,
						'perspective' => 400,
						'duration' => 800,
						'delay' => 0,
						'transformOrigin' => 'center',
						'presetAnimation' => 'bottomMedium',
						'transformOriginCustom' => '',
						'stagger' => 100,
						'splitType' => 'words',
						'animationType' => 'maskedSlideUp'

					];

					// Convert the heading animation to JSON string
					// Convert the heading animation to JSON string
					$headingAnimation = wp_json_encode($headingAnimation);

					if (!empty($headingAnimation)) {
						// Parse the block content as HTML
						$dom = new \DOMDocument();
						// Use explicit error handling to prevent warnings from causing issues
						libxml_use_internal_errors(true);
						$dom->loadHTML($block_content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

						// Retrieve the first div or the outermost element
						$outerDiv = $dom->firstChild;
						if ($outerDiv) {
							// Retrieve existing class attribute
							$existingClasses = $outerDiv->getAttribute('class');

							// Add the animation attribute
							$outerDiv->setAttribute('data-spilttext', $headingAnimation);
							// Restore existing classes
							if (!empty($existingClasses)) {
								$outerDiv->setAttribute('class', $existingClasses);
							}

							// Save the modified HTML
							// Use saveHTML() with the specified node to avoid getting unwanted doctype/html/body tags
							$block_content = $dom->saveHTML($dom->documentElement);
						}
					}

					// Clean up any libxml errors
					libxml_clear_errors();
				}
			}

			return $block_content;
		}

		/**
		 * Add Entrance Animation
		 *
		 * @since 0.0.1
		 *
		 * @return array
		 */
		public function add_parallax_animation($block_content, $block) {
			if (isset($block['blockName']) && str_contains($block['blockName'], 'zolo/')) {

				$animationActive = $block['attrs']['parallaxAnimationActive'] ?? false;
				if ($animationActive) {
					$parallaxAnimation = $block['attrs']['parallaxAnimation'] ?? [
						'vertical' => [
							'direction' => 'top',
							'speed' => 100,
							'viewport' => [
								'minValue' => 20,
								'maxValue' => 80,
								'unit' => '%',
							]
						],
					];

					// Convert the entrance animation to JSON string
					$parallaxAnimation = wp_json_encode($parallaxAnimation);

					if (!empty($parallaxAnimation)) {
						// Parse the block content as HTML
						$dom = new \DOMDocument();
						// Use explicit error handling to prevent warnings from causing issues
						libxml_use_internal_errors(true);
						$dom->loadHTML($block_content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

						// Retrieve the first div or the outermost element
						$outerDiv = $dom->firstChild;
						if ($outerDiv) {
							// Retrieve existing class attribute
							$existingClasses = $outerDiv->getAttribute('class');

							// Add the animation attribute
							$outerDiv->setAttribute('data-parallax', $parallaxAnimation);
							// Restore existing classes
							if (!empty($existingClasses)) {
								$outerDiv->setAttribute('class', $existingClasses);
							}

							// Save the modified HTML
							// Use saveHTML() with the specified node to avoid getting unwanted doctype/html/body tags
							$block_content = $dom->saveHTML($dom->documentElement);
						}
					}

					// Clean up any libxml errors
					libxml_clear_errors();
				}
			}

			return $block_content;
		}

		/**
		 * Add Entrance Animation
		 *
		 * @since 1.2.2
		 *
		 * @return array
		 */
		public function cursors_effects($block_content, $block) {
			if (isset($block['blockName']) && str_contains($block['blockName'], 'zolo/')) {

				$zoloCursors = $block['attrs']['zoloCursors']['active'] ?? false;
				if ($zoloCursors) {
					$cursorOptions = $block['attrs']['zoloCursors'] ?? [
						'active' => true,
						'source' => 'default',
						'preset' => 'style-1',
						'disabledDefault' => false,
						'speed' => 400,
						'textLabel' => 'Click Me',
					];

					// Convert the heading animation to JSON string
					// Convert the heading animation to JSON string
					$cursorOptions = wp_json_encode($cursorOptions);

					if (!empty($cursorOptions)) {
						// Parse the block content as HTML
						$dom = new \DOMDocument();
						// Use explicit error handling to prevent warnings from causing issues
						libxml_use_internal_errors(true);
						$dom->loadHTML($block_content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

						// Retrieve the first div or the outermost element
						$outerDiv = $dom->firstChild;
						if ($outerDiv) {
							// Retrieve existing class attribute
							$existingClasses = $outerDiv->getAttribute('class');

							// Add the animation attribute
							$outerDiv->setAttribute('data-cursors', $cursorOptions);
							// Restore existing classes
							if (!empty($existingClasses)) {
								$outerDiv->setAttribute('class', $existingClasses);
							}

							// Save the modified HTML
							// Use saveHTML() with the specified node to avoid getting unwanted doctype/html/body tags
							$block_content = $dom->saveHTML($dom->documentElement);
						}
					}

					// Clean up any libxml errors
					libxml_clear_errors();
				}
			}

			return $block_content;
		}
		/**
		 * Add Entrance Animation
		 *
		 * @since 1.2.2
		 *
		 * @return array
		 */
		public function tilt_effects($block_content, $block) {
			if (isset($block['blockName']) && str_contains($block['blockName'], 'zolo/')) {

				$zoloTilt = $block['attrs']['zoloTilt']['active'] ?? false;
				if ($zoloTilt) {
					$tiltOptions = $block['attrs']['zoloTilt'] ?? [];

					// Convert the heading animation to JSON string
					// Convert the heading animation to JSON string
					$tiltOptions = wp_json_encode($tiltOptions);

					if (!empty($tiltOptions)) {
						// Parse the block content as HTML
						$dom = new \DOMDocument();
						// Use explicit error handling to prevent warnings from causing issues
						libxml_use_internal_errors(true);
						$dom->loadHTML($block_content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

						// Retrieve the first div or the outermost element
						$outerDiv = $dom->firstChild;
						if ($outerDiv) {
							// Retrieve existing class attribute
							$existingClasses = $outerDiv->getAttribute('class');

							// Add the animation attribute
							$outerDiv->setAttribute('data-zolo-tilt', $tiltOptions);
							// Restore existing classes
							if (!empty($existingClasses)) {
								$outerDiv->setAttribute('class', $existingClasses);
							}

							// Save the modified HTML
							// Use saveHTML() with the specified node to avoid getting unwanted doctype/html/body tags
							$block_content = $dom->saveHTML($dom->documentElement);
						}
					}

					// Clean up any libxml errors
					libxml_clear_errors();
				}
			}

			return $block_content;
		}
	}
}
