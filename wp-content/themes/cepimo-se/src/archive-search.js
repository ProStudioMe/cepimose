console.log("Archive search script loaded!");

document.addEventListener("DOMContentLoaded", function () {
  const searchInput = document.querySelector(".archive-search-field");
  const clearButton = document.querySelector(".archive-search-clear");
  const postItems = document.querySelectorAll(".post-item");
  const noResultsMessage = document.querySelector(".wp-block-query-no-results");
  const queryContainer = document.getElementById("archive-query");

  if (!searchInput || !postItems.length) {
    console.log("Missing required elements - search disabled");
    return;
  }

  function performSearch() {
    const searchTerm = searchInput.value.toLowerCase().trim();
    console.log("Searching for:", searchTerm);
    let visiblePosts = 0;

    // Show/hide clear button
    if (clearButton) {
      clearButton.style.display = searchTerm ? "block" : "none";
    }

    postItems.forEach((post) => {
      const li = post.closest(".wp-block-post");
      if (!li) return;

      const title =
        post.querySelector(".wp-block-post-title")?.textContent.toLowerCase() ||
        "";
      const excerpt =
        post
          .querySelector(".wp-block-post-excerpt")
          ?.textContent.toLowerCase() || "";
      const categories = Array.from(
        post.querySelectorAll(".wp-block-post-terms a")
      )
        .map((cat) => cat.textContent.toLowerCase())
        .join(" ");
      const allText = post.textContent.toLowerCase();

      const matches =
        title.includes(searchTerm) ||
        excerpt.includes(searchTerm) ||
        categories.includes(searchTerm) ||
        allText.includes(searchTerm);

      if (matches || searchTerm === "") {
        li.style.display = "block";
        visiblePosts++;
      } else {
        li.style.display = "none";
      }
    });

    console.log("Visible posts:", visiblePosts);

    // Show/hide no results message
    if (noResultsMessage) {
      if (visiblePosts === 0 && searchTerm !== "") {
        noResultsMessage.style.display = "block";
      } else {
        noResultsMessage.style.display = "none";
      }
    }

    // Hide pagination when searching
    const pagination = queryContainer.querySelector(
      ".wp-block-query-pagination"
    );
    if (pagination) {
      pagination.style.display = searchTerm ? "none" : "block";
    }
  }

  // Instant search on input
  searchInput.addEventListener("input", performSearch);
  console.log("Search input listener added");

  // Clear button functionality
  if (clearButton) {
    clearButton.addEventListener("click", function () {
      searchInput.value = "";
      performSearch();
      searchInput.focus();
    });
    console.log("Clear button listener added");
  }

  // Also handle browser clear (X) on input
  searchInput.addEventListener("search", function () {
    if (this.value === "") {
      performSearch();
    }
  });
});
