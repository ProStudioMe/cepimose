<?php

/**
 * ZoloBlocks Pro Enqueues.
 */

namespace ZoloPro\Extensions;

use ZoloPro\Helpers\ZoloProHelpers;
use ZoloPro\Traits\SingletonTrait;

// Exit if accessed directly.
if (!defined('ABSPATH')) exit;

class CSSFilters
{

	use SingletonTrait;

	public function __construct()
	{
		if (ZoloProHelpers::is_extension_enabled('css-filters')) {
            add_action("enqueue_block_editor_assets", [$this, "enqueue_css_filters_editor_assets"]);
		}
	}

    public function enqueue_css_filters_editor_assets()
    {
        $editor_assets_path = ZOLO_PRO_DIR_PATH . "build/extensions/css-filters/index.asset.php";
        if (file_exists($editor_assets_path)) {
			$editor_assets = include $editor_assets_path;
			wp_register_script(
				'zolo-css-filters-editor-script',
				ZOLO_PRO_ADMIN_URL . 'build/extensions/css-filters/index.js',
				$editor_assets['dependencies'],
				$editor_assets['version'],
				true
			);
		}
        wp_enqueue_script('zolo-css-filters-editor-script');
    }
}
