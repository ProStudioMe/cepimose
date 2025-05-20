document.addEventListener("DOMContentLoaded", () => {
  document
    .querySelectorAll(".wp-block-prostudio-banner-swiper")
    .forEach((block) => {
      const swiperElement = block.querySelector(".swiper");
      const options = JSON.parse(block.dataset?.swiperOptions || "{}");

      const {
        speed = 800,
        autoplay = true,
        autoplayDelay = 5000,
        pauseOnMouseEnter = true,
        loop = true,
        navigation = true,
        effect = "fade",
        pagination = true,
        paginationType = "bullets",
      } = options;

      const swiperOptions = {
        loop,
        autoplay: autoplay
          ? {
              delay: autoplayDelay,
              disableOnInteraction: false,
              pauseOnMouseEnter,
            }
          : false,
        speed,
        effect,
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

      new Swiper(swiperElement, swiperOptions);
    });
});
