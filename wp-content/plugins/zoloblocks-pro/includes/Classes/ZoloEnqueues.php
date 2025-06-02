<?php

/**
 * ZoloBlocks Pro Enqueues.
 */

namespace ZoloPro\Classes;

use ZoloPro\Traits\SingletonTrait;

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('ZoloEnqueues')) {

	/**
	 * Class ZoloEnqueues.
	 *
	 * @since 1.0.0
	 */
	class ZoloEnqueues {

		/**
		 * ZoloEnqueues Instance.
		 *
		 * @since 1.0.0
		 * @var ZoloEnqueues
		 * @access private
		 */
		use SingletonTrait;

		/**
		 * Constructor
		 */
		public function __construct() {
			$this->init();
		}

		/**
		 * Init Enqueues
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function init() {
			$this->register_block_assets();
			add_action('enqueue_block_editor_assets', [$this, 'editor_assets_loader']);
			add_action('enqueue_block_assets', array($this, 'enqueue_block_assets'));
			// enqueue inline css to hide block before animation
			add_action('wp_head', [$this, 'initial_css_loader']);
		}

		/**
		 * Load Inline CSS
		 *
		 * @since 0.0.1
		 *
		 * @return void
		 */
		public function initial_css_loader() {
			$custom_css = ".zolo-entrance-animation:not(.animation-initialized), .zolo-entrance-animation .zolo-post-item:not(.animation-initialized) { opacity: 0; }
                   .zolo-editor .zolo-entrance-animation:not(.animation-initialized), .zolo-editor .zolo-entrance-animation .zolo-post-item:not(.animation-initialized) { opacity: 1; }";
			if (!empty($custom_css)) {
				echo '<style id="zolo-init">' . esc_html($custom_css) . '</style>';
			}
		}

		/**
		 * Load Block Editor Assets
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function editor_assets_loader() {
			// Enqueue Editor Global Scripts
			wp_enqueue_script('zolo-pro-global-script');
		}
		/**
		 * Register Block Assets
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function register_block_assets() {
			$gb_file = trailingslashit(ZOLO_PRO_DIR_PATH) . "build/global/index.asset.php";
			if (file_exists($gb_file)) {
				$script_dependency = include $gb_file;
				wp_register_style('zolo-pro-global-style', trailingslashit(ZOLO_PRO_ADMIN_URL) . "build/global/index.css", [], $script_dependency['version'], 'all');
				wp_register_script('zolo-pro-global-script', trailingslashit(ZOLO_PRO_ADMIN_URL) . "build/global/index.js", $script_dependency['dependencies'], $script_dependency['version'], true);
			}

			// Register 3rd Party Scripts
			wp_register_script('gsap', trailingslashit(ZOLO_PRO_ADMIN_URL) . 'assets/js/gsap/gsap.min.js', [], ZOLO_PRO_VERSION, true);
			wp_register_script('scrolltrigger', trailingslashit(ZOLO_PRO_ADMIN_URL) . 'assets/js/gsap/ScrollTrigger.min.js', ['gsap'], ZOLO_PRO_VERSION, true);
			wp_register_script('spiltText', trailingslashit(ZOLO_PRO_ADMIN_URL) . 'assets/js/gsap/SplitText.min.js', ['gsap'], ZOLO_PRO_VERSION, true);
			wp_register_script('CustomEase', trailingslashit(ZOLO_PRO_ADMIN_URL) . 'assets/js/gsap/CustomEase.min.js', ['gsap'], ZOLO_PRO_VERSION, true);
			wp_register_script('scrollTo', trailingslashit(ZOLO_PRO_ADMIN_URL) . 'assets/js/gsap/ScrollToPlugin.min.js', ['gsap'], ZOLO_PRO_VERSION, true);
			wp_register_script('draggable', trailingslashit(ZOLO_PRO_ADMIN_URL) . 'assets/js/gsap/Draggable.min.js', ['gsap'], ZOLO_PRO_VERSION, true);
			wp_register_script('InertiaPlugin', trailingslashit(ZOLO_PRO_ADMIN_URL) . 'assets/js/gsap/InertiaPlugin.min.js', ['gsap'], ZOLO_PRO_VERSION, true);
			wp_register_script('popper', trailingslashit(ZOLO_PRO_ADMIN_URL) . 'assets/js/popper.min.js', [], ZOLO_PRO_VERSION, true);
			wp_register_script('tippy', trailingslashit(ZOLO_PRO_ADMIN_URL) . 'assets/js/tippy-bundle.min.js', [], ZOLO_PRO_VERSION, true);
			wp_register_script('lenis', trailingslashit(ZOLO_PRO_ADMIN_URL) . 'assets/js/lenis.min.js', did_action('elementor/loaded') ? ['underscore'] : [], ZOLO_PRO_VERSION, true);
			wp_register_script('tilt', trailingslashit(ZOLO_PRO_ADMIN_URL) . 'assets/js/tilt.js', [], ZOLO_PRO_VERSION, true);
			wp_register_script('cottonjs', trailingslashit(ZOLO_PRO_ADMIN_URL) . 'assets/js/cotton.min.js', [], '1.3.3', true);
			wp_register_script('simple-parallax', trailingslashit(ZOLO_PRO_ADMIN_URL) . 'assets/js/parallax.umd.js', [], ZOLO_PRO_VERSION, true);

			// Register 3rd Party Styles
			wp_register_style('tippy', trailingslashit(ZOLO_PRO_ADMIN_URL) . 'assets/css/tippy.min.css', [], ZOLO_PRO_VERSION);
			wp_register_style('animate', trailingslashit(ZOLO_PRO_ADMIN_URL) . 'assets/css/animate.css', [], ZOLO_PRO_VERSION);
		}

		public function enqueue_block_assets() {
			// Enqueue Block Assets for both Frontend and Editor
			wp_enqueue_style('zolo-pro-global-style');
		}
	}
}
