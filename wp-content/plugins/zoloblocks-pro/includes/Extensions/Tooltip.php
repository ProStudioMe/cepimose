<?php

/**
 * ZoloBlocks Pro Enqueues.
 */

namespace ZoloPro\Extensions;

use ZoloPro\Helpers\ZoloProHelpers;
use ZoloPro\Traits\SingletonTrait;

// Exit if accessed directly.
if (!defined('ABSPATH')) exit;

class Tooltip {

    use SingletonTrait;

    public function __construct() {
        if (ZoloProHelpers::is_extension_enabled('tooltip')) {
            $this->register_tooltip_frontend_assets();
            add_filter("render_block", [$this, "enqueue_tooltip_frontend_assets"], 10, 2);
            if (is_admin()) {
                add_action("enqueue_block_assets", [$this, "enqueue_tooltip_editor_assets"]);
            }
        }
    }

    public function enqueue_tooltip_editor_assets() {
        $editor_assets_path = ZOLO_PRO_DIR_PATH . "build/extensions/tooltip/index.asset.php";
        if (file_exists($editor_assets_path)) {
            $editor_assets = include $editor_assets_path;
            wp_register_script(
                'zolo-tooltip-editor-script',
                ZOLO_PRO_ADMIN_URL . 'build/extensions/tooltip/index.js',
                $editor_assets['dependencies'],
                $editor_assets['version'],
                true
            );
            wp_register_style(
                'zolo-tooltip-editor-style',
                ZOLO_PRO_ADMIN_URL . 'build/extensions/tooltip/index.css',
                [],
                $editor_assets['version'],
                'all'
            );
            wp_enqueue_script('popper');
            wp_enqueue_script('tippy');
            wp_enqueue_script('zolo-tooltip-editor-script');
            wp_enqueue_style('zolo-tooltip-editor-style');
        }
    }

    public function register_tooltip_frontend_assets() {
        $frontend_assets_path = ZOLO_PRO_DIR_PATH . "build/extensions/tooltip/frontend.asset.php";
        if (file_exists($frontend_assets_path)) {
            $frontend_assets = include $frontend_assets_path;
            wp_register_script(
                'zolo-tooltip-frontend-script',
                ZOLO_PRO_ADMIN_URL . 'build/extensions/tooltip/frontend.js',
                $frontend_assets['dependencies'],
                $frontend_assets['version'],
                true
            );
        }
    }

    public function enqueue_tooltip_frontend_assets($block_content, $parsed_block) {
        if (str_contains($block_content, 'zolo-tooltip')) {
            wp_enqueue_style('tippy');
            wp_enqueue_script('popper');
            wp_enqueue_script('tippy');
            wp_enqueue_script('zolo-tooltip-frontend-script');
        }

        return $block_content;
    }
}
