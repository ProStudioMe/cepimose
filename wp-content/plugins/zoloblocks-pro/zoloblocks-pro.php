<?php

/**
 * Plugin Name: ZoloBlocks Pro
 * Plugin URI: https://bdthemes.com/
 * Version: 2.1.0
 * Author: BdThemes
 * Author URI: https://bdthemes.com/
 * Text Domain: zoloblocks-pro
 * Description: A collection of custom Gutenberg blocks to design your webpages with ease.
 * Domain Path: /languages
 * Requires at least: 6.1
 * Requires PHP: 7.4
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

use ZoloPro\Admin\Notices\Notices;

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

// Load WordPress includes
require_once(ABSPATH . 'wp-admin/includes/plugin.php');

// Composer Autoload.
require_once __DIR__ . '/vendor/autoload.php';

// Check if Zoloblocks free version is activated
if (!is_plugin_active('zoloblocks/zoloblocks.php')) {
    return;
}

if (!class_exists('Zolo_Blocks_Pro')) {

    /**
     * Main Zolo_Blocks_Pro Class
     *
     * @since 1.0.0
     * @return Zolo_Blocks_Pro
     */
    final class Zolo_Blocks_Pro {

        /**
         * Zolo_Blocks_Pro Instance
         *
         * @since 1.0.0
         * @var Zolo_Blocks_Pro
         * @access private
         */
        private static $instance;

        /**
         * Constructor
         *
         * @since 1.0.0
         * @return void
         */
        public function __construct() {
            // define constants.
            $this->constants();
            // admin notices
            $this->admin_notices();
            // Include required files.
            $this->includes();
        }

        /**
         * Define Constants
         *
         * @since 1.0.0
         * @return void
         */
        public function constants() {
            define('ZOLO_PRO_FILE', __FILE__);
            define('ZOLO_PRO_VERSION', '2.1.0');
            define('ZOLO_PRO_DIR_PATH', plugin_dir_path(ZOLO_PRO_FILE));
            define('ZOLO_PRO_ADMIN_URL', plugin_dir_url(ZOLO_PRO_FILE));
            define('ZOLO_PRO_PHP_VERSION', '7.0');
            define('ZOLO_PRO_WP_VERSION', '5.6');
        }

        /**
         * Admin Notices
         *
         * @since 1.0.0
         * @return void
         */
        public function admin_notices() {
            // check if ZoloBlocks is active or not.
            if (!is_plugin_active('zoloblocks/zoloblocks.php')) {
                add_action('admin_notices', [Notices::class, 'is_zolo_blocks_active']);
            }

            // php version check
            if (version_compare(phpversion(), ZOLO_PRO_PHP_VERSION, '<')) {
                add_action('admin_notices', [Notices::class, 'php_version_check']);
            }

            // wp version check
            if (version_compare(get_bloginfo('version'), ZOLO_PRO_WP_VERSION, '<')) {
                add_action('admin_notices', [Notices::class, 'wp_version_check']);
            }
        }

        /**
         * Include required files
         *
         * @since 1.0.0
         * @return void
         */
        public function includes() {
            require_once trailingslashit(ZOLO_PRO_DIR_PATH) . 'includes/zoloblocks-pro-loader.php';
        }

        /**
         * Plugin activation callback
         *
         * @since 1.0.0
         * @return void
         */
        public static function activate() {
        }


        /**
         * Plugin deactivation callback
         *
         * @since 1.0.0
         * @return void
         */
        public static function deactivate() {
        }
        /**
         * Get Instance
         *
         * @since 1.0.0
         * @return Zolo_Blocks_Pro
         */
        public static function get_instance() {
            if (!isset(self::$instance)) {
                self::$instance = new self();
            }
            return self::$instance;
        }
    }

    // Register activation and deactivation hooks.
    register_activation_hook(__FILE__, ['Zolo_Blocks_Pro', 'activate']);
    register_deactivation_hook(__FILE__, ['Zolo_Blocks_Pro', 'deactivate']);
}

/**
 * Initialize the Zolo_Blocks_Pro
 *
 * @since 1.0.0
 * @return Zolo_Blocks_Pro
 */
Zolo_Blocks_Pro::get_instance();
