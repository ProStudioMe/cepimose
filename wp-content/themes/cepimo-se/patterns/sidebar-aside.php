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
    <div class="custom-tabs">
        <div class="tab-headers">
            <button class="tab-header active" data-tab="tab1">Razdelki na tej strani</button>
            <button class="tab-header" data-tab="tab2">Povezane objave</button>
        </div>
        
        <div class="tab-content">
            <div class="tab-panel active" id="tab1">
                <!-- Content for anchor links will be populated here -->
            </div>
            <div class="tab-panel" id="tab2">
                <!-- Content for related posts will be populated here -->
            </div>
        </div>
    </div>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const tabHeaders = document.querySelectorAll('.custom-tabs .tab-header');
        const tabPanels = document.querySelectorAll('.custom-tabs .tab-panel');
        const tabContents = document.querySelectorAll('.custom-tab-content');
        
        // Ensure first tab is active on load
        setTimeout(() => {
            if (tabContents.length > 0) {
                tabContents.forEach((content, index) => {
                    if (index === 0) {
                        content.classList.add('active');
                    } else {
                        content.classList.remove('active');
                    }
                });
            }
        }, 100);
        
        tabHeaders.forEach((header, index) => {
            header.addEventListener('click', function() {
                const targetTab = this.dataset.tab;
                
                // Remove active class from all elements
                tabHeaders.forEach(h => h.classList.remove('active'));
                tabPanels.forEach(p => p.classList.remove('active'));
                tabContents.forEach(c => c.classList.remove('active'));
                
                // Add active class to clicked elements
                this.classList.add('active');
                document.getElementById(targetTab).classList.add('active');
                if (tabContents[index]) {
                    tabContents[index].classList.add('active');
                }
            });
        });
        
        // Smooth scrolling for anchor links
        setTimeout(() => {
            const anchorLinks = document.querySelectorAll('.prostudio-anchor-links .anchor-link');
            anchorLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const targetId = this.getAttribute('href').substring(1);
                    const targetElement = document.getElementById(targetId);
                    if (targetElement) {
                        targetElement.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                        if (history.pushState) {
                            history.pushState(null, null, '#' + targetId);
                        }
                    }
                });
            });
        }, 1000);
    });
    </script>
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