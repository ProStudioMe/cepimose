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

class Interactions {

	use SingletonTrait;

	public function __construct() {
		if (ZoloProHelpers::is_extension_enabled('interactions')) {
			add_action("enqueue_block_assets", [$this, "register_interactions_assets"], 10);
			add_filter("block_type_metadata_settings", [$this, "interactions_editor_settings"], 10, 2);
			add_filter("render_block_data", [$this, "enqueue_interactions_assets"]);
			add_filter("render_block", [$this, "interactions_data_attributes"], 10, 2);
		}
	}

	public function register_interactions_assets() {
		$editor_assets_path = ZOLO_PRO_DIR_PATH . "build/extensions/interactions/index.asset.php";
		$frontend_assets_path = ZOLO_PRO_DIR_PATH . "build/extensions/interactions/frontend.asset.php";
		if (file_exists($editor_assets_path)) {
			$editor_assets = include $editor_assets_path;
			wp_register_script(
				'zolo-interactions-editor-script',
				ZOLO_PRO_ADMIN_URL . 'build/extensions/interactions/index.js',
				$editor_assets['dependencies'],
				$editor_assets['version'],
				true
			);
		}

		if (file_exists($frontend_assets_path) && !is_admin()) {
			$frontend_assets = include $frontend_assets_path;
			wp_register_script(
				'zolo-interactions-frontend-script',
				ZOLO_PRO_ADMIN_URL . 'build/extensions/interactions/frontend.js',
				$frontend_assets['dependencies'],
				$frontend_assets['version'],
				true
			);
		}
	}

	public function interactions_editor_settings($settings, $metadata) {
		if (isset($metadata["category"]) && $metadata["category"] === "zoloblocks") {
			if (empty($settings["editor_script_handles"])) {
				$settings["editor_script_handles"] = [];
			}

			$settings["editor_script_handles"][] = "zolo-interactions-editor-script";
		}

		return $settings;
	}

	public function enqueue_interactions_assets($block) {
		if (isset($block['blockName']) && str_contains($block['blockName'], 'zolo/') && !empty($block['attrs']['interactions'])) {
			$interactions = $this->validate_interactions($block['attrs']['interactions']);
			if (!empty($interactions)) {
				$settings = json_encode($interactions);
				if (
					str_contains($settings, 'show') || str_contains($settings, 'hide') || str_contains($settings, 'toggle') || str_contains($settings, 'scrollTo') ||
					str_contains($settings, 'scaleX') || str_contains($settings, 'scaleY') || str_contains($settings, 'rotateX') || str_contains($settings, 'rotateY') || str_contains($settings, 'rotateZ') || str_contains($settings, 'translateX') ||
					str_contains($settings, 'translateY') || str_contains($settings, 'translateZ') || str_contains($settings, 'skewX') || str_contains($settings, 'skewY') || str_contains($settings, 'opacity')
				) {
					wp_enqueue_script('gsap');
				}

				if (str_contains($settings, 'startAnimation')) {
					wp_enqueue_style('animate');
				}

				if (str_contains($settings, 'scrollTo')) {
					wp_enqueue_script('scrollTo');
				}


				wp_enqueue_script('zolo-interactions-frontend-script');
			}
		}

		return $block;
	}

	private function validate_interactions($interactions) {
		$results = [];

		if (!is_array($interactions)) return $results;

		foreach ($interactions as $value) {
			if (empty($value["trigger"]["event"])) continue;

			if (empty($value["action"]["action"])) continue;

			$results[] = $value;
		}

		return $results;
	}

	public function interactions_data_attributes($block_content, $block) {
		if (isset($block['blockName']) && str_contains($block['blockName'], 'zolo/') && !empty($block['attrs']['interactions'])) {
			$interactions = $this->validate_interactions($block['attrs']['interactions']);
			if (!empty($interactions)) {
				$tags = new \WP_HTML_Tag_Processor($block_content);
				$tags->next_tag();
				$tags->set_attribute('data-interactions', wp_json_encode($interactions));
				$tags->add_class('has-zolo-interactions');
				$block_content = $tags->get_updated_html();
			}
		}

		return $block_content;
	}
}
