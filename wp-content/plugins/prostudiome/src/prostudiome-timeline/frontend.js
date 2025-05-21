document.addEventListener("DOMContentLoaded", () => {
	document
		.querySelectorAll(".wp-block-prostudiome-timeline")
		.forEach((block) => {
			const swiperElement = block.querySelector(".swiper");
			const options = JSON.parse(block.dataset?.swiperOptions || "{}");

			const {
				speed = 800,
				autoplay = false,
				loop = false,
				navigation = true,
				effect = "slide",
				pagination = true,
				paginationType = "bullets",
				slidesPerView = 6,
				spaceBetween = 20,
				centeredSlides = false,
				grabCursor = true,
				breakpoints = {
					320: {
						slidesPerView: 1,
						spaceBetween: 10,
					},
					480: {
						slidesPerView: 2,
						spaceBetween: 15,
					},
					768: {
						slidesPerView: 3,
						spaceBetween: 15,
					},
					1024: {
						slidesPerView: 4,
						spaceBetween: 20,
					},
					1280: {
						slidesPerView: 5,
						spaceBetween: 20,
					},
					1440: {
						slidesPerView: 6,
						spaceBetween: 20,
					},
				},
			} = options;

			const swiperOptions = {
				loop,
				autoplay,
				speed,
				effect,
				slidesPerView,
				spaceBetween,
				centeredSlides,
				grabCursor,
				breakpoints,
				observer: true,
				observeParents: true,
				resizeObserver: true,
				watchOverflow: true,
				navigation: navigation
					? {
							nextEl: block.querySelector(".swiper-button-next"),
							prevEl: block.querySelector(".swiper-button-prev"),
					  }
					: false,
				pagination: pagination
					? {
							el: block.querySelector(".swiper-pagination"),
							type: paginationType,
							clickable: true,
					  }
					: false,
			};

			console.log("Swiper options:", swiperOptions); // Debug log
			new Swiper(swiperElement, swiperOptions);
		});
});
