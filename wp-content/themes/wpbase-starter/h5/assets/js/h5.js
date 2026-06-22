/**
 * EIKOU H5 - Mobile Interactions
 * Carousel, Tab Bar, Hamburger Menu, Video Modal, Filters, Scroll Effects
 */
(function () {
    'use strict';

    /* ========== Hero Carousel ========== */
    var carousel = document.getElementById('heroCarousel');
    var dots = document.getElementById('heroDots');
    var autoPlayTimer = null;
    var autoPlayDelay = 4000;

    function initCarousel() {
        if (!carousel || !dots) return;

        var dotEls = dots.querySelectorAll('.h5-dot');
        var cards = carousel.querySelectorAll('.h5-hero-card');
        if (cards.length === 0) return;

        var currentIndex = 0;

        function updateDots(index) {
            dotEls.forEach(function (d, i) {
                d.classList.toggle('active', i === index);
            });
            currentIndex = index;
        }

        function scrollToIndex(index) {
            var card = cards[index];
            if (card) {
                carousel.scrollTo({ left: card.offsetLeft, behavior: 'smooth' });
                updateDots(index);
            }
        }

        // Scroll-snap listener
        var scrollTimeout;
        carousel.addEventListener('scroll', function () {
            clearTimeout(scrollTimeout);
            scrollTimeout = setTimeout(function () {
                var scrollLeft = carousel.scrollLeft;
                var cardWidth = cards[0].offsetWidth;
                var index = Math.round(scrollLeft / cardWidth);
                if (index >= 0 && index < cards.length) {
                    updateDots(index);
                }
            }, 80);
        }, { passive: true });

        // Dot click
        dotEls.forEach(function (dot) {
            dot.addEventListener('click', function () {
                var index = parseInt(this.getAttribute('data-index'), 10);
                scrollToIndex(index);
                resetAutoPlay();
            });
        });

        // Auto-play
        function startAutoPlay() {
            stopAutoPlay();
            autoPlayTimer = setInterval(function () {
                var next = (currentIndex + 1) % cards.length;
                scrollToIndex(next);
            }, autoPlayDelay);
        }

        function stopAutoPlay() {
            if (autoPlayTimer) {
                clearInterval(autoPlayTimer);
                autoPlayTimer = null;
            }
        }

        function resetAutoPlay() {
            stopAutoPlay();
            startAutoPlay();
        }

        // Pause on touch
        carousel.addEventListener('touchstart', stopAutoPlay, { passive: true });
        carousel.addEventListener('touchend', function () {
            setTimeout(startAutoPlay, 2000);
        }, { passive: true });

        startAutoPlay();
    }

    /* ========== Hamburger Menu ========== */
    function initMenu() {
        var hamburger = document.getElementById('h5Hamburger');
        var overlay = document.getElementById('h5MenuOverlay');
        if (!hamburger || !overlay) return;

        var scrollPos = 0;

        hamburger.addEventListener('click', function () {
            var isOpen = overlay.classList.contains('active');
            if (isOpen) {
                closeMenu();
            } else {
                openMenu();
            }
        });

        function openMenu() {
            scrollPos = window.pageYOffset;
            hamburger.classList.add('active');
            overlay.classList.add('active');
            document.body.classList.add('h5-menu-open');
            document.body.style.top = -scrollPos + 'px';
        }

        function closeMenu() {
            hamburger.classList.remove('active');
            overlay.classList.remove('active');
            document.body.classList.remove('h5-menu-open');
            document.body.style.top = '';
            window.scrollTo(0, scrollPos);
        }

        // Close on overlay background click
        overlay.addEventListener('click', function (e) {
            if (e.target === overlay) {
                closeMenu();
            }
        });

        // Sub-menu accordion
        var parentItems = overlay.querySelectorAll('.menu-item-has-children');
        parentItems.forEach(function (item) {
            var link = item.querySelector(':scope > a');
            if (!link) return;

            link.addEventListener('click', function (e) {
                var subMenu = item.querySelector('.sub-menu, .submenu');
                if (!subMenu) return;

                e.preventDefault();
                item.classList.toggle('open');
                subMenu.classList.toggle('open');
            });
        });
    }

    /* ========== Header Scroll Effect ========== */
    function initHeaderScroll() {
        var header = document.getElementById('h5Header');
        if (!header) return;

        var lastScroll = 0;

        window.addEventListener('scroll', function () {
            var currentScroll = window.pageYOffset;
            if (currentScroll > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
            lastScroll = currentScroll;
        }, { passive: true });
    }

    /* ========== Bottom Tab Bar ========== */
    function initTabBar() {
        var tabBar = document.getElementById('h5TabBar');
        if (!tabBar) return;

        // Set active tab
        if (typeof eikouMobile !== 'undefined' && eikouMobile.currentTab) {
            var tabs = tabBar.querySelectorAll('.h5-tab-item');
            tabs.forEach(function (tab) {
                if (tab.getAttribute('data-tab') === eikouMobile.currentTab) {
                    tab.classList.add('active');
                }
            });
        }

        // Hide/show on scroll
        var lastScroll = 0;
        var ticking = false;

        window.addEventListener('scroll', function () {
            if (!ticking) {
                window.requestAnimationFrame(function () {
                    var currentScroll = window.pageYOffset;
                    if (currentScroll > lastScroll && currentScroll > 200) {
                        tabBar.classList.add('hidden');
                    } else {
                        tabBar.classList.remove('hidden');
                    }
                    lastScroll = currentScroll;
                    ticking = false;
                });
                ticking = true;
            }
        }, { passive: true });
    }

    /* ========== Video Modal ========== */
    function initVideoModal() {
        var modal = document.getElementById('h5VideoModal');
        var player = document.getElementById('h5VideoPlayer');
        if (!modal || !player) return;

        var closeBtn = modal.querySelector('.h5-video-modal-close');
        var bg = modal.querySelector('.h5-video-modal-bg');
        var source = player.querySelector('source');

        // Open modal on card click
        var cards = document.querySelectorAll('.h5-video-card[data-video-url]');
        cards.forEach(function (card) {
            card.addEventListener('click', function () {
                var url = this.getAttribute('data-video-url');
                if (!url) return;
                source.setAttribute('src', url);
                player.load();
                modal.classList.add('active');
                player.play().catch(function () { });
            });
        });

        function closeModal() {
            modal.classList.remove('active');
            player.pause();
            source.setAttribute('src', '');
            player.load();
        }

        if (closeBtn) closeBtn.addEventListener('click', closeModal);
        if (bg) bg.addEventListener('click', closeModal);
    }

    /* ========== Filter Buttons (Works Page) ========== */
    function initFilters() {
        var filterBtns = document.querySelectorAll('.h5-filter-btn[data-filter]');
        if (filterBtns.length === 0) return;

        var cards = document.querySelectorAll('.h5-work-card[data-category]');

        filterBtns.forEach(function (btn) {
            btn.addEventListener('click', function () {
                var filter = this.getAttribute('data-filter');

                // Update active button
                filterBtns.forEach(function (b) { b.classList.remove('active'); });
                this.classList.add('active');

                // Filter cards
                cards.forEach(function (card) {
                    if (filter === 'all' || card.getAttribute('data-category') === filter) {
                        card.style.display = '';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });
    }

    /* ========== Scroll Reveal ========== */
    function initScrollReveal() {
        var elements = document.querySelectorAll('.h5-section');
        if (elements.length === 0) return;

        elements.forEach(function (el) {
            el.classList.add('h5-scroll-reveal');
        });

        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });

        elements.forEach(function (el) {
            observer.observe(el);
        });
    }

    /* ========== Init All ========== */
    function init() {
        initCarousel();
        initMenu();
        initHeaderScroll();
        initTabBar();
        initVideoModal();
        initFilters();
        initScrollReveal();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
