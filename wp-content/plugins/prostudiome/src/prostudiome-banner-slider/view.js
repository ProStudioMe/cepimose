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
	const emblaNode = document.querySelector(".embla");
	if (!emblaNode) return;

	const viewPort = emblaNode.querySelector(".embla__viewport");
	const prevBtn = emblaNode.querySelector(".embla__prev");
	const nextBtn = emblaNode.querySelector(".embla__next");

	const options = {
		loop: true,
		dragFree: true,
		skipSnaps: false,
		containScroll: "trimSnaps",
	};

	const embla = EmblaCarousel(viewPort, options);

	prevBtn.addEventListener("click", () => embla.scrollPrev());
	nextBtn.addEventListener("click", () => embla.scrollNext());

	// Optional: Add button states
	const addToggleBtnStates = (emblaApi, prevBtn, nextBtn) => {
		const toggleBtnState = () => {
			if (emblaApi.canScrollPrev()) prevBtn.removeAttribute("disabled");
			else prevBtn.setAttribute("disabled", "disabled");

			if (emblaApi.canScrollNext()) nextBtn.removeAttribute("disabled");
			else nextBtn.setAttribute("disabled", "disabled");
		};

		emblaApi
			.on("select", toggleBtnState)
			.on("init", toggleBtnState)
			.on("reInit", toggleBtnState);

		return () => {
			prevBtn.removeAttribute("disabled");
			nextBtn.removeAttribute("disabled");
		};
	};

	const toggleBtnState = addToggleBtnStates(embla, prevBtn, nextBtn);
});
