document.addEventListener("DOMContentLoaded", function () {
  // Search modal functionality
  const searchButton = document.querySelector(".search-submit");
  const searchModal = document.querySelector(".search-modal-overlay");
  const searchDrawer = document.querySelector(".search-modal-drawer");
  const closeButton = document.querySelector(".search-modal-close");
  const searchInput = document.querySelector(
    '.search-form-modern input[type="search"]'
  );
  const searchResults = document.querySelector(".search-results");
  const searchSuggestions = document.querySelector(".search-suggestions");

  // Open search modal
  function openSearchModal() {
    if (searchModal && searchDrawer) {
      searchModal.classList.remove("hidden");
      // Trigger reflow to ensure transition works
      searchModal.offsetHeight;
      searchDrawer.classList.remove("translate-x-full");
      searchDrawer.classList.add("translate-x-0");

      // Focus on search input after animation
      setTimeout(() => {
        if (searchInput) {
          searchInput.focus();
        }
      }, 300);

      // Prevent body scroll
      document.body.style.overflow = "hidden";
    }
  }

  // Close search modal
  function closeSearchModal() {
    if (searchModal && searchDrawer) {
      searchDrawer.classList.remove("translate-x-0");
      searchDrawer.classList.add("translate-x-full");

      // Hide modal after animation
      setTimeout(() => {
        searchModal.classList.add("hidden");
        // Restore body scroll
        document.body.style.overflow = "";
        // Clear search results
        if (searchResults) {
          searchResults.innerHTML = "";
        }
        // Show suggestions
        if (searchSuggestions) {
          searchSuggestions.style.display = "block";
        }
      }, 300);
    }
  }

  // Event listeners
  if (searchButton) {
    searchButton.addEventListener("click", function (e) {
      e.preventDefault();
      openSearchModal();
    });
  }

  if (closeButton) {
    closeButton.addEventListener("click", closeSearchModal);
  }

  // Close on overlay click
  if (searchModal) {
    searchModal.addEventListener("click", function (e) {
      if (e.target === searchModal) {
        closeSearchModal();
      }
    });
  }

  // Close on Escape key
  document.addEventListener("keydown", function (e) {
    if (
      e.key === "Escape" &&
      searchModal &&
      !searchModal.classList.contains("hidden")
    ) {
      closeSearchModal();
    }
  });

  // Search functionality
  if (searchInput) {
    let searchTimeout;

    searchInput.addEventListener("input", function (e) {
      const query = e.target.value.trim();

      // Clear previous timeout
      clearTimeout(searchTimeout);

      if (query.length < 2) {
        // Show suggestions for short queries
        if (searchResults) {
          searchResults.innerHTML = "";
        }
        if (searchSuggestions) {
          searchSuggestions.style.display = "block";
        }
        return;
      }

      // Hide suggestions
      if (searchSuggestions) {
        searchSuggestions.style.display = "none";
      }

      // Debounce search
      searchTimeout = setTimeout(() => {
        performSearch(query);
      }, 300);
    });

    // Handle form submission
    searchInput.closest("form")?.addEventListener("submit", function (e) {
      e.preventDefault();
      const query = searchInput.value.trim();
      if (query) {
        // Redirect to search results page
        window.location.href = `/?s=${encodeURIComponent(query)}`;
      }
    });
  }

  // Perform search via AJAX
  function performSearch(query) {
    if (!searchResults) return;

    // Show loading state
    searchResults.innerHTML = `
            <div class="flex items-center justify-center py-8">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                <span class="ml-3 text-gray-600">Iskanje...</span>
            </div>
        `;

    // Make AJAX request
    fetch(
      `/wp-json/wp/v2/search?search=${encodeURIComponent(
        query
      )}&subtype=post&per_page=5`
    )
      .then((response) => response.json())
      .then((data) => {
        displaySearchResults(data, query);
      })
      .catch((error) => {
        console.error("Search error:", error);
        searchResults.innerHTML = `
                    <div class="text-center py-8 text-gray-500">
                        Prišlo je do napake pri iskanju. Poskusite znova.
                    </div>
                `;
      });
  }

  // Display search results
  function displaySearchResults(results, query) {
    if (!searchResults) return;

    if (results.length === 0) {
      searchResults.innerHTML = `
                <div class="text-center py-8">
                    <div class="text-gray-400 mb-2">
                        <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <p class="text-gray-600">Ni najdenih rezultatov za "<strong>${query}</strong>"</p>
                    <p class="text-sm text-gray-500 mt-2">Poskusite z drugimi ključnimi besedami</p>
                </div>
            `;
      return;
    }

    const resultsHtml = results
      .map((result) => {
        return `
                <a href="${result.url}" class="block p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors duration-200 mb-3">
                    <h4 class="font-medium text-gray-900 mb-1 line-clamp-2">${result.title}</h4>
                    <p class="text-sm text-gray-600 line-clamp-2">${result.subtype}</p>
                </a>
            `;
      })
      .join("");

    searchResults.innerHTML = `
            <div class="mb-4">
                <h4 class="text-sm font-medium text-gray-700 mb-3">Rezultati iskanja (${
                  results.length
                })</h4>
            </div>
            <div class="space-y-3">
                ${resultsHtml}
            </div>
            <div class="mt-6 text-center">
                <a href="/?s=${encodeURIComponent(
                  query
                )}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                    Prikaži vse rezultate
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        `;
  }

  // Add line-clamp utility if not available
  if (!document.querySelector("style[data-line-clamp]")) {
    const style = document.createElement("style");
    style.setAttribute("data-line-clamp", "true");
    style.textContent = `
            .line-clamp-2 {
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }
        `;
    document.head.appendChild(style);
  }
});
