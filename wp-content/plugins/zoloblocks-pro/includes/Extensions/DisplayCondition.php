<?php

/**
 * ZoloBlocks Pro Enqueues.
 */

namespace ZoloPro\Extensions;


use ZoloPro\Helpers\ZoloProHelpers;
use ZoloPro\Traits\SingletonTrait;

// Exit if accessed directly.
if (!defined('ABSPATH')) exit;

class DisplayCondition {

    use SingletonTrait;

    public function __construct() {
        if (ZoloProHelpers::is_extension_enabled('display-condition')) {
            add_action("enqueue_block_assets", [$this, "enqueue_display_condition_editor_assets"]);
            add_filter('render_block', [$this, 'zolo_conditional_render_block'], 10, 2);
        }
    }

    public function enqueue_display_condition_editor_assets() {
        $editor_assets_path = ZOLO_PRO_DIR_PATH . "build/extensions/display-condition/index.asset.php";
        if (file_exists($editor_assets_path) && is_admin()) {
            $editor_assets = include $editor_assets_path;
            wp_register_style('zolo-display-condition-editor-style', ZOLO_PRO_ADMIN_URL . 'build/extensions/display-condition/index.css', [], $editor_assets['version']);
            wp_register_script(
                'zolo-display-condition-editor-script',
                ZOLO_PRO_ADMIN_URL . 'build/extensions/display-condition/index.js',
                $editor_assets['dependencies'],
                $editor_assets['version'],
                true
            );
            wp_enqueue_script('zolo-display-condition-editor-script');
            wp_enqueue_style('zolo-display-condition-editor-style');
        }
    }

    public function filterValidConditions(array $conditions): array
    {
        $filteredConditions = [];

        foreach ($conditions as $conditionGroup) {
            $validConditions = [];
            if (!empty($conditionGroup)) {
                foreach ($conditionGroup as $condition) {
                    if (!empty($condition['type']) && !empty($condition['value'])) {
                        $validConditions[] = $condition;
                    }
                }
            }

            if (!empty($validConditions)) {
                $filteredConditions[] = $validConditions;
            }
        }

        return $filteredConditions;
    }

    /**
     * Render block
     * @param string $block_content
     * @param array $block
     * @return string
     */
    public function zolo_conditional_render_block($block_content, $block) {

        if (isset($block['blockName']) && str_contains($block['blockName'], 'zolo/')) {
            $advancedVisibility = $block['attrs']['enableAdvancedVisibility'] ?? false;

            if ($advancedVisibility) {

                $visibilityType = isset($block['attrs']['visibilityType']) ? $block['attrs']['visibilityType'] : 'cd_show';

                $displayConditionsRows = $block['attrs']['displayConditions'] ?? [];
                $validConditionsRows = $this->filterValidConditions($displayConditionsRows);

                $final_result = false;

                if (empty($validConditionsRows)) return $block_content;


                foreach ($validConditionsRows as $conditions) {
                    $single_result = true;
                    foreach ($conditions as $condition) {
                        $condition_result = $this->check_condition($condition);
                        $single_result = $single_result && $condition_result;
                    }
                    $final_result = $final_result || $single_result;
                }

                if ($visibilityType === 'cd_show' && !$final_result) {
                    return '';
                } else if ($visibilityType === 'cd_hide' && $final_result) {
                    return '';
                } else {
                    return $block_content;
                }
            }
        }

        return $block_content;
    }

    /**
     * Check condition
     * @param array $condition
     */
    public function check_condition($condition) {

        if (! empty($condition) && is_array($condition)) {

            // login status
            if ($condition['type'] === 'login_status') {
                if ($condition['value'] === 'logged_in') {
                    return is_user_logged_in();
                } else {
                    return !is_user_logged_in();
                }
            }

            // user role
            if ($condition['type'] === 'user_role') {
                $user = wp_get_current_user();
                $roles = $user->roles;
                return in_array($condition['value'], $roles);
            }

            // days
            if ($condition['type'] === 'days_of_week') {
                $daysValues = $condition['value'] ?? [];
                $currentDay = strtolower(gmdate('l'));

                $result = false;

                if (!empty($daysValues) && is_array($daysValues)) {
                    foreach ($daysValues as $day) {
                        $dayVale = strtolower($day['value']);
                        if ($currentDay === $dayVale) {
                            $result = true;
                            break;
                        }
                    }
                }

                return $result;
            }

            // pages
            if ($condition['type'] === 'pages') {

                // post type
                $post_type = get_post_type();
                if ($post_type !== 'page') {
                    return false;
                }

                $pagesValues = $condition['value'] ?? [];
                $currentPage = get_the_ID();

                $result = false;

                if (!empty($pagesValues) && is_array($pagesValues)) {
                    foreach ($pagesValues as $page) {
                        $pageValue = $page['value'];
                        if ($currentPage == $pageValue) {
                            $result = true;
                            break;
                        }
                    }
                }

                return $result;
            }

            // posts
            if ($condition['type'] === 'posts') {

                // post type
                $post_type = get_post_type();
                if ($post_type !== 'post') {
                    return false;
                }

                $postsValues = $condition['value'] ?? [];
                $currentPost = get_the_ID();

                $result = false;

                if (!empty($postsValues) && is_array($postsValues)) {
                    foreach ($postsValues as $post) {
                        $postValue = $post['value'];
                        if ($currentPost == $postValue) {
                            $result = true;
                            break;
                        }
                    }
                }

                return $result;
            }

            // browser
            if ($condition['type'] === 'browsers') {

                $browser = $this->detectBrowser();
                $browserValues = $condition['value'] ?? [];

                $result = false;

                if (!empty($browserValues) && is_array($browserValues)) {
                    foreach ($browserValues as $browserValue) {
                        if ($browser === $browserValue['value']) {
                            $result = true;
                            break;
                        }
                    }
                }

                return $result;
            }

            return false;
        }
    }

    /**
     * Check browser
     */
    public function detectBrowser() {
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        $browser = "Unknown Browser";

        // Check for various browsers
        if (strpos($userAgent, 'Opera') || strpos($userAgent, 'OPR/')) {
            $browser = 'opera';
        } elseif (strpos($userAgent, 'Edge')) {
            $browser = 'edge';
        } elseif (strpos($userAgent, 'Chrome')) {
            $browser = 'chrome';
        } elseif (strpos($userAgent, 'Safari')) {
            $browser = 'safari';
        } elseif (strpos($userAgent, 'Firefox')) {
            $browser = 'firefox';
        } elseif (strpos($userAgent, 'MSIE') || strpos($userAgent, 'Trident/7')) {
            $browser = 'ie';
        }

        return $browser;
    }
}
