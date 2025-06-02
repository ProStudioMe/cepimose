<?php

/**
 * ZoloBlocks Pro Enqueues.
 */

namespace ZoloPro\Extensions;

use ZoloPro\Helpers\ZoloProHelpers;
use ZoloPro\Traits\SingletonTrait;

// Exit if accessed directly.
if (!defined('ABSPATH')) exit;

class Highlight
{

	use SingletonTrait;

	public function __construct()
	{
		if (ZoloProHelpers::is_extension_enabled('highlight')) {
            add_action("enqueue_block_editor_assets", [$this, "enqueue_highlight_editor_assets"]);
		}
	}

    public function enqueue_highlight_editor_assets()
    {
        $editor_assets_path = ZOLO_PRO_DIR_PATH . "build/extensions/highlight/index.asset.php";
        if (file_exists($editor_assets_path)) {
			$editor_assets = include $editor_assets_path;
			wp_register_script(
				'zolo-highlight-editor-script',
				ZOLO_PRO_ADMIN_URL . 'build/extensions/highlight/index.js',
				$editor_assets['dependencies'],
				$editor_assets['version'],
				true
			);
            wp_register_style(
                'zolo-highlight-editor-style',
                ZOLO_PRO_ADMIN_URL . 'build/extensions/highlight/index.css',
                [],
                $editor_assets['version'],
                'all'
            );
		}
        wp_enqueue_script('zolo-highlight-editor-script');
        wp_enqueue_style('zolo-highlight-editor-style');
    }
}
