<?php
namespace ZoloPro\Hooks\Types;
use ZoloPro\Traits\SingletonTrait;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'Actions' ) ) {

    /**
     * Main Actions Class
     * 
     * @since 1.0.0
     * @return Actions
     */
    class Actions {

        /**
         * Actions Instance
         * 
         * @since 1.0.0
         * @var Actions
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
            // add necessary actions here 
        }

    }
} 