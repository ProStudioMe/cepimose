// Pure CSS Marquee: Duplicate ticker items in the DOM if needed for seamless loop

document.addEventListener("DOMContentLoaded", function () {
	const tickerBar = document.querySelector(".prostudiome-ticker-bar");
	const marquee = tickerBar?.querySelector(".marquee");
	if (!marquee) return;

	// Duplicate content if not enough to fill 2x the viewport
	const originalHTML = marquee.innerHTML;
	let totalWidth = marquee.scrollWidth;
	const viewportWidth = window.innerWidth;

	while (totalWidth < 2 * viewportWidth) {
		marquee.innerHTML += originalHTML;
		totalWidth = marquee.scrollWidth;
	}
});
