(function () {
  "use strict";

  // Prevent conflicts with other scripts
  const SIDEBAR_NAMESPACE = "CepimoSidebarTabs";

  // Only run if not already initialized
  if (window[SIDEBAR_NAMESPACE]) {
    console.log("🔄 Sidebar tabs already initialized, skipping...");
    return;
  }

  console.log("🚀 Sidebar tabs script loading (external file)...");

  /*
   * Mobile Drawer Behavior:
   * ✅ STAYS OPEN when switching between tabs
   * ❌ CLOSES when clicking anchor links within tabs
   * ❌ CLOSES when clicking related post links
   * ❌ CLOSES when manually clicking the toggle button
   */

  // Mark as initialized
  window[SIDEBAR_NAMESPACE] = true;

  // Conflict prevention utilities
  function preventEventConflicts(element) {
    if (!element) return;

    // Stop propagation for conflicting events
    const conflictEvents = ["focus", "blur", "change", "input"];
    conflictEvents.forEach((eventType) => {
      element.addEventListener(
        eventType,
        function (e) {
          e.stopPropagation();
        },
        true
      );
    });
  }

  function initMobileDrawer() {
    // Use more specific selector to avoid conflicts
    const asideWrapper = document.querySelector(
      ".wp-block-group.aside-tab-wrapper"
    );
    const mobileToggle = document.getElementById("aside-mobile-toggle");

    console.log("🔍 Mobile drawer elements check:", {
      wrapper: !!asideWrapper,
      toggle: !!mobileToggle,
      toggleVisible: mobileToggle
        ? getComputedStyle(mobileToggle).display !== "none"
        : false,
    });

    if (!asideWrapper || !mobileToggle) {
      console.warn("⚠️ Missing required elements for mobile drawer");
      return;
    }

    // Clear any existing event listeners more aggressively
    const cleanToggle = mobileToggle.cloneNode(true);
    mobileToggle.parentNode.replaceChild(cleanToggle, mobileToggle);

    // Add protected click handler
    cleanToggle.addEventListener(
      "click",
      function (e) {
        console.log("🎯 Mobile toggle clicked!");
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();

        asideWrapper.classList.toggle("mobile-expanded");
        console.log(
          "📱 Drawer state:",
          asideWrapper.classList.contains("mobile-expanded")
            ? "expanded"
            : "collapsed"
        );
      },
      { capture: true }
    );

    // Prevent conflicts
    preventEventConflicts(cleanToggle);

    console.log("✅ Mobile drawer initialized with conflict protection");
    return cleanToggle;
  }

  function initTabs() {
    // Use more specific selectors
    const tabHeaders = document.querySelectorAll(
      ".wp-block-group.aside-tab-wrapper .custom-tabs .tab-header"
    );
    const tabContents = document.querySelectorAll(
      ".wp-block-group.aside-tab-wrapper .custom-tab-content"
    );

    console.log("🔍 Tab elements search:", {
      headers: tabHeaders.length,
      contents: tabContents.length,
      headerElements: Array.from(tabHeaders).map((h) => h.textContent.trim()),
      contentElements: Array.from(tabContents).map((c) => c.className),
    });

    if (tabHeaders.length === 0 || tabContents.length === 0) {
      console.warn("⚠️ Missing tab elements - headers or contents not found");
      return;
    }

    // Ensure first tab content is active on load
    tabContents.forEach((content, index) => {
      if (index === 0) {
        content.classList.add("active");
        console.log("✅ Set first tab content as active:", content.className);
      } else {
        content.classList.remove("active");
      }
    });

    // Add event listeners with conflict protection
    tabHeaders.forEach((header, index) => {
      // Clear any existing listeners
      const cleanHeader = header.cloneNode(true);
      header.parentNode.replaceChild(cleanHeader, header);

      cleanHeader.addEventListener(
        "click",
        function (e) {
          console.log(
            `🎯 Tab ${index + 1} clicked (${cleanHeader.textContent.trim()})`
          );
          e.preventDefault();
          e.stopPropagation();
          e.stopImmediatePropagation();

          // Remove active class from all headers
          document
            .querySelectorAll(
              ".wp-block-group.aside-tab-wrapper .custom-tabs .tab-header"
            )
            .forEach((h) => {
              h.classList.remove("active");
            });

          // Remove active class from all content
          document
            .querySelectorAll(
              ".wp-block-group.aside-tab-wrapper .custom-tab-content"
            )
            .forEach((c) => {
              c.classList.remove("active");
            });

          // Add active class to clicked header
          cleanHeader.classList.add("active");

          // Add active class to corresponding content
          const currentTabContents = document.querySelectorAll(
            ".wp-block-group.aside-tab-wrapper .custom-tab-content"
          );
          if (currentTabContents[index]) {
            currentTabContents[index].classList.add("active");
            console.log(
              `✅ Tab ${index + 1} content activated:`,
              currentTabContents[index].className
            );
          } else {
            console.warn(`❌ No content found for tab ${index + 1}`);
          }

          // Note: Mobile drawer stays open when switching tabs
        },
        { capture: true }
      );

      // Prevent conflicts
      preventEventConflicts(cleanHeader);
    });

    console.log("✅ Tabs initialized with conflict protection");
  }

  function initAnchorLinks() {
    setTimeout(() => {
      const anchorLinks = document.querySelectorAll(
        ".wp-block-group.aside-tab-wrapper .prostudio-anchor-links .anchor-link"
      );
      console.log("🔍 Found anchor links:", anchorLinks.length);

      anchorLinks.forEach((link) => {
        link.addEventListener(
          "click",
          function (e) {
            e.preventDefault();
            e.stopPropagation();

            const href = this.getAttribute("href");
            console.log("🔗 Anchor link clicked:", href);

            if (href && href.startsWith("#") && href.length > 1) {
              const targetId = href.substring(1);
              const targetElement = document.getElementById(targetId);

              if (targetElement) {
                targetElement.scrollIntoView({
                  behavior: "smooth",
                  block: "start",
                });

                // Update URL safely
                try {
                  if (history.pushState) {
                    history.pushState(null, null, href);
                  }
                } catch (e) {
                  console.warn("Could not update URL:", e);
                }
                console.log("✅ Scrolled to:", targetId);
              } else {
                console.warn("❌ Target element not found:", targetId);
              }
            }

            // Close mobile drawer
            if (window.innerWidth <= 768) {
              const wrapper = document.querySelector(
                ".wp-block-group.aside-tab-wrapper"
              );
              if (wrapper) wrapper.classList.remove("mobile-expanded");
            }
          },
          { capture: true }
        );
      });

      // Handle related posts links
      const relatedLinks = document.querySelectorAll(
        ".wp-block-group.aside-tab-wrapper .wp-block-prostudiome-same-category-posts a"
      );
      console.log("🔍 Found related post links:", relatedLinks.length);

      relatedLinks.forEach((link) => {
        link.addEventListener(
          "click",
          function () {
            console.log("🔗 Related post link clicked");
            if (window.innerWidth <= 768) {
              const wrapper = document.querySelector(
                ".wp-block-group.aside-tab-wrapper"
              );
              if (wrapper) wrapper.classList.remove("mobile-expanded");
            }
          },
          { capture: true }
        );
      });
    }, 1000);
  }

  function initSidebar() {
    console.log(
      "🚀 Initializing sidebar components with conflict protection..."
    );

    try {
      initMobileDrawer();

      // Wait a bit for WordPress blocks to render
      setTimeout(() => {
        initTabs();
        initAnchorLinks();
      }, 500);
    } catch (error) {
      console.error("❌ Error initializing sidebar:", error);
    }
  }

  // Multiple initialization strategies to handle conflicts
  function safeInit() {
    console.log("📍 DOM ready state:", document.readyState);

    // Strategy 1: DOM ready
    if (document.readyState === "loading") {
      document.addEventListener("DOMContentLoaded", initSidebar);
    } else {
      initSidebar();
    }

    // Strategy 2: Fallback with retries
    let retryCount = 0;
    const maxRetries = 5;

    function retryInit() {
      setTimeout(
        () => {
          const tabHeaders = document.querySelectorAll(
            ".wp-block-group.aside-tab-wrapper .custom-tabs .tab-header"
          );
          const tabContents = document.querySelectorAll(
            ".wp-block-group.aside-tab-wrapper .custom-tab-content"
          );

          if (tabHeaders.length > 0 && tabContents.length > 0) {
            console.log("🔄 Retry successful, initializing...");
            initTabs();
          } else if (retryCount < maxRetries) {
            retryCount++;
            console.log(
              `🔄 Retry ${retryCount}/${maxRetries} - elements not ready yet...`
            );
            retryInit();
          } else {
            console.warn(
              "⚠️ Max retries reached - sidebar elements may not be available"
            );
          }
        },
        1000 + retryCount * 500
      ); // Increasing delay
    }

    retryInit();
  }

  // Initialize
  safeInit();

  // Expose for debugging
  window.CepimoSidebarDebug = {
    reinit: initSidebar,
    checkElements: function () {
      return {
        headers: document.querySelectorAll(
          ".wp-block-group.aside-tab-wrapper .custom-tabs .tab-header"
        ).length,
        contents: document.querySelectorAll(
          ".wp-block-group.aside-tab-wrapper .custom-tab-content"
        ).length,
        mobileToggle: !!document.getElementById("aside-mobile-toggle"),
      };
    },
  };
})();
