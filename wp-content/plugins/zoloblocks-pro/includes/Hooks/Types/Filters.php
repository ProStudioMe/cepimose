<?php
namespace ZoloPro\Hooks\Types;
use ZoloPro\Traits\SingletonTrait;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'Filters' ) ) {

    /**
     * Main Filters Class
     * 
     * @since 1.0.0
     * @return Filters
     */
    class Filters {

        /**
         * Filters Instance
         * 
         * @since 1.0.0
         * @var Filters
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
            // add necessary filters here 
        }

    }
} 
