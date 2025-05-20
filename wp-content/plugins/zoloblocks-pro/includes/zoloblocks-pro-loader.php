<?php

/**
 * ZoloBlocks Pro Loader.
 *
 * @package ZoloBlocksPro
 */

use ZoloPro\Classes\PostQuery;
use ZoloPro\Traits\SingletonTrait;
use ZoloPro\Classes\ZoloEnqueues;
use ZoloPro\Classes\ZoloAnimations;
use ZoloPro\Admin\Admin;
use ZoloPro\Hooks\Hooks;
use ZoloPro\Classes\ZoloAjax;
use ZoloPro\Extensions\ExtensionsLoader;

// Exit if accessed directly.
if (! defined('ABSPATH')) {
	exit;
}

if (! class_exists('Zolo_Blocks_Pro_Loader')) {

	/**
	 * Main Zolo_Blocks_Pro_Loader Class
	 *
	 * @since 1.0.0
	 * @return Zolo_Blocks_Pro_Loader
	 */
	class Zolo_Blocks_Pro_Loader {

		/**
		 * Zolo_Blocks_Pro_Loader Instance
		 *
		 * @since 1.0.0
		 * @var Zolo_Blocks_Pro_Loader
		 * @access private
		 * @static
		 */
		use SingletonTrait;

		/**
		 * Constructor
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function __construct() {
			$this->init();
			$this->includes();
		}

		/**
		 * Init ZoloBlocks Pro Plugin
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function init() {
			add_action('init', [$this, 'plugins_loaded']);
			// Load Post Query rest API.
			PostQuery::getInstance();
		}

		/**
		 * Loads plugin files.
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function plugins_loaded() {

			/**
			 * Hook: zolo_blocks_pro_before_loaded
			 * Type: Action
			 * Description: Fires before ZoloBlocks Pro Plugin is loaded.
			 */
			do_action('zolo_blocks_pro_before_loaded');

			// Load Admin.
			new Admin();

			// Load Enqueues.
			ZoloEnqueues::getInstance();

			// Load Animations.
			ZoloAnimations::getInstance();

			// Load Hooks.
			Hooks::getInstance();

			// Load ajax.
			ZoloAjax::getInstance();

			//Load ExtentionsLoader.
			ExtensionsLoader::getInstance();

			/**
			 * Hook: zolo_blocks_pro_after_loaded
			 * Type: Action
			 * Description: Fires after ZoloBlocks Pro Plugin is loaded.
			 */
			do_action('zolo_blocks_pro_after_loaded');
		}

		/**
		 * Include required files.
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function includes() {
			require_once trailingslashit(ZOLO_PRO_DIR_PATH) . 'includes/Classes/ZoloPreview.php';
		}
	}
}

/**
 * Initialize the Zolo_Blocks_Pro_Loader
 *
 * @since 1.0.0
 * @return Zolo_Blocks_Pro_Loader
 */
Zolo_Blocks_Pro_Loader::getInstance();
