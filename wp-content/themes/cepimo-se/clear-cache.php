<?php
/**
 * Cache Clearing Script for WordPress Patterns
 * Run this file directly in your browser to clear all caches
 */

// Load WordPress
require_once('../../../wp-load.php');

// Clear all caches
if (function_exists('wp_cache_flush')) {
    wp_cache_flush();
}

// Clear transients
delete_transient('block_patterns');
delete_transient('block_pattern_categories');

// Clear any object cache
if (function_exists('wp_cache_flush')) {
    wp_cache_flush();
}

// Clear LiteSpeed Cache if available
if (class_exists('LiteSpeed_Cache_API')) {
    LiteSpeed_Cache_API::purge_all();
}

// Clear any other caching plugins
do_action('wp_cache_flush');

echo "Cache cleared successfully! Please refresh your WordPress admin and frontend.";
?>
