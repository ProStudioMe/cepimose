<?php

/**
 * ZoloBlocks Pro Enqueues.
 */

namespace ZoloPro\Extensions;

use ZoloPro\Helpers\ZoloProHelpers;
use ZoloPro\Traits\SingletonTrait;

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

class Tilt
{

	use SingletonTrait;

	public function __construct()
	{
		if (ZoloProHelpers::is_extension_enabled('tilt')) {
			$this->register_tilt_assets();
            if(!is_admin()){
                add_filter("render_block_data", [$this, "enqueue_tilt_frontend_assets"]);
            }else{
				add_filter("block_type_metadata", [$this, "add_tilt_editor_script"]);
			}
		}
	}

	public function register_tilt_assets()
	{
		$editor_assets_path = ZOLO_PRO_DIR_PATH . "build/extensions/tilt/index.asset.php";
		$frontend_assets_path = ZOLO_PRO_DIR_PATH . "build/extensions/tilt/frontend.asset.php";
		if (file_exists($editor_assets_path)) {
			$editor_assets = include $editor_assets_path;
			wp_register_script(
				'zolo-tilt-editor-script',
				ZOLO_PRO_ADMIN_URL . 'build/extensions/tilt/index.js',
				$editor_assets['dependencies'],
				$editor_assets['version'],
				true
			);
		}

		if (file_exists($frontend_assets_path)) {
			$frontend_assets = include $frontend_assets_path;
			wp_register_script(
				'zolo-tilt-frontend-script',
				ZOLO_PRO_ADMIN_URL . 'build/extensions/tilt/frontend.js',
				$frontend_assets['dependencies'],
				$frontend_assets['version'],
				true
			);
		}
	}

    public function add_tilt_editor_script($block){
		if ( isset($block['name']) && str_contains($block['name'], 'zolo/')) {
			$existing_scripts = isset($block['script']) ? (is_string($block['script']) ? [$block['script']]: $block['script']): [];
			$block['script'] = $existing_scripts;
			if(!wp_script_is('tilt', 'enqueued')){
				$block['script'] = array_merge($existing_scripts, [ 'zolo-tilt-editor-script', 'tilt' ]);
			}
		}

		return $block;
	}

    public function enqueue_tilt_frontend_assets($parsed_block)
    {
        if ( isset($parsed_block['blockName']) && str_contains($parsed_block['blockName'], 'zolo/') && !empty($parsed_block['attrs']['zoloTilt']['active'])) {
            wp_enqueue_script('tilt');
            wp_enqueue_script('zolo-tilt-frontend-script');
        }
        return $parsed_block;
    }
}