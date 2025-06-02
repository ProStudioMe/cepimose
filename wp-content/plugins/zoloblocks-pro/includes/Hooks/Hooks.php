<?php
namespace ZoloPro\Hooks;

use ZoloPro\Traits\SingletonTrait;
use ZoloPro\Hooks\Types\Filters;
use ZoloPro\Hooks\Types\Actions;

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

if ( ! class_exists( 'Hooks' ) ) {

    /**
     * Main Hooks Class
     * 
     * @since 1.0.0
     * @return Hooks
     */
    class Hooks {

        /**
         * Hooks Instance
         * 
         * @since 1.0.0
         * @var Hooks
         * @access private
         */
        use SingletonTrait;

        /**
         * Constructor
         * 
         * @since 1.0.0
         * @return void
         */
        public function __construct() {
            $this->init_hooks();
        }

        /**
         * Initialize Hooks
         * 
         * @since 1.0.0
         * @return void
         */
        public function init_hooks() {
            Filters::getInstance();
            Actions::getInstance();
        }

    }
} 