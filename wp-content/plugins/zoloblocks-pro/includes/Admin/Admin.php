<?php

namespace ZoloPro\Admin;

use ZoloPro\Admin\License\License;
use ZoloPro\Admin\License\ZoloBlocksBase;
use ZoloPro\Traits\SingletonTrait;

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

if (! class_exists('Admin')) {
    /**
     * Main Admin Class
     *
     * @since 1.0.0
     * @return Admin
     */
    class Admin {

        const PAGE_ID = 'zoloblocks-pro-license';

        public $responseObj;
        public $licenseMessage;
        public $showMessage = false;
        private $is_activated = false;

        /**
         * Constructor
         *
         * @since 1.0.0
         * @return void
         */
        function __construct() {

            // admin enqueue scripts
            add_action('admin_enqueue_scripts', [$this, 'admin_enqueue_scripts']);

            add_action('admin_menu', [$this, 'admin_menu'], 201);
            add_action('zolo_license_page', [$this, 'license_page'], 99);
            $license_key   = self::get_license_key();
            $license_email = self::get_license_email();

            ZoloBlocksBase::add_on_delete(
                function () {
                    update_option('zolo_license_email', '');
                    update_option('zolo_license_key', '');
                    update_option(ZoloBlocksBase::get_lic_key_param('zolo_license_key'), '');
                }
            );

            if (ZoloBlocksBase::check_wp_plugin($license_key, $license_email, $error, $responseObj, ZOLO_PRO_FILE)) {
                add_action('admin_post_zolo_block_deactivate_license', [$this, 'action_deactivate_license']);
                $this->is_activated = true;
            } else {

                if (!empty($licenseKey) && !empty($this->licenseMessage)) {
                    $this->showMessage = true;
                }

                if ($error) {
                    $this->licenseMessage = $error;
                    $this->license_activate_error_notice();
                    add_action('admin_notices', [$this, 'license_activate_error_notice'], 10, 3);
                }

                $this->license_activate_notice();

                update_option(ZoloBlocksBase::get_lic_key_param('zolo_license_key'), "");
                add_action('admin_post_zolo_block_activate_license', [$this, 'action_activate_license']);
            }
        }

        /**
         * admin_enqueue_scripts
         *
         * @since 1.0.0
         */
        public function admin_enqueue_scripts($screen) {
            wp_enqueue_style('zolo-blocks-admin-notice', trailingslashit(ZOLO_PRO_ADMIN_URL) . 'includes/Admin/assets/css/admin-notice.css', [], ZOLO_PRO_VERSION);
            wp_enqueue_style('zolo-blocks-admin', trailingslashit(ZOLO_PRO_ADMIN_URL) . 'includes/Admin/assets/css/license.css', [], ZOLO_PRO_VERSION);

            if ('toplevel_page_zoloblocks' === $screen) {

                $dependencyFile = trailingslashit(ZOLO_PRO_DIR_PATH) . 'build/admin/index.asset.php';

                if (!file_exists($dependencyFile)) {
                    return;
                }

                $dependency = require_once $dependencyFile;

                wp_enqueue_script(
                    'zoloblocks-pro-admin-js',
                    trailingslashit(ZOLO_PRO_ADMIN_URL) . 'build/admin/index.js',
                    $dependency['dependencies'],
                    $dependency['version'],
                    false
                );
            }
        }

        /**
         * Get License Page URL
         *
         * @return string
         */
        public static function get_url() {
            return admin_url('admin.php?page=' . self::PAGE_ID);
        }

        function admin_menu() {
            add_submenu_page('zoloblocks', esc_html__('License', 'zoloblocks-pro'), esc_html__('License', 'zoloblocks-pro'), 'manage_options', self::PAGE_ID, [$this, 'display_page']);
        }

        public function display_page() {
            do_action('zolo_license_page');
        }

        /**
         * Get all the pages
         *
         * @return array page names with key value pairs
         */
        function get_pages() {
            $pages         = get_pages();
            $pages_options = [];
            if ($pages) {
                foreach ($pages as $page) {
                    $pages_options[$page->ID] = $page->post_title;
                }
            }

            return $pages_options;
        }

        /**
         * Get License Key
         *
         * @access public
         * @return string
         */

        public static function get_license_key() {
            $license_key = get_option(ZoloBlocksBase::get_lic_key_param('zolo_license_key'));
            if (empty($license_key)) {
                $license_key = get_option('zolo_license_key');
                if (!empty($license_key)) {
                    self::set_license_key($license_key);
                    update_option('zolo_license_key', '');
                }
            }
            return trim($license_key);
        }

        /**
         * Get License Email
         *
         * @access public
         * @return string
         */
        public static function get_license_email() {
            return trim(get_option('zolo_license_email', get_bloginfo('admin_email')));
        }

        /**
         * Set License Key
         *
         * @access public
         * @return string
         */

        public static function set_license_key($license_key) {
            return update_option('zolo_license_key', $license_key);
        }

        /**
         * Set License Email
         *
         * @access public
         * @return string
         */

        public static function set_license_email($license_email) {
            return update_option('zolo_license_email', $license_email);
        }


        /**
         * Display License Page
         *
         * @access public
         */

        public function license_page() {
            if ($this->is_activated) {
                $this->license_activated();
            } else {
                if (!empty($licenseKey) && !empty($this->licenseMessage)) {
                    $this->showMessage = true;
                }
                $this->license_form();
            }
        }

        /**
         * License Deactivate Action
         * @access public
         */

        function action_deactivate_license() {
            check_admin_referer('zb-license');
            if (ZoloBlocksBase::remove_license_key(ZOLO_PRO_FILE, $message)) {
                update_option("zolo_license_key", "") || add_option("zolo_license_key");
            }
            wp_safe_redirect(admin_url('admin.php?page=' . 'zoloblocks-pro-license#zolo_license_settings'));
        }

        /**
         * License Active Error
         *
         * @access public
         */

        public function license_activate_error_notice() {
            var_dump($this->licenseMessage);
        }

        /**
         * License Active Notice
         *
         * @access public
         */

        public function license_activate_notice() {
            add_action('admin_notices', function () {
                echo $this->license_active_notice_message(); // phpcs:ignore
            });
        }

        public function license_active_notice_message() {
            $plugin_icon = 'images/logo.png';
            $plugin_title = esc_html__('ZoloBlocks Pro', 'zoloblocks-pro');
            $plugin_msg = esc_html__('Thank you for purchase ZoloBlocks. Please activate your license to get feature updates, premium support. Don\'t have ZoloBlocks license? Purchase and download your license copy from here.', 'zoloblocks-pro');
            ob_start();
?>
            <div class="wrap">
                <div class="zolo-license-notice-global zolo_block">
                    <?php if (!empty($plugin_icon)) : ?>
                        <div class="zolo-license-notice-logo">
                            <svg version="1.2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 110 110" width="110" height="110">
                                <style>
                                    .s0 {
                                        fill: #1b1e1d
                                    }

                                    .s1 {
                                        fill: #2667ff
                                    }

                                    .s2 {
                                        fill: #add7f6
                                    }

                                    .s3 {
                                        fill: #ffffff
                                    }
                                </style>
                                <g id="Layer">
                                    <path id="Layer" class="s0" d="m153.8 70.5h22v10.2h-36.1v-9.5l21.8-31.4h-21.8v-10.2h36.1v9.5z" />
                                    <path id="Layer" class="s0" d="m253.5 71.1h16.3v9.6h-28.7v-51.1h12.4z" />
                                    <path id="Layer" fill-rule="evenodd" class="s0" d="m233.8 54.9c0 14.5-11.7 26.2-26.2 26.2-14.5 0-26.2-11.7-26.2-26.2 0-14.4 11.7-26.2 26.2-26.2 14.5 0 26.2 11.8 26.2 26.2zm-12.4 0c0-7.6-6.2-13.7-13.8-13.7-7.6 0-13.8 6.1-13.8 13.7 0 7.6 6.2 13.8 13.8 13.8 7.6 0 13.8-6.2 13.8-13.8z" />
                                    <path id="Layer" fill-rule="evenodd" class="s0" d="m326 54.9c0 14.5-11.8 26.2-26.3 26.2-14.4 0-26.2-11.7-26.2-26.2 0-14.4 11.8-26.2 26.3-26.2 14.4 0 26.2 11.8 26.2 26.2zm-12.5 0c0-7.6-6.2-13.7-13.8-13.7-7.5 0-13.7 6.1-13.7 13.7 0 7.6 6.1 13.8 13.8 13.8 7.5 0 13.7-6.2 13.7-13.8z" />
                                </g>
                                <g id="Layer">
                                    <path id="Layer" fill-rule="evenodd" class="s0" d="m364.5 56.8q2.3 1.8 3.7 4.5 1.3 2.7 1.3 5.8 0 3.8-1.9 6.9-2 3.1-5.7 4.9-3.8 1.8-8.9 1.8h-18.9v-50.7h18.2q5.1 0 8.8 1.7 3.6 1.7 5.4 4.6 1.9 2.9 1.9 6.5 0 4.5-2.5 7.5-2.4 2.9-6.4 4.2 2.7 0.5 5 2.3zm-12.7-5q4.7 0 7.2-2.1 2.6-2.2 2.6-6.1 0-3.8-2.6-6-2.5-2.2-7.3-2.2h-11v16.4zm8.3 21.1q2.7-2.4 2.7-6.5c0-2.8-0.9-5-2.9-6.7q-2.9-2.4-7.8-2.4h-11.5v17.9h11.8q4.9 0 7.7-2.3z" />
                                    <path id="Layer" class="s0" d="m385.3 75.3h17.8v5.4h-24.4v-50.7h6.6c0 0 0 45.3 0 45.3z" />
                                    <path id="Layer" class="s0" d="m468.2 41.9q3.3-5.9 9.1-9.2 5.8-3.4 12.8-3.4 8.3 0 14.5 4 6.2 4 9 11.4h-7.9q-2.1-4.6-6.1-7.1-3.9-2.5-9.5-2.5c-3.6 0-6.7 0.8-9.5 2.5q-4.2 2.5-6.6 7-2.4 4.6-2.4 10.7c0 4 0.8 7.5 2.4 10.6q2.4 4.5 6.6 7 4.2 2.5 9.5 2.5c3.6 0 6.9-0.8 9.5-2.5q4-2.4 6.1-7h7.9q-2.8 7.3-9 11.3-6.2 3.9-14.5 3.9-7 0-12.8-3.3-5.8-3.3-9.1-9.2-3.4-5.9-3.4-13.3 0-7.4 3.4-13.4 0 0 0 0z" />
                                    <path id="Layer" class="s0" d="m574.1 79.4q-3.9-1.8-6.2-5-2.2-3.1-2.3-7.3h7q0.4 3.6 3 6 2.6 2.5 7.5 2.5c3.3 0 5.7-0.8 7.5-2.4q2.7-2.4 2.7-6.1 0-2.9-1.6-4.7-1.6-1.8-4-2.8-2.4-0.9-6.5-2-5-1.3-8-2.6-3-1.3-5.2-4.1-2.1-2.8-2.1-7.6 0-4.1 2.1-7.3 2.1-3.2 5.9-5 3.8-1.7 8.8-1.7 7.1 0 11.6 3.6 4.6 3.5 5.2 9.4h-7.3q-0.4-2.9-3.1-5.1-2.7-2.2-7.1-2.2-4.1 0-6.8 2.1-2.6 2.2-2.6 6 0 2.8 1.6 4.5 1.6 1.8 3.9 2.7 2.2 0.9 6.4 2.1 5 1.3 8.1 2.7 3 1.3 5.2 4.1 2.2 2.8 2.2 7.6 0 3.8-2 7-1.9 3.3-5.8 5.3-3.8 2.1-9.1 2.1c-3.5 0-6.3-0.6-9-1.8z" />
                                    <g id="Layer">
                                        <path id="Layer" class="s0" d="m530.1 29.9h-6.7v22l6.7-7.4v-14.6z" />
                                        <path id="Layer" class="s0" d="m551 80.7h8.6l-23.2-25.4 23-25.4h-8.4l-20.9 23.6-6.7 7.1v20.1h6.7v-18.7l2-2.3 18.9 21z" />
                                    </g>
                                    <path id="Layer" fill-rule="evenodd" class="s0" d="m459 55.2c0 14.3-11.5 25.9-25.8 25.9-14.3 0-25.9-11.6-25.9-25.9 0-14.3 11.6-25.9 25.9-25.9 14.3 0 25.8 11.6 25.8 25.9zm-5.3 0c0-11.3-9.2-20.5-20.5-20.5-11.3 0-20.5 9.2-20.5 20.5 0 11.3 9.2 20.5 20.5 20.5 11.3 0 20.5-9.2 20.5-20.5z" />
                                </g>
                                <g id="Layer">
                                    <path id="Layer" class="s1" d="m104.8 110h-99.7c-2.8 0-5.1-2.3-5.1-5.2v-99.7c0-2.7 2.3-5.1 5.1-5.1h99.7c2.8 0 5.1 2.3 5.1 5.1v99.7c0 2.9-2.3 5.2-5.1 5.2z" />
                                    <path id="Layer" class="s2" d="m22.6 49.8l28.3-28.6h-28.4l0.1 28.6z" />
                                    <path id="Layer" class="s3" d="m80.1 40.1l-11.2 11.2c1.7 1.8 3.7 4.8 3.7 7.5 0 3.2-1.3 5.8-3.4 7.9-1.9 2.1-4.5 3.1-7.6 3.1h-24.3l49.5-48.6h-22.9l-41.3 41.1v26.5l15.8-0.1h23.2c8.2 0 14.9-2.5 20.6-7.5 5.5-5.1 8.3-13.3 8.3-21.1 0-8.3-4.2-15.3-10.4-20z" />
                                </g>
                            </svg>
                        </div>
                    <?php endif; ?>
                    <div class="zolo-license-notice-content">
                        <h3>
                            <?php printf(wp_kses_post($plugin_title)); ?>
                        </h3>
                        <p>
                            <?php printf(wp_kses_post($plugin_msg)); ?>
                        </p>
                        <div class="zolo-license-notice-button-wrap">
                            <a href="<?php echo esc_url(self::get_url()); ?>#zolo_license_settings" class="zolo-button zolo-button-allow">
                                <?php esc_html_e('Activate License', 'zoloblocks-pro'); ?>
                            </a>
                            <a href="https://zoloblocks.com/pricing" target="_blank" class="zolo-button zolo-button-skip">
                                <?php esc_html_e('Purchase License', 'zoloblocks-pro'); ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php
            return ob_get_clean();
        }

        /**
         * Display License Activated
         *
         * @access public
         * @return void
         */
        function license_activated() {
        ?>
            <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                <input type="hidden" name="action" value="zolo_block_deactivate_license" />
                <div class="zb-license-container zolo-card zolo-card-body">
                    <h3 class="zb-license-title"><span class="dashicons dashicons-admin-network"></span>
                        <?php esc_html_e("ZoloBlocks License Information", 'zoloblocks-pro'); ?>
                    </h3>

                    <ul class="zolo-license-info zolo-list zolo-list-divider">
                        <li>
                            <div>
                                <span class="license-info-title">
                                    <?php esc_html_e('Status', 'zoloblocks-pro'); ?>
                                </span>

                                <?php if (ZoloBlocksBase::get_register_info()->is_valid) : ?>
                                    <span class="license-valid">Valid License</span>
                                <?php else : ?>
                                    <span class="license-valid">Invalid License</span>
                                <?php endif; ?>
                            </div>
                        </li>

                        <li>
                            <div>
                                <span class="license-info-title">
                                    <?php esc_html_e('License Type', 'zoloblocks-pro'); ?>
                                </span>
                                <?php echo esc_html(ZoloBlocksBase::get_register_info()->license_title); ?>
                            </div>
                        </li>

                        <li>
                            <div>
                                <span class="license-info-title">
                                    <?php esc_html_e('License Expired on', 'zoloblocks-pro'); ?>
                                </span>
                                <?php echo esc_html(ZoloBlocksBase::get_register_info()->expire_date); ?>
                            </div>
                        </li>

                        <li>
                            <div>
                                <span class="license-info-title">
                                    <?php esc_html_e('Support Expired on', 'zoloblocks-pro'); ?>
                                </span>
                                <?php echo esc_html(ZoloBlocksBase::get_register_info()->support_end); ?>
                            </div>
                        </li>

                        <li>
                            <div>
                                <span class="license-info-title">
                                    <?php esc_html_e('License Email', 'zoloblocks-pro'); ?>
                                </span>
                                <?php
                                echo esc_html(get_option(ZoloBlocksBase::get_lic_key_param('zolo_license_email')));
                                ?>
                            </div>
                        </li>

                        <li>
                            <div>
                                <span class="license-info-title">
                                    <?php esc_html_e('Your License Key', 'zoloblocks-pro'); ?>
                                </span>
                                <span class="license-key">
                                    <?php echo esc_attr(substr(ZoloBlocksBase::get_register_info()->license_key, 0, 9) . "XXXXXXXX-XXXXXXXX" . substr(ZoloBlocksBase::get_register_info()->license_key, -9)); ?>
                                </span>
                            </div>
                        </li>
                    </ul>

                    <div class="zb-license-active-btn">
                        <?php wp_nonce_field('zb-license'); ?>
                        <?php submit_button('Deactivate License'); ?>
                    </div>
                </div>
            </form>
        <?php
        }

        /**
         * Display License Form
         *
         * @access public
         * @return void
         */

        function license_form() {
        ?>
            <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                <input type="hidden" name="action" value="zolo_block_activate_license" />
                <div class="zb-license-container zolo-card zolo-card-body">

                    <?php
                    if (!empty($this->showMessage) && !empty($this->licenseMessage)) {
                    ?>
                        <div class="notice notice-error is-dismissible">
                            <p>
                                <?php echo esc_html($this->licenseMessage); ?>
                            </p>
                        </div>
                    <?php
                    }
                    ?>

                    <h3 class="zolo-text-large">
                        <strong>
                            <?php esc_html_e('Enter your license key here, to activate ZoloBlocks Pro, and get full feature updates and premium support.', 'zoloblocks-pro'); ?>
                        </strong>
                    </h3>

                    <ol class="zolo-text-default">
                        <li>
                            <?php printf(sprintf('Log in to your <a href="%1s" target="_blank">BdThemes Account</a> to get your license key.', 'https://account.bdthemes.com/login')); ?>
                        </li>
                        <li>
                            <?php printf(sprintf('If you don\'t yet have a license key, <a href="%s" target="_blank">get ZoloBlocks Pro now</a>.', 'https://zoloblocks.com/pricing')); ?>
                        </li>
                        <li>
                            <?php esc_html_e('Copy the license key from your account and paste it below for work ZoloBlocks properly.', 'zoloblocks-pro'); ?>
                        </li>
                    </ol>

                    <div class="zolo-zb-license-field zolo-margin-top">
                        <label for="zolo_license_email">License Email
                            <input type="text" class="regular-text code" name="zolo_license_email" size="50" placeholder="example@email.com" required="required">
                        </label>
                    </div>

                    <div class="zolo-zb-license-field zolo-margin-top">
                        <label for="zolo_license_key">License Code
                            <input type="text" class="regular-text code" name="zolo_license_key" size="50" placeholder="xxxxxxxx-xxxxxxxx-xxxxxxxx-xxxxxxxx" required="required">
                        </label>
                    </div>

                    <div class="zb-license-active-btn">
                        <?php wp_nonce_field('zb-license'); ?>
                        <?php submit_button('Activate License'); ?>
                    </div>
                </div>
            </form>
<?php
        }

        /**
         * License Activate Action
         * @access public
         */

        function action_activate_license() {
            check_admin_referer('zb-license');

            $licenseKey   = !empty($_POST['zolo_license_key']) ? sanitize_text_field($_POST['zolo_license_key']) : "";
            $licenseEmail = !empty($_POST['zolo_license_email']) ? wp_unslash($_POST['zolo_license_email']) : "";

            update_option(ZoloBlocksBase::get_lic_key_param('zolo_license_key'), $licenseKey);
            update_option(ZoloBlocksBase::get_lic_key_param('zolo_license_email'), $licenseEmail);

            wp_safe_redirect(admin_url('admin.php?page=' . 'zoloblocks-pro-license#zolo_license_settings'));
        }
    }
}
