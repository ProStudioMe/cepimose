document.addEventListener("DOMContentLoaded", () => {
	document
		.querySelectorAll(".wp-block-prostudiome-timeline")
		.forEach((block) => {
			const swiperElement = block.querySelector(".swiper");
			const options = JSON.parse(block.dataset?.swiperOptions || "{}");

			const swiperOptions = {
				speed: options.speed || 800,
				loop: options.loop || false,
				autoplay: options.autoplay || false,
				effect: options.effect || "slide",
				centeredSlides: options.centeredSlides || false,
				grabCursor: options.grabCursor || true,
				slidesPerView: 1,
				spaceBetween: 0,
				breakpoints: options.breakpoints || {
					0: {
						slidesPerView: 1,
					},
					480: {
						slidesPerView: 2,
					},
					768: {
						slidesPerView: 3,
					},
					1024: {
						slidesPerView: 4,
					},
					1280: {
						slidesPerView: 4,
					},
				},
				observer: true,
				observeParents: true,
				resizeObserver: true,
				watchOverflow: true,
				navigation:
					options.navigation !== false
						? {
								nextEl: block.querySelector(".swiper-button-next"),
								prevEl: block.querySelector(".swiper-button-prev"),
						  }
						: false,
				pagination:
					options.pagination !== false
						? {
								el: block.querySelector(".swiper-pagination"),
								type: options.paginationType || "bullets",
								clickable: true,
						  }
						: false,
			};

			console.log("Swiper options:", swiperOptions); // Debug log
			const swiper = new Swiper(swiperElement, swiperOptions);

			// Debug breakpoints
			window.addEventListener("resize", () => {
				console.log("Current window width:", window.innerWidth);
				console.log("Current slidesPerView:", swiper.params.slidesPerView);
				console.log("Active breakpoint:", swiper.currentBreakpoint);
			});
		});
});
