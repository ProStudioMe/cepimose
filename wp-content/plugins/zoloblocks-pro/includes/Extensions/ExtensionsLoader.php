<?php

/**
 * ZoloBlocks Pro Enqueues.
 */

namespace ZoloPro\Extensions;

use ZoloPro\Traits\SingletonTrait;

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

class ExtensionsLoader {
    use SingletonTrait;

    public function __construct() {
        $this->load_classes();
    }

    public function load_classes() {
        Interactions::getInstance();
        Cursors::getInstance();
        Tilt::getInstance();
        CSSFilters::getInstance();
        BackdropFilters::getInstance();
        BackgroundParallax::getInstance();
        DynamicContent::getInstance();
        MotionEffects::getInstance();
        Tooltip::getInstance();
        Highlight::getInstance();
        TextAnimation::getInstance();
        DisplayCondition::getInstance();
        Blocks::getInstance();
        SmoothScroller::getInstance();
        ImageParallax::getInstance();
    }
}
