(function () {
  "use strict";

  // Prevent conflicts with other scripts
  const BLOG_FEATURES_NAMESPACE = "CepimoBlogFeatures";

  // Only run if not already initialized
  if (window[BLOG_FEATURES_NAMESPACE]) {
    console.log("🔄 Blog features already initialized, skipping...");
    return;
  }

  console.log("🚀 Blog features script loading...");

  // Mark as initialized
  window[BLOG_FEATURES_NAMESPACE] = true;

  // Blog Features Class
  class BlogFeatures {
    constructor() {
      console.log('🚀 BlogFeatures constructor called');
      this.currentPostId = this.getCurrentPostId();
      this.currentPostUrl = window.location.href;
      this.currentPostTitle = document.title;
      console.log('📊 BlogFeatures initialized with:', {
        postId: this.currentPostId,
        url: this.currentPostUrl,
        title: this.currentPostTitle
      });
      this.init();
    }

    init() {
      console.log('🔧 Initializing blog features...');
      this.initReadingTime();
      this.initAnchorLinks();
      this.initLatestPosts();
      this.initShareButtons();
      console.log('✅ Blog features initialization complete');
    }

    getCurrentPostId() {
      // Try to get post ID from body class or data attribute
      const body = document.body;
      const postIdMatch = body.className.match(/postid-(\d+)/);
      if (postIdMatch) {
        return postIdMatch[1];
      }
      
      // Fallback: try to get from meta tag
      const metaPostId = document.querySelector('meta[name="post-id"]');
      if (metaPostId) {
        return metaPostId.getAttribute('content');
      }
      
      return null;
    }

    initReadingTime() {
      const readingTimeElement = document.querySelector('.reading-time-text');
      if (!readingTimeElement) return;

      const content = document.querySelector('.wp-block-post-content');
      if (!content) return;

      const text = content.textContent || content.innerText;
      const wordCount = text.trim().split(/\s+/).length;
      const readingTime = Math.ceil(wordCount / 200); // Average reading speed: 200 words per minute

      readingTimeElement.textContent = `${readingTime} min branja`;
    }

    initAnchorLinks() {
      const anchorContainer = document.querySelector('.anchor-links-container');
      if (!anchorContainer) return;

      // Find the parent section to hide/show
      const anchorSection = anchorContainer.closest('.anchor-links-section');
      if (!anchorSection) return;

      const headings = document.querySelectorAll('.wp-block-post-content h2, .wp-block-post-content h3, .wp-block-post-content h4');
      
      // If no headings found, hide the entire section
      if (headings.length === 0) {
        anchorSection.style.display = 'none';
        return;
      }

      // Show the section if it was previously hidden
      anchorSection.style.display = 'block';

      const anchorLinks = [];
      
      headings.forEach((heading, index) => {
        const level = parseInt(heading.tagName.charAt(1));
        const text = heading.textContent.trim();
        const id = `heading-${index + 1}`;
        
        // Add ID to heading if it doesn't have one
        if (!heading.id) {
          heading.id = id;
        }

        const link = document.createElement('a');
        link.href = `#${heading.id}`;
        link.className = `anchor-link anchor-link-level-${level}`;
        link.textContent = text;
        
        // Smooth scroll behavior
        link.addEventListener('click', (e) => {
          e.preventDefault();
          const target = document.querySelector(link.getAttribute('href'));
          if (target) {
            target.scrollIntoView({
              behavior: 'smooth',
              block: 'start'
            });
          }
        });

        anchorLinks.push(link);
      });

      // Clear container and add links
      anchorContainer.innerHTML = '';
      anchorLinks.forEach(link => {
        anchorContainer.appendChild(link);
      });
    }

    async initLatestPosts() {
      const latestPostsContainer = document.querySelector('.latest-posts-container');
      if (!latestPostsContainer) {
        console.log('Latest posts container not found');
        return;
      }

      console.log('Initializing latest posts from "novice" category...');

      // Show sample posts immediately for testing
      console.log('Showing sample posts for testing...');
      const samplePosts = this.getSamplePosts();
      latestPostsContainer.innerHTML = samplePosts;

      try {
        // Get the "novice" category ID
        const noviceCategoryId = await this.getNoviceCategoryId();
        console.log('Novice category ID:', noviceCategoryId);

        if (!noviceCategoryId) {
          console.log('Novice category not found, showing sample posts');
          return;
        }

        // Fetch latest posts from the "novice" category
        const posts = await this.fetchLatestPosts([noviceCategoryId], 5);
        console.log('Fetched posts from novice category:', posts);
        this.renderLatestPosts(latestPostsContainer, posts);
      } catch (error) {
        console.error('Error loading latest posts:', error);
        // Keep sample posts if API fails
        console.log('Keeping sample posts due to API error');
      }
    }

    getCurrentPostCategories() {
      // Try to get categories from meta tags or body classes
      const categoryMeta = document.querySelector('meta[name="category"]');
      if (categoryMeta) {
        return [categoryMeta.getAttribute('content')];
      }

      // Try to get from body classes
      const body = document.body;
      const categoryMatches = body.className.match(/category-([a-zA-Z0-9-]+)/g);
      if (categoryMatches) {
        return categoryMatches.map(match => match.replace('category-', ''));
      }

      // Try to get from WordPress REST API
      if (this.currentPostId) {
        return this.getCategoriesFromAPI();
      }

      return [];
    }

    async getCategoriesFromAPI() {
      try {
        const response = await fetch(`${window.location.origin}/wp-json/wp/v2/posts/${this.currentPostId}`);
        if (response.ok) {
          const post = await response.json();
          return post.categories || [];
        }
      } catch (error) {
        console.error('Error fetching post categories:', error);
      }
      return [];
    }

    async getNoviceCategoryId() {
      try {
        const response = await fetch(`${window.location.origin}/wp-json/wp/v2/categories?slug=novice`);
        if (response.ok) {
          const categories = await response.json();
          if (categories && categories.length > 0) {
            return categories[0].id;
          }
        }
      } catch (error) {
        console.error('Error fetching novice category:', error);
      }
      return null;
    }

    async fetchLatestPosts(categories, limit = 5) {
      const apiUrl = `${window.location.origin}/wp-json/wp/v2/posts`;
      const params = new URLSearchParams({
        per_page: limit,
        _embed: '1',
        exclude: this.currentPostId || ''
      });

      // Add categories if provided
      if (categories && categories.length > 0) {
        params.append('categories', categories.join(','));
      }

      console.log('Fetching posts from:', `${apiUrl}?${params}`);

      try {
        const response = await fetch(`${apiUrl}?${params}`);
        console.log('API Response status:', response.status);
        
        if (!response.ok) {
          throw new Error(`Failed to fetch posts: ${response.status}`);
        }

        const posts = await response.json();
        console.log('API Response posts:', posts);
        return posts;
      } catch (error) {
        console.error('Error fetching posts:', error);
        throw error;
      }
    }

    renderLatestPosts(container, posts) {
      if (!posts || posts.length === 0) {
        // Show sample posts for testing if no real posts are found
        console.log('No posts found, showing sample posts');
        const samplePosts = this.getSamplePosts();
        container.innerHTML = samplePosts;
        return;
      }

      const postsHTML = posts.map(post => {
        const featuredImage = post._embedded?.['wp:featuredmedia']?.[0]?.source_url;
        const imageUrl = featuredImage || '/wp-content/themes/cepimo-se/assets/default-post-image.svg';
        const date = new Date(post.date).toLocaleDateString('sl-SI', {
          day: 'numeric',
          month: 'short',
          year: 'numeric'
        });

        return `
          <a href="${post.link}" class="latest-post-card">
            <img src="${imageUrl}" alt="${post.title.rendered}" class="latest-post-image">
            <div class="latest-post-content">
              <h4 class="latest-post-title">${post.title.rendered}</h4>
              <div class="latest-post-date">${date}</div>
            </div>
          </a>
        `;
      }).join('');

      container.innerHTML = postsHTML;
    }

    getSamplePosts() {
      const samplePosts = [
        {
          title: 'Nove smernice za cepljenje v letu 2024',
          date: '15. jun 2024',
          image: '/wp-content/themes/cepimo-se/assets/default-post-image.svg'
        },
        {
          title: 'Aktualne novice o cepivih proti sezonskim boleznim',
          date: '12. jun 2024',
          image: '/wp-content/themes/cepimo-se/assets/default-post-image.svg'
        },
        {
          title: 'Pomembne spremembe v ceplilnem programu',
          date: '10. jun 2024',
          image: '/wp-content/themes/cepimo-se/assets/default-post-image.svg'
        },
        {
          title: 'Najnovejše informacije o varnosti cepiv',
          date: '8. jun 2024',
          image: '/wp-content/themes/cepimo-se/assets/default-post-image.svg'
        },
        {
          title: 'Aktualizacija priporočil za cepljenje otrok',
          date: '5. jun 2024',
          image: '/wp-content/themes/cepimo-se/assets/default-post-image.svg'
        }
      ];

      return samplePosts.map(post => `
        <a href="#" class="latest-post-card">
          <img src="${post.image}" alt="${post.title}" class="latest-post-image">
          <div class="latest-post-content">
            <h4 class="latest-post-title">${post.title}</h4>
            <div class="latest-post-date">${post.date}</div>
          </div>
        </a>
      `).join('');
    }

    initShareButtons() {
      // Facebook Share
      const facebookBtn = document.querySelector('.share-button.facebook');
      if (facebookBtn) {
        facebookBtn.addEventListener('click', (e) => {
          e.preventDefault();
          this.shareOnFacebook();
        });
      }

      // Twitter Share
      const twitterBtn = document.querySelector('.share-button.twitter');
      if (twitterBtn) {
        twitterBtn.addEventListener('click', (e) => {
          e.preventDefault();
          this.shareOnTwitter();
        });
      }

      // LinkedIn Share
      const linkedinBtn = document.querySelector('.share-button.linkedin');
      if (linkedinBtn) {
        linkedinBtn.addEventListener('click', (e) => {
          e.preventDefault();
          this.shareOnLinkedIn();
        });
      }

      // Email Share
      const emailBtn = document.querySelector('.share-button.email');
      if (emailBtn) {
        emailBtn.addEventListener('click', (e) => {
          e.preventDefault();
          this.shareViaEmail();
        });
      }
    }

    shareOnFacebook() {
      const url = encodeURIComponent(this.currentPostUrl);
      const title = encodeURIComponent(this.currentPostTitle);
      const shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${url}`;
      this.openShareWindow(shareUrl, 'Facebook Share');
    }

    shareOnTwitter() {
      const url = encodeURIComponent(this.currentPostUrl);
      const title = encodeURIComponent(this.currentPostTitle);
      const shareUrl = `https://twitter.com/intent/tweet?url=${url}&text=${title}`;
      this.openShareWindow(shareUrl, 'Twitter Share');
    }

    shareOnLinkedIn() {
      const url = encodeURIComponent(this.currentPostUrl);
      const title = encodeURIComponent(this.currentPostTitle);
      const shareUrl = `https://www.linkedin.com/sharing/share-offsite/?url=${url}`;
      this.openShareWindow(shareUrl, 'LinkedIn Share');
    }

    shareViaEmail() {
      const subject = encodeURIComponent(this.currentPostTitle);
      const body = encodeURIComponent(`Preberite ta članek: ${this.currentPostTitle}\n\n${this.currentPostUrl}`);
      const mailtoUrl = `mailto:?subject=${subject}&body=${body}`;
      window.location.href = mailtoUrl;
    }

    openShareWindow(url, title) {
      const width = 600;
      const height = 400;
      const left = (window.innerWidth - width) / 2;
      const top = (window.innerHeight - height) / 2;
      
      window.open(
        url,
        title,
        `width=${width},height=${height},left=${left},top=${top},scrollbars=yes,resizable=yes`
      );
    }
  }

  // Initialize when DOM is ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
      new BlogFeatures();
    });
  } else {
    new BlogFeatures();
  }

})();
