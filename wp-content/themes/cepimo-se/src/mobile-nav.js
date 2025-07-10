document.addEventListener("DOMContentLoaded", function () {
  function handleMobileNavigation() {
    const isMobile = window.innerWidth <= 768;

    // Find all navigation items with submenus
    const navItems = document.querySelectorAll(
      ".wp-block-navigation-item.has-child"
    );

    // Handle all submenu links
    const submenuLinks = document.querySelectorAll(
      ".wp-block-navigation__submenu-container .wp-block-navigation-item__content"
    );

    submenuLinks.forEach((link) => {
      link.removeEventListener("click", handleSubmenuLinkClick);
      if (isMobile) {
        link.addEventListener("click", handleSubmenuLinkClick);
      }
    });

    navItems.forEach((item) => {
      // Remove click listeners
      item.removeEventListener("click", handleNavClick);

      if (isMobile) {
        // On mobile: remove hover classes and add click listener
        item.classList.remove("open-on-hover-click");
        item.addEventListener("click", handleNavClick);

        // Also handle clicks on the navigation item content to prevent page refresh
        const navContent = item.querySelector(
          ".wp-block-navigation-item__content"
        );
        if (navContent) {
          navContent.removeEventListener("click", handleContentClick);
          navContent.addEventListener("click", handleContentClick);
        }

        // Prevent default WordPress hover behavior on mobile
        item.removeAttribute("data-wp-on-async--mouseenter");
        item.removeAttribute("data-wp-on-async--mouseleave");
        item.removeAttribute("data-wp-on-async--focus");
      } else {
        // On desktop: restore hover classes and attributes
        item.classList.add("open-on-hover-click");
        item.setAttribute(
          "data-wp-on-async--mouseenter",
          "actions.openMenuOnHover"
        );
        item.setAttribute(
          "data-wp-on-async--mouseleave",
          "actions.closeMenuOnHover"
        );

        // Remove mobile-specific click handler from content
        const navContent = item.querySelector(
          ".wp-block-navigation-item__content"
        );
        if (navContent) {
          navContent.removeEventListener("click", handleContentClick);
        }
      }
    });
  }

  function handleSubmenuLinkClick(e) {
    if (window.innerWidth <= 768) {
      // Let the link work normally - don't prevent default
      // But stop the event from bubbling up to prevent WordPress handlers
      e.stopPropagation();

      // Close all open submenus after a short delay (after navigation starts)
      setTimeout(() => {
        document
          .querySelectorAll(".wp-block-navigation__submenu-container.is-open")
          .forEach((submenu) => {
            submenu.classList.remove("is-open");
            const parentItem = submenu.closest(".wp-block-navigation-item");
            if (parentItem) {
              parentItem.classList.remove("is-active");
              const button = parentItem.querySelector(
                ".wp-block-navigation-submenu__toggle"
              );
              if (button) {
                button.setAttribute("aria-expanded", "false");
              }
            }
          });
      }, 100);
    }
  }

  function handleContentClick(e) {
    if (window.innerWidth <= 768) {
      // On mobile, prevent the default link behavior
      e.preventDefault();
      e.stopPropagation();

      // Find the parent navigation item and trigger its click handler
      const parentItem = this.closest(".wp-block-navigation-item.has-child");
      if (parentItem) {
        parentItem.click();
      }
    }
  }

  function handleNavClick(e) {
    if (window.innerWidth > 768) return; // Only handle clicks on mobile

    const navItem = this;
    const submenu = navItem.querySelector(
      ".wp-block-navigation__submenu-container"
    );
    const button = navItem.querySelector(
      ".wp-block-navigation-submenu__toggle"
    );

    if (submenu) {
      e.preventDefault();
      e.stopPropagation();

      // Close sibling submenus
      const parentUl = navItem.closest("ul");
      if (parentUl) {
        parentUl
          .querySelectorAll(".wp-block-navigation__submenu-container")
          .forEach((menu) => {
            if (menu !== submenu) {
              menu.classList.remove("is-open");
              const parentItem = menu.closest(".wp-block-navigation-item");
              if (parentItem) {
                parentItem.classList.remove("is-active");
                const parentButton = parentItem.querySelector(
                  ".wp-block-navigation-submenu__toggle"
                );
                if (parentButton) {
                  parentButton.setAttribute("aria-expanded", "false");
                }
              }
            }
          });
      }

      // Toggle current submenu
      const isOpen = submenu.classList.contains("is-open");
      submenu.classList.toggle("is-open");
      navItem.classList.toggle("is-active");

      // Update aria-expanded state
      if (button) {
        button.setAttribute("aria-expanded", !isOpen ? "true" : "false");
      }
    }
  }

  // Run on initial load
  handleMobileNavigation();

  // Run on resize with debounce
  let resizeTimer;
  window.addEventListener("resize", function () {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(handleMobileNavigation, 250);
  });
});
