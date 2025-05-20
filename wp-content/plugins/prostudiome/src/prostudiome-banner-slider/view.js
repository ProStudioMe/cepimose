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

import EmblaCarousel from "embla-carousel";

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

	// Check if EmblaCarousel is available
	if (typeof EmblaCarousel === "undefined") {
		console.error("EmblaCarousel is not loaded!");
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
				dragFree: false,
				skipSnaps: false,
				containScroll: "trimSnaps",
				align: "start",
				slidesToScroll: 1,
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
				embla.on("reInit", updateButtonStates);

				// Initial state update
				updateButtonStates();
			}
		} catch (error) {
			console.error(`Error initializing slider ${index + 1}:`, error);
		}
	});
});
