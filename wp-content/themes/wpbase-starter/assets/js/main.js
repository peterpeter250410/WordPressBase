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
        '.section-header, .business-item, .work-card, .video-card, .reason-item, .partner-logo, .scroll-reveal, .service-card-home, .service-hub-card'
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

    // ========== Video Modal ==========
    var videoModal = document.getElementById('videoModal');
    var videoPlayer = document.getElementById('videoPlayer');

    if (videoModal && videoPlayer) {
        // Open modal on video card click
        document.querySelectorAll('.video-card[data-video-url]').forEach(function (card) {
            card.addEventListener('click', function () {
                var url = this.getAttribute('data-video-url');
                if (!url) return;
                var source = videoPlayer.querySelector('source');
                source.src = url;
                videoPlayer.load();
                videoModal.classList.add('active');
                videoPlayer.play();
            });
        });

        // Close modal
        var closeBtn = videoModal.querySelector('.video-modal-close');
        var backdrop = videoModal.querySelector('.video-modal-backdrop');

        function closeVideoModal() {
            videoModal.classList.remove('active');
            videoPlayer.pause();
            videoPlayer.querySelector('source').src = '';
            videoPlayer.load();
        }

        if (closeBtn) closeBtn.addEventListener('click', closeVideoModal);
        if (backdrop) backdrop.addEventListener('click', closeVideoModal);

        // Close on Escape key
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && videoModal.classList.contains('active')) {
                closeVideoModal();
            }
        });
    }

    // ========== Mobile Menu Toggle (右侧抽屉 + 蒙版) ==========
    var mobileMenuBtn = document.querySelector('.mobile-menu-btn');
    var mainNav = document.querySelector('.main-nav');

    if (mobileMenuBtn && mainNav && !document.body.classList.contains('is-mobile')) {
        function closePcMenu() {
            mainNav.classList.remove('open');
            mobileMenuBtn.classList.remove('active');
            document.body.style.overflow = '';
        }

        mobileMenuBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            var willOpen = !mainNav.classList.contains('open');
            mainNav.classList.toggle('open', willOpen);
            mobileMenuBtn.classList.toggle('active', willOpen);
            document.body.style.overflow = willOpen ? 'hidden' : '';
        });

        // 点击蒙版空白处关闭（点到 nav 自身而非内部 ul/链接）
        mainNav.addEventListener('click', function (e) {
            if (e.target === mainNav) closePcMenu();
        });

        // 点击导航链接后关闭抽屉
        mainNav.querySelectorAll('a').forEach(function (a) {
            a.addEventListener('click', closePcMenu);
        });

        // Esc 关闭
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && mainNav.classList.contains('open')) closePcMenu();
        });
    }

})();
