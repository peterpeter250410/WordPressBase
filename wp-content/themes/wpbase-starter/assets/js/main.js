/* ============================================
   EIKOU Co., Ltd. - Mockup Interactions
   ============================================ */

(function () {
    'use strict';

    // ========== Hero Slideshow (Apple-style crossfade) ==========
    const slides = document.querySelectorAll('.hero-slide');
    const indicators = document.querySelectorAll('.hero-indicators .indicator');
    let currentSlide = 0;
    let slideInterval;
    const SLIDE_DURATION = 3000; // 3 seconds per slide

    function goToSlide(index) {
        // Remove prev class from all
        slides.forEach(function (s) { s.classList.remove('prev'); });

        // Current active becomes prev
        slides[currentSlide].classList.remove('active');
        slides[currentSlide].classList.add('prev');
        indicators[currentSlide].classList.remove('active');

        // New slide becomes active
        currentSlide = index;
        slides[currentSlide].classList.add('active');
        indicators[currentSlide].classList.add('active');

        // Clean up prev class after transition
        setTimeout(function () {
            slides.forEach(function (s) {
                if (!s.classList.contains('active')) {
                    s.classList.remove('prev');
                }
            });
        }, 1600);
    }

    function nextSlide() {
        var next = (currentSlide + 1) % slides.length;
        goToSlide(next);
    }

    function startSlideshow() {
        slideInterval = setInterval(nextSlide, SLIDE_DURATION);
    }

    function resetSlideshow() {
        clearInterval(slideInterval);
        startSlideshow();
    }

    // Indicator click
    indicators.forEach(function (btn) {
        btn.addEventListener('click', function () {
            var index = parseInt(this.getAttribute('data-slide'));
            if (index !== currentSlide) {
                goToSlide(index);
                resetSlideshow();
            }
        });
    });

    // Start auto slideshow with initial fade-in
    if (slides.length > 0) {
        // Remove active from first slide, then re-add after a frame to trigger CSS transition
        slides[0].classList.remove('active');
        if (indicators.length > 0) indicators[0].classList.remove('active');
        requestAnimationFrame(function () {
            requestAnimationFrame(function () {
                slides[0].classList.add('active');
                if (indicators.length > 0) indicators[0].classList.add('active');
                startSlideshow();
            });
        });
    }

    // ========== Language Dropdown ==========
    var langDropdown = document.querySelector('.lang-dropdown');
    var langBtn = document.querySelector('.lang-current');

    if (langBtn && langDropdown) {
        langBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            langDropdown.classList.toggle('open');
        });

        // Close on outside click
        document.addEventListener('click', function () {
            langDropdown.classList.remove('open');
        });

        // Prevent close when clicking inside menu
        var langMenu = document.querySelector('.lang-menu');
        if (langMenu) {
            langMenu.addEventListener('click', function (e) {
                e.stopPropagation();
            });
        }

        // Language selection
        var langLinks = document.querySelectorAll('.lang-menu a');
        langLinks.forEach(function (link) {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                langLinks.forEach(function (l) { l.classList.remove('active'); });
                this.classList.add('active');
                document.querySelector('.lang-current span').textContent = this.textContent;
                langDropdown.classList.remove('open');
            });
        });
    }

    // ========== Header scroll effect ==========
    var header = document.querySelector('.site-header');
    var lastScroll = 0;

    window.addEventListener('scroll', function () {
        var scrollY = window.pageYOffset;
        if (scrollY > 100) {
            header.style.background = 'rgba(10, 10, 15, 0.95)';
        } else {
            header.style.background = 'rgba(10, 10, 15, 0.85)';
        }
        lastScroll = scrollY;
    });

    // ========== Scroll reveal animation ==========
    var revealElements = document.querySelectorAll(
        '.section-header, .business-item, .work-card, .video-card, .reason-item, .partner-logo'
    );

    var observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1, rootMargin: '0px 0px -60px 0px' });

    revealElements.forEach(function (el) {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        el.style.transition = 'opacity 0.7s ease, transform 0.7s ease';
        observer.observe(el);
    });

})();
