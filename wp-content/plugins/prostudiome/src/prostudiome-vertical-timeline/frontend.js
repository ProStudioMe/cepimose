/**
 * Scroll-triggered animations for Vertical Timeline Block
 */

document.addEventListener('DOMContentLoaded', function() {
    // Function to check if element is in viewport
    function isInViewport(element) {
        const rect = element.getBoundingClientRect();
        const windowHeight = window.innerHeight || document.documentElement.clientHeight;
        
        // Trigger animation when element is 20% visible
        return rect.top < windowHeight * 0.8 && rect.bottom > 0;
    }

    // Function to animate timeline items
    function animateTimelineItems() {
        const timelineItems = document.querySelectorAll('.prostudiome-vertical-timeline .timeline-item');
        
        timelineItems.forEach((item, index) => {
            if (isInViewport(item) && !item.classList.contains('animate')) {
                // Add a small delay for staggered effect
                setTimeout(() => {
                    item.classList.add('animate');
                }, index * 100); // 100ms delay between each item
            }
        });
    }

    // Initial check
    animateTimelineItems();

    // Listen for scroll events with throttling
    let ticking = false;
    function handleScroll() {
        if (!ticking) {
            requestAnimationFrame(() => {
                animateTimelineItems();
                ticking = false;
            });
            ticking = true;
        }
    }

    // Add scroll listener
    window.addEventListener('scroll', handleScroll, { passive: true });

    // Also check on resize
    window.addEventListener('resize', handleScroll, { passive: true });
});
