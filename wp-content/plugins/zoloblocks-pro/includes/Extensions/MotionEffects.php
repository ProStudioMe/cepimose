<?php

/**
 * ZoloBlocks Pro Enqueues.
 */

namespace ZoloPro\Extensions;

use ZoloPro\Helpers\ZoloProHelpers;
use ZoloPro\Traits\SingletonTrait;

// Exit if accessed directly.
if (!defined('ABSPATH')) exit;

class MotionEffects
{

	use SingletonTrait;
    public $extensions = [
        'entrance',
        'floating',
        'parallax',
        'sticky',
    ];

	public function __construct()
	{
        $this->register_motion_effects_assets();
        add_action("enqueue_block_editor_assets", [$this, "enqueue_motion_effects_editor_assets"]);
        if(!is_admin()){
            add_filter("render_block_data", [$this, "enqueue_motion_effects_frontend_assets"]);
        }else{
            add_filter("block_type_metadata", [$this, "add_motion_effects_editor_assets"]);
        }
	}

	public function register_motion_effects_assets()
	{
        $motion_effects_assets_path = ZOLO_PRO_DIR_PATH . "build/extensions/motion-effects/index.asset.php";
        if (file_exists($motion_effects_assets_path)) {
            $motion_effects_assets = include $motion_effects_assets_path;
            wp_register_script(
                'zolo-motion-effects-editor-script',
                trailingslashit(ZOLO_PRO_ADMIN_URL) . "build/extensions/motion-effects/index.js",
                $motion_effects_assets['dependencies'],
                $motion_effects_assets['version'],
                true
            );
        }
        foreach ($this->extensions as $extension) {
            $frontend_assets_path = ZOLO_PRO_DIR_PATH . "build/extensions/motion-effects/$extension/index.asset.php";

            if (file_exists($frontend_assets_path)) {
                $frontend_assets = include $frontend_assets_path;
                $dependencies = array_merge($frontend_assets['dependencies'], ['gsap', 'scrolltrigger', 'CustomEase']);
                wp_register_script(
                    "zolo-motion-effects-$extension-frontend-script",
                    ZOLO_PRO_ADMIN_URL . "build/extensions/motion-effects/$extension/frontend.js",
                    $dependencies,
                    $frontend_assets['version'],
                    true
                );
            }
        }
	}

    public function add_motion_effects_editor_assets($metadata){
        if(isset($metadata['name']) && str_contains($metadata['name'], 'zolo/')) {
            $scripts = isset($metadata['script']) ? (is_string($metadata['script']) ? [$metadata['script']] : $metadata['script']) : [];
            if(!wp_script_is('gsap')){
                $metadata['script'] = array_merge($scripts, ["gsap"]);
            }

            if(!wp_script_is('scrolltrigger')){
                $metadata['script'] = array_merge($scripts, ["scrolltrigger"]);
            }

            if(!wp_script_is('CustomEase')){
                $metadata['script'] = array_merge($scripts, ["CustomEase"]);
            }
        }

        return $metadata;
    }

    public function enqueue_motion_effects_editor_assets()
    {
        wp_enqueue_script("zolo-motion-effects-editor-script");
    }

    public function enqueue_motion_effects_frontend_assets($parsed_block)
    {
        if(isset($parsed_block['blockName']) && str_contains($parsed_block['blockName'], 'zolo/')) {
            foreach ($this->extensions as $extension) {
                if(ZoloProHelpers::is_extension_enabled($extension)){
                    $extension_attrs_active_key = $extension . 'AnimationActive';
                    if ($extension === 'sticky') {
                        $extension_attrs_active_key = 'isSticky';
                    }

                    if (!empty($parsed_block['attrs'][$extension_attrs_active_key])) {
                        wp_enqueue_script("zolo-motion-effects-$extension-frontend-script");
                    }
                }
            }
        }
        return $parsed_block;
    }
}
