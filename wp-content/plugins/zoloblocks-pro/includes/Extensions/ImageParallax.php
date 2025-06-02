<?php

/**
 * Zolo Blocks Pro Enqueues.
 */

namespace ZoloPro\Extensions;


use ZoloPro\Helpers\ZoloProHelpers;
use ZoloPro\Traits\SingletonTrait;

// Exit if accessed directly.
if (!defined('ABSPATH')) exit;

class ImageParallax {

    use SingletonTrait;

    public function __construct() {
        if (ZoloProHelpers::is_extension_enabled('image-parallax')) {
            add_action("enqueue_block_assets", [$this, "enqueue_background_parallax_editor_assets"]);
            add_filter("render_block_data", [$this, "enqueue_background_parallax_frontend_assets"]);
            add_filter("render_block", [$this, "background_parallax_data_attributes"], 10, 2);
        }
    }

    public function enqueue_background_parallax_editor_assets() {
        $editor_assets_path = ZOLO_PRO_DIR_PATH . "build/extensions/image-parallax/index.asset.php";
        $frontend_assets_path = ZOLO_PRO_DIR_PATH . "build/extensions/image-parallax/frontend.asset.php";
        if (file_exists($editor_assets_path) && is_admin()) {
            $editor_assets = include $editor_assets_path;

            wp_register_script(
                'zolo-image-parallax-editor-script',
                ZOLO_PRO_ADMIN_URL . 'build/extensions/image-parallax/index.js',
                $editor_assets['dependencies'],
                $editor_assets['version'],
                true
            );
            wp_enqueue_script('zolo-image-parallax-editor-script');
        }
        if (file_exists($frontend_assets_path) && !is_admin()) {
            $frontend_assets = include $frontend_assets_path;
            //merge with the existing dependencies
            $frontend_assets['dependencies'] = array_merge($frontend_assets['dependencies'], ['gsap', 'scrolltrigger']);
            wp_register_script(
                'zolo-image-parallax-frontend-script',
                ZOLO_PRO_ADMIN_URL . 'build/extensions/image-parallax/frontend.js',
                $frontend_assets['dependencies'],
                $frontend_assets['version'],
                true
            );
        }
    }

    public function enqueue_background_parallax_frontend_assets($block) {
        if (
            isset($block['blockName']) &&
            $block['blockName'] === 'zolo/advanced-image' &&
            !empty($block['attrs']['imageParallax']['active']) &&
            !is_admin()
        ) {

            wp_enqueue_script('simple-parallax');
            wp_enqueue_script('zolo-image-parallax-frontend-script');
        }
        return $block;
    }


    public function background_parallax_data_attributes($block_content, $block) {
        if (isset($block['blockName']) && str_contains($block['blockName'], 'zolo/advanced-image')) {
            $background_parallax = $block['attrs']['imageParallax'] ?? null;
            if ($background_parallax && !empty($background_parallax['active'])) { {
                    $tags = new \WP_HTML_Tag_Processor($block_content);
                    $tags->next_tag();
                    $tags->set_attribute('data-image-parallax', json_encode($background_parallax));
                    $block_content = $tags->get_updated_html();
                    return $block_content;
                }
            }
        }

        return $block_content;
    }
}
