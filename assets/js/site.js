document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('[data-carousel]').forEach((carousel) => {
    const slides = Array.from(carousel.querySelectorAll('.carousel-slide'));
    const dots = Array.from(carousel.querySelectorAll('[data-carousel-dot]'));
    const prev = carousel.querySelector('[data-carousel-prev]');
    const next = carousel.querySelector('[data-carousel-next]');
    if (slides.length <= 1) return;
    let index = 0;
    const show = (nextIndex) => {
      index = (nextIndex + slides.length) % slides.length;
      slides.forEach((slide, i) => slide.classList.toggle('is-active', i === index));
      dots.forEach((dot, i) => dot.classList.toggle('is-active', i === index));
    };
    prev && prev.addEventListener('click', () => show(index - 1));
    next && next.addEventListener('click', () => show(index + 1));
    dots.forEach((dot) => dot.addEventListener('click', () => show(Number(dot.dataset.carouselDot || 0))));
    const interval = Number(carousel.dataset.interval || 25000);
    window.setInterval(() => show(index + 1), interval);
  });
});
