<?php
/**
 * Title: Sidebar Aside Widget Area
 * Slug: cepimo-se/sidebar-aside
 * Categories: cepimose
 * Keywords: sidebar, aside, widgets, navigation, anchor links, related posts
 * Description: A custom tabbed aside sidebar with anchor links and related posts
 */
?>

<!-- wp:group {"tagName":"aside","className":"aside-tab-wrapper","layout":{"type":"constrained"}} -->
<aside class="wp-block-group aside-tab-wrapper">
    <!-- wp:html -->
    <!-- Mobile drawer toggle button -->
    <button id="aside-mobile-toggle" class="aside-mobile-toggle xl:hidden">
        <img src="/wp-content/themes/cepimo-se/assets/arrows-up.svg" alt="Expand sidebar" class="toggle-icon">
    </button>

    <div class="custom-tabs">
        <div class="tab-headers">
            <button class="tab-header active" data-tab="tab1">Razdelki na tej strani</button>
            <button class="tab-header" data-tab="tab2">Povezane objave</button>
        </div>
    </div>

    <!-- Sidebar functionality handled by external sidebar-tabs.js -->
    <!-- /wp:html -->

    <!-- wp:prostudiome/anchor-links {"className":"custom-tab-content tab-content-1 active"} /-->

    <!-- wp:prostudiome/same-category-posts {"className":"custom-tab-content tab-content-2"} /-->

    <!-- wp:shortcode -->
    <div class="aside-button-section">
        <div class="sample-button">
            [aside_button]
        </div>
    </div>
    <!-- /wp:shortcode -->

    <!-- wp:shortcode -->
    <div class="aside-banner-section">
        <div class="sample-banner">
            [aside_banner]
        </div>
    </div>
    <!-- /wp:shortcode -->
</aside>
<!-- /wp:group -->