/**
 * Use this file for JavaScript code that you want to run in the front-end
 * on posts/pages that contain this block.
 *
 * When this file is defined as the value of the `viewScript` property
 * in `block.json` it will be enqueued on the front end of the site.
 *
 * Example:
 *
 * ```js
 * {
 *   "viewScript": "file:./view.js"
 * }
 * ```
 *
 * If you're not making any changes to this file because your project doesn't need any
 * JavaScript running in the front-end, then you should delete this file and remove
 * the `viewScript` property from `block.json`.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-metadata/#view-script
 */

/* eslint-disable no-console */
console.log("Hello World! (from prostudiome-prostudiome block)");
/* eslint-enable no-console */

/*!
 * Embla Carousel
 * Core code from https://www.embla-carousel.com/
 */
const EmblaCarousel = (function () {
	function createEmblaCarousel(emblaNode, options = {}) {
		const viewport = emblaNode;
		const container = viewport.querySelector(".embla__container");
		const slides = [...container.children];
		let index = 0;
		let scrolling = false;

		// Initialize
		container.style.transform = "translate3d(0px, 0px, 0px)";
		let slideWidth = slides[0].offsetWidth;

		function updateSlideWidth() {
			slideWidth = slides[0].offsetWidth;
		}

		function scrollTo(targetIndex) {
			if (scrolling) return;
			scrolling = true;

			const previousIndex = index;
			index = targetIndex;

			// Handle loop
			if (options.loop) {
				if (index < 0) index = slides.length - 1;
				if (index >= slides.length) index = 0;
			} else {
				if (index < 0) index = 0;
				if (index >= slides.length) index = slides.length - 1;
			}

			const offset = -index * slideWidth;
			container.style.transform = `translate3d(${offset}px, 0px, 0px)`;
			container.style.transition = "transform 0.3s ease-in-out";

			setTimeout(() => {
				container.style.transition = "";
				scrolling = false;
				if (previousIndex !== index) {
					viewport.dispatchEvent(new CustomEvent("select"));
				}
			}, 300);
		}

		// Public methods
		return {
			scrollPrev: () => scrollTo(index - 1),
			scrollNext: () => scrollTo(index + 1),
			canScrollPrev: () => options.loop || index > 0,
			canScrollNext: () => options.loop || index < slides.length - 1,
			on: (eventName, callback) => {
				viewport.addEventListener(eventName, callback);
				if (eventName === "init") {
					callback();
				}
				return {
					on: (nextEventName, nextCallback) => {
						viewport.addEventListener(nextEventName, nextCallback);
						return this;
					},
				};
			},
		};
	}

	return createEmblaCarousel;
})();

window.addEventListener("load", function () {
	// Debug log to check if script is running
	console.log("Banner slider initialization starting...");

	// Find all slider instances on the page
	const sliders = document.querySelectorAll(
		".wp-block-prostudiome-banner-slider .embla",
	);
	console.log("Found sliders:", sliders.length);

	if (!sliders.length) {
		console.log("No sliders found on page");
		return;
	}

	sliders.forEach((slider, index) => {
		console.log(`Initializing slider ${index + 1}`);

		const viewPort = slider.querySelector(".embla__viewport");
		const prevBtn = slider.querySelector(".embla__prev");
		const nextBtn = slider.querySelector(".embla__next");

		if (!viewPort) {
			console.error(`Viewport not found in slider ${index + 1}`);
			return;
		}

		try {
			const options = {
				loop: true,
			};

			const embla = EmblaCarousel(viewPort, options);
			console.log(`Slider ${index + 1} initialized successfully`);

			if (prevBtn && nextBtn) {
				// Add click handlers
				prevBtn.addEventListener("click", () => {
					console.log("Previous button clicked");
					embla.scrollPrev();
				});

				nextBtn.addEventListener("click", () => {
					console.log("Next button clicked");
					embla.scrollNext();
				});

				// Update button states
				const updateButtonStates = () => {
					const canScrollPrev = embla.canScrollPrev();
					const canScrollNext = embla.canScrollNext();

					prevBtn.disabled = !canScrollPrev;
					nextBtn.disabled = !canScrollNext;

					prevBtn.style.display = canScrollPrev ? "block" : "none";
					nextBtn.style.display = canScrollNext ? "block" : "none";
				};

				// Add listeners for button state updates
				embla.on("select", updateButtonStates);
				embla.on("init", updateButtonStates);

				// Initial state update
				updateButtonStates();
			}
		} catch (error) {
			console.error(`Error initializing slider ${index + 1}:`, error);
		}
	});
});
