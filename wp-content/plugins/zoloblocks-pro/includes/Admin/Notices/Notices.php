<?php

namespace ZoloPro\Admin\Notices;

use ZoloPro\Traits\SingletonTrait;

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

if (! class_exists('Notices')) {

    /**
     * Main Notices Class
     *
     * @since 1.0.0
     * @return Notices
     */
    class Notices {

        /**
         * Notice for ZoloBlocks is active or not.
         *
         * @since 1.0.0
         * @return void
         */
        public static function is_zolo_blocks_active() {
?>
            <div class="zolo-admin-notice zoloblocks notice">
                <div class="zolo-notice-logo">
                    <img src="<?php echo esc_url(ZOLO_PRO_ADMIN_URL . 'includes/Admin/assets/images/logo.svg'); ?>" alt="ZoloBlocks Pro">
                </div>
                <div class="zolo-notice-content">
                    <h3 class="zolo-notice-title">
                        <?php echo esc_html__('ZoloBlocks Pro', 'zoloblocks-pro'); ?>
                    </h3>
                    <p class="zolo-notice-desc">
                        <?php echo esc_html__('ZoloBlocks Pro depends on the activation of ZoloBlocks for complete functionality. Activate ZoloBlocks to access all features of ZoloBlocks Pro.', 'zoloblocks-pro'); ?>
                    </p>
                    <div class="zolo-notice-btn-wrap">
                        <?php if (file_exists(WP_PLUGIN_DIR . '/zoloblocks/zoloblocks.php')) {
                            $notice_title = esc_html__('Activate ZoloBlocks', 'zoloblocks-pro');
                            $notice_url = wp_nonce_url('plugins.php?action=activate&plugin=zoloblocks/zoloblocks.php&plugin_status=all&paged=1', 'activate-plugin_zoloblocks/zoloblocks.php');
                        } else {
                            $notice_title = esc_html__('Install ZoloBlocks', 'zoloblocks-pro');
                            $notice_url = wp_nonce_url(self_admin_url('update.php?action=install-plugin&plugin=zoloblocks'), 'install-plugin_zoloblocks');
                        } ?>
                        <a href=<?php echo esc_url($notice_url); ?> class="zolo-notice-btn zolo-notice-btn-allow zolo-notice-btn-activate">
                            <?php echo esc_html($notice_title); ?>
                        </a>
                        <?php
                        ?>
                    </div>
                </div>
            </div>
        <?php
        }

        /**
         * Notice for PHP Version Check.
         *
         * @since 1.0.0
         * @return void
         */
        public static function php_version_check() {
            $current_php_version = phpversion();
        ?>
            <div class="zolo-admin-notice zoloblocks notice">
                <div class="zolo-notice-logo">
                    <img src="<?php echo esc_url(ZOLO_PRO_ADMIN_URL . 'includes/Admin/assets/images/logo.svg'); ?>" alt="ZoloBlocks Pro">
                </div>
                <div class="zolo-notice-content">
                    <h3 class="zolo-notice-title">
                        <?php echo esc_html__('ZoloBlocks Pro', 'zoloblocks-pro'); ?>
                    </h3>
                    <p class="zolo-notice-desc">
                        <?php
                        $translated_text = esc_html__('ZoloBlocks Pro requires PHP verion %1$s. Your current PHP version is %2$s.', 'zoloblocks-pro'); // phpcs:ignore
                        $formatted_text = sprintf($translated_text, ZOLO_PRO_PHP_VERSION, $current_php_version);
                        echo esc_html($formatted_text);
                        ?>
                    </p>
                </div>
            </div>
        <?php
        }

        /**
         * Notice for WordPress Version Check.
         *
         * @since 1.0.0
         * @return void
         */
        public static function wp_version_check() {
            $current_wp_version = get_bloginfo('version');
        ?>
            <div class="zolo-admin-notice zoloblocks notice">
                <div class="zolo-notice-logo">
                    <img src="<?php echo esc_url(ZOLO_PRO_ADMIN_URL . 'includes/Admin/assets/images/logo.svg'); ?>" alt="ZoloBlocks Pro">
                </div>
                <div class="zolo-notice-content">
                    <h3 class="zolo-notice-title">
                        <?php echo esc_html__('ZoloBlocks Pro', 'zoloblocks-pro'); ?>
                    </h3>
                    <p class="zolo-notice-desc">
                        <?php
                        $translated_text = esc_html__('ZoloBlocks Pro requires WordPress version %1$s. Your current WordPress version is %2$s.', 'zoloblocks-pro'); // phpcs:ignore
                        $formatted_text = sprintf($translated_text, ZOLO_PRO_WP_VERSION, $current_wp_version);
                        echo esc_html($formatted_text);
                        ?>
                    </p>
                </div>
            </div>
<?php
        }
    }
}
