document.addEventListener("DOMContentLoaded", function() {

    const carousels = document.querySelectorAll('.btfc-carousel')
    carousels.forEach(carousel => {
        const track = carousel.querySelector('.btfc-track');
        const slides = Array.from(track.children);
        const nextButton = carousel.querySelector('.btfc-btn.next');
        const prevButton = carousel.querySelector('.btfc-btn.prev');
        const slideWidth = slides[0].getBoundingClientRect().width;

        slides.forEach((slide, index)=> {
            slide.style.left = slideWidth * index + 'px';
        })

        let currentIndex = 0;
        let autoSlideInterval;
        let autoSlideTimeout;

        function moveToSlide(track, currentSlide, targetSlide){
            track.style.transform = 'translateX(-' + targetSlide.style.left + ')';
            currentSlide.classList.remove('current-slide');
            targetSlide.classList.add('current-slide')
        }

        function startAutoSlide() {
            autoSlideInterval = setInterval(() => {
                const currentSlide = track.querySelector('.current-slide');
                let nextSlide = currentSlide.nextElementSibling || slides[0];
                moveToSlide(track, currentSlide, nextSlide);
            }, 3000);
        }

        function stopAutoSlide() {
            clearInterval(autoSlideInterval);
            clearTimeout(autoSlideTimeout);
        }

        function restartAutoSlide() {
            stopAutoSlide();
            autoSlideTimeout = setTimeout(() => {
                startAutoSlide();
            }, 3000)
        }

        nextButton.addEventListener('click', () => {
            const currentSlide = track.querySelector('.current-slide');
            let nextSlide = currentSlide.nextElementSibling;
            if (!nextSlide) {
                nextSlide = slides[0];
            }
            moveToSlide(track, currentSlide, nextSlide);
            restartAutoSlide();
        });

        prevButton.addEventListener('click', () => {
            const currentSlide = track.querySelector('.current-slide');
            let prevSlide = currentSlide.previousElementSibling;
            if (!prevSlide) {
                prevSlide = slides[slides.length - 1];
            }
            moveToSlide(track, currentSlide, prevSlide);
            restartAutoSlide();
        });

        startAutoSlide();
        
    })  
})






