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

class Cursors {

	use SingletonTrait;

	public function __construct() {
		if (ZoloProHelpers::is_extension_enabled('cursors')) {
			$this->register_cursors_assets();
			if (is_admin()) {
				add_filter("enqueue_block_assets", [$this, "enqueue_cursors_editor_assets"]);
			} else {
				add_filter("render_block_data", [$this, "enqueue_cursors_frontend_assets"]);
			}
		}
	}

	public function register_cursors_assets() {
		$editor_assets_path = ZOLO_PRO_DIR_PATH . "build/extensions/cursors/index.asset.php";
		$frontend_assets_path = ZOLO_PRO_DIR_PATH . "build/extensions/cursors/frontend.asset.php";
		if (file_exists($editor_assets_path)) {
			$editor_assets = include $editor_assets_path;
			wp_register_script(
				'zolo-cursors-editor-script',
				ZOLO_PRO_ADMIN_URL . 'build/extensions/cursors/index.js',
				$editor_assets['dependencies'],
				$editor_assets['version'],
				true
			);
			wp_register_style(
				'zolo-cursors-style',
				ZOLO_PRO_ADMIN_URL . 'build/extensions/cursors/style-index.css',
				[],
				$editor_assets['version'],
				'all'
			);
		}

		if (file_exists($frontend_assets_path)) {
			$frontend_assets = include $frontend_assets_path;
			wp_register_script(
				'zolo-cursors-frontend-script',
				ZOLO_PRO_ADMIN_URL . 'build/extensions/cursors/frontend.js',
				$frontend_assets['dependencies'],
				$frontend_assets['version'],
				true
			);
		}
	}

	public function enqueue_cursors_editor_assets() {
		wp_enqueue_style('zolo-cursors-style');
		wp_enqueue_script('cottonjs');
		wp_enqueue_script('zolo-cursors-editor-script');
	}

	public function enqueue_cursors_frontend_assets($parsed_block) {

		if (isset($parsed_block['blockName']) && str_contains($parsed_block['blockName'], 'zolo/') && !empty($parsed_block['attrs']['zoloCursors']['active'])) {
			wp_enqueue_script('cottonjs');
			wp_enqueue_script('zolo-cursors-frontend-script');
			wp_enqueue_style('zolo-cursors-style');
		}
		return $parsed_block;
	}
}
