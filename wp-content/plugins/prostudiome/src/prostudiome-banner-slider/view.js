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

document.addEventListener("DOMContentLoaded", function () {
	// Find all slider instances on the page
	const sliders = document.querySelectorAll(
		".wp-block-prostudiome-banner-slider .embla",
	);

	if (!sliders.length) return;

	sliders.forEach((slider) => {
		const viewPort = slider.querySelector(".embla__viewport");
		const prevBtn = slider.querySelector(".embla__prev");
		const nextBtn = slider.querySelector(".embla__next");

		if (!viewPort) return;

		const options = {
			loop: true,
			dragFree: false,
			skipSnaps: false,
			containScroll: "trimSnaps",
			align: "start",
			slidesToScroll: 1,
		};

		const embla = EmblaCarousel(viewPort, options);

		if (prevBtn && nextBtn) {
			// Add click handlers
			prevBtn.addEventListener("click", () => embla.scrollPrev());
			nextBtn.addEventListener("click", () => embla.scrollNext());

			// Update button states
			const updateButtonStates = () => {
				if (embla.canScrollPrev()) {
					prevBtn.removeAttribute("disabled");
				} else {
					prevBtn.setAttribute("disabled", "disabled");
				}

				if (embla.canScrollNext()) {
					nextBtn.removeAttribute("disabled");
				} else {
					nextBtn.setAttribute("disabled", "disabled");
				}
			};

			// Add listeners for button state updates
			embla.on("select", updateButtonStates);
			embla.on("init", updateButtonStates);
			embla.on("reInit", updateButtonStates);

			// Initial state update
			updateButtonStates();
		}
	});
});
