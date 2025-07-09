function performSearch(query, resultsElement) {
  console.log("[search-modal.js] performSearch called", query);
  if (!resultsElement) return;
  resultsElement.innerHTML = `
    <div class="search-loading">
      <div class="search-loading-spinner"></div>
      <span class="search-loading-text">Iskanje...</span>
    </div>
  `;
  fetch(
    `/wp-json/wp/v2/search?search=${encodeURIComponent(
      query
    )}&subtype=post&per_page=5`
  )
    .then((response) => response.json())
    .then((data) => {
      displaySearchResults(data, query, resultsElement);
    })
    .catch((error) => {
      console.error("Search error:", error);
      resultsElement.innerHTML = `
        <div class="search-no-results">
          <div class="search-no-results-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
            </svg>
          </div>
          <p class="search-no-results-title">Prišlo je do napake pri iskanju</p>
          <p class="search-no-results-subtitle">Poskusite znova</p>
        </div>
      `;
    });
}
window.performSearch = performSearch;

function displaySearchResults(results, query, resultsElement) {
  if (!resultsElement) return;
  if (results.length === 0) {
    resultsElement.innerHTML = `
      <div class="search-no-results">
        <div class="search-no-results-icon">
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
          </svg>
        </div>
        <p class="search-no-results-title">Ni najdenih rezultatov</p>
        <p class="search-no-results-subtitle">Ni najdenih rezultatov za "<strong>${query}</strong>"</p>
      </div>
    `;
    return;
  }
  const resultsHtml = results
    .map((result) => {
      return `
        <a href="${result.url}" class="search-result-item">
          <h4 class="search-result-title">${result.title}</h4>
        </a>
      `;
    })
    .join("");
  resultsElement.innerHTML = `
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
      )}" class="inline-flex items-center px-6 py-3 no-underline bg-blue-middle text-white rounded-xl hover:bg-blue-middle/90 transition-colors duration-200 font-medium">
        Prikaži vse rezultate
        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
      </a>
    </div>
  `;
}
window.displaySearchResults = displaySearchResults;

// Function to attach search input event listeners
function attachSearchInputListeners(inputElement) {
  if (!inputElement) return;
  let resultsElement = document.querySelector(".search-modal-results");

  // Prevent form submission on Enter key
  const searchForm = inputElement.closest("form");
  if (searchForm) {
    searchForm.addEventListener("submit", function (e) {
      e.preventDefault();
      return false;
    });
  }

  // Prevent any default form behavior
  inputElement.addEventListener("keypress", function (e) {
    if (e.key === "Enter") {
      e.preventDefault();
      e.stopPropagation();
    }
  });

  inputElement.addEventListener("input", function (e) {
    // Prevent ACF field conflicts
    if (e.target.hasAttribute("data-prevent-acf-conflict")) {
      e.stopPropagation();
      e.stopImmediatePropagation();
    }

    console.log("[search-modal.js] searchInput input event", e.target.value);
    const query = e.target.value.trim();

    // Clear previous timeout
    clearTimeout(inputElement._searchTimeout);

    if (query.length < 2) {
      // Show initial state for short queries
      if (resultsElement) {
        resultsElement.innerHTML = `
          <div class=\"text-center py-12\">
            <div class=\"w-16 h-16 mx-auto mb-4 text-gray-300\">
              <svg fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\">
                <path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z\"></path>
              </svg>
            </div>
            <p class=\"text-gray-500\">Začnite tipkati za iskanje...</p>
          </div>
        `;
      }
      return;
    }

    // Debounce search
    inputElement._searchTimeout = setTimeout(() => {
      console.log(
        "[search-modal.js] About to call window.performSearch:",
        typeof window.performSearch,
        window.performSearch
      );
      window.performSearch(query, resultsElement);
    }, 300);
  });

  // Handle form submission
  inputElement.addEventListener("keydown", function (e) {
    // Prevent ACF field conflicts
    if (e.target.hasAttribute("data-prevent-acf-conflict")) {
      e.stopPropagation();
      e.stopImmediatePropagation();
    }

    if (e.key === "Enter") {
      e.preventDefault();
      e.stopPropagation();
      const query = inputElement.value.trim();
      if (query) {
        // Close modal first
        closeSearchModal();
        // Then redirect to search results page
        setTimeout(() => {
          window.location.href = `/?s=${encodeURIComponent(query)}`;
        }, 100);
      }
    } else if (e.key === "Escape") {
      e.preventDefault();
      closeSearchModal();
    }
  });
}

function initSearchModal() {
  // Search modal functionality
  const searchButton = document.querySelector(".search-submit");
  const searchModal = document.querySelector(".search-modal-overlay");
  const searchDrawer = document.querySelector(".search-modal-drawer");
  const closeButton = document.querySelector(".search-modal-close");
  let searchInput = document.querySelector("#modal-search-input");
  let searchResults = document.querySelector(".search-modal-results");
  const searchSuggestions = document.querySelector(".search-suggestions");

  console.log("[search-modal.js] Elements found:", {
    searchButton: !!searchButton,
    searchModal: !!searchModal,
    searchDrawer: !!searchDrawer,
    closeButton: !!closeButton,
    searchInput: !!searchInput,
    searchResults: !!searchResults,
  });

  // Debug: Track modal button clicks
  if (searchButton) {
    searchButton.onclick = null;
    searchButton.removeAttribute("onclick");
    searchButton.addEventListener("click", function (e) {
      console.log("[search-modal.js] Modal button clicked");
      e.preventDefault();
      e.stopPropagation();
      openSearchModal();
      return false;
    });
  }

  // Global prevention of navigation when modal is active
  let modalIsActive = false;

  function openSearchModal() {
    console.log("[search-modal.js] openSearchModal called");
    modalIsActive = true;
    if (searchModal && searchDrawer) {
      if (searchInput) searchInput.value = "";
      if (searchResults) searchResults.innerHTML = "";
      searchModal.classList.remove("hidden");
      searchModal.classList.add("show");
      searchModal.offsetHeight;
      searchDrawer.classList.add("open");

      // Prevent ACF field conflicts on search pages
      if (searchInput) {
        // Temporarily disable ACF field watchers that might interfere
        const acfFields = document.querySelectorAll(
          ".acf-field-type-search-results, .select2-search__field, .select2-results:not(.search-modal-results)"
        );
        acfFields.forEach((field) => {
          if (field && typeof field.blur === "function") {
            field.blur();
          }
          // Temporarily disable ACF event listeners
          field.style.pointerEvents = "none";
        });

        setTimeout(() => {
          if (searchInput) {
            console.log(
              "[search-modal.js] focusing searchInput with ACF protection"
            );
            // Add extra protection against ACF interference
            searchInput.setAttribute("data-prevent-acf-conflict", "true");
            searchInput.focus();

            // Re-enable ACF fields after modal is stable
            setTimeout(() => {
              acfFields.forEach((field) => {
                field.style.pointerEvents = "";
              });
            }, 1000);
          }
        }, 500);
      }
      document.body.style.overflow = "hidden";
    }
  }

  function closeSearchModal() {
    modalIsActive = false;
    if (searchModal && searchDrawer) {
      searchModal.classList.add("hiding");
      searchDrawer.classList.add("closing");
      setTimeout(() => {
        searchModal.classList.remove("show", "hiding");
        searchDrawer.classList.remove("open", "closing");
        searchModal.classList.add("hidden");
        document.body.style.overflow = "";
        if (searchResults) {
          searchResults.innerHTML = "";
        }
        if (searchInput) {
          searchInput.value = "";
        }
      }, 500);
    }
  }
  window.closeSearchModal = closeSearchModal;

  if (closeButton) {
    closeButton.addEventListener("click", closeSearchModal);
  }
  if (searchModal) {
    searchModal.addEventListener("click", function (e) {
      if (e.target === searchModal) {
        closeSearchModal();
      }
    });
  }
  document.addEventListener("keydown", function (e) {
    if (
      e.key === "Escape" &&
      searchModal &&
      !searchModal.classList.contains("hidden")
    ) {
      closeSearchModal();
    }
  });
  if (searchInput) {
    attachSearchInputListeners(searchInput);
  }
  const modalForm = document.querySelector(".search-form-container");
  const modalInput = document.getElementById("modal-search-input");
  if (modalForm) {
    modalForm.addEventListener("submit", function (e) {
      e.preventDefault();
      e.stopPropagation();
      return false;
    });
  }
  if (modalInput) {
    modalInput.addEventListener("keydown", function (e) {
      if (e.key === "Enter") {
        e.preventDefault();
        e.stopPropagation();
        return false;
      }
    });
  }
}

document.addEventListener("DOMContentLoaded", function () {
  initSearchModal();
});

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
