<?php

/**
 * ZoloBlocks Pro Enqueues.
 */

namespace ZoloPro\Extensions;


use ZoloPro\Helpers\ZoloProHelpers;
use ZoloPro\Traits\SingletonTrait;

// Exit if accessed directly.
if (!defined('ABSPATH')) exit;

class SmoothScroller {

    use SingletonTrait;

    public function __construct() {
        if(ZoloProHelpers::is_extension_enabled('smooth-scroller')) {
            add_action("enqueue_block_assets", [$this, "register_smooth_scroller_assets"]);
            add_filter("render_block_data", [$this, "enqueue_smooth_scroller_frontend_assets"]);
        }
    }

    public function register_smooth_scroller_assets() {
        $frontend_assets_path = ZOLO_PRO_DIR_PATH . "build/extensions/smooth-scroller/frontend.asset.php";
        if (file_exists($frontend_assets_path)) {
            $frontend_assets = include $frontend_assets_path;
            wp_register_script(
                'zolo-smooth-scroller-frontend-script',
                ZOLO_PRO_ADMIN_URL . 'build/extensions/smooth-scroller/frontend.js',
                $frontend_assets['dependencies'],
                $frontend_assets['version'],
                true
            );
        }
    }

    public function enqueue_smooth_scroller_frontend_assets($block) {
        wp_enqueue_script('lenis');
        wp_enqueue_script('zolo-smooth-scroller-frontend-script');
        return $block;
    }
}
