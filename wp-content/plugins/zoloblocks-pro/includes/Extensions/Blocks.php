<?php

/**
 * ZoloBlocks Pro Enqueues.
 */

namespace ZoloPro\Extensions;


use ZoloPro\Helpers\ZoloProHelpers;
use ZoloPro\Traits\SingletonTrait;

// Exit if accessed directly.
if (!defined('ABSPATH')) exit;

class Blocks {

    use SingletonTrait;

    public function __construct() {
        if (is_admin()) {
            add_action("enqueue_block_assets", [$this, "enqueue_blocks_editor_assets"]);
        }
    }

    public function enqueue_blocks_editor_assets() {

        $editor_assets_path = ZOLO_PRO_DIR_PATH . "build/extensions/blocks/index.asset.php";
        if (file_exists($editor_assets_path)) {
            $editor_assets = include $editor_assets_path;
            wp_register_script(
                'zolo-blocks-editor-script',
                ZOLO_PRO_ADMIN_URL . 'build/extensions/blocks/index.js',
                $editor_assets['dependencies'],
                $editor_assets['version'],
                true
            );
            wp_enqueue_script('zolo-blocks-editor-script');
        }
    }
}
