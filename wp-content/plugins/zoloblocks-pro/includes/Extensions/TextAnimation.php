<?php

/**
 * ZoloBlocks Pro Enqueues.
 */

namespace ZoloPro\Extensions;


use ZoloPro\Helpers\ZoloProHelpers;
use ZoloPro\Traits\SingletonTrait;

// Exit if accessed directly.
if (!defined('ABSPATH')) exit;

class TextAnimation {

    use SingletonTrait;

    public function __construct() {
        if (ZoloProHelpers::is_extension_enabled('text-animation')) {
            add_action("enqueue_block_assets", [$this, "enqueue_advanced_heading_editor_assets"]);
            add_filter("render_block_data", [$this, "enqueue_text_animation_frontend_assets"]);
        }
    }

    public function enqueue_advanced_heading_editor_assets() {
        $editor_assets_path = ZOLO_PRO_DIR_PATH . "build/extensions/text-animation/index.asset.php";
        $frontend_assets_path = ZOLO_PRO_DIR_PATH . "build/extensions/text-animation/frontend.asset.php";
        if (file_exists($editor_assets_path) && is_admin()) {
            $editor_assets = include $editor_assets_path;

            wp_register_script(
                'zolo-text-animation-editor-script',
                ZOLO_PRO_ADMIN_URL . 'build/extensions/text-animation/index.js',
                $editor_assets['dependencies'],
                $editor_assets['version'],
                true
            );
            wp_enqueue_script('spiltText');
            wp_enqueue_script('zolo-text-animation-editor-script');
        }
        if (file_exists($frontend_assets_path) && !is_admin()) {
            $frontend_assets = include $frontend_assets_path;
            //merge with the existing dependencies
            $frontend_assets['dependencies'] = array_merge($frontend_assets['dependencies'], ['gsap', 'spiltText', 'scrolltrigger']);
            wp_register_script(
                'zolo-text-animation-frontend-script',
                ZOLO_PRO_ADMIN_URL . 'build/extensions/text-animation/frontend.js',
                $frontend_assets['dependencies'],
                $frontend_assets['version'],
                true
            );
        }
    }

    public function enqueue_text_animation_frontend_assets($block) {
        if (
            isset($block['blockName']) &&
            $block['blockName'] === 'zolo/advanced-heading' &&
            !empty($block['attrs']['splitTextActive']) &&
            !is_admin()
        ) {
            wp_enqueue_script('zolo-text-animation-frontend-script');
        }
        return $block;
    }
}
