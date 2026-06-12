/* ============================================
   EIKOU Mobile H5 Interactions
   Only loaded on mobile devices via PHP detection
   ============================================ */
(function () {
    'use strict';

    // ===== Bottom Tab Active State =====
    var tabBar = document.getElementById('mobileTabBar');
    if (tabBar && typeof eikouMobile !== 'undefined') {
        var tabs = tabBar.querySelectorAll('.tab-item');
        tabs.forEach(function (tab) {
            if (tab.getAttribute('data-tab') === eikouMobile.currentTab) {
                tab.classList.add('active');
            }
        });
    }

    // ===== Mobile Menu Overlay Toggle =====
    var menuBtn = document.querySelector('.mobile-menu-btn');
    var overlay = document.getElementById('mobileMenuOverlay');
    var body = document.body;

    if (menuBtn && overlay) {
        menuBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            var isOpen = overlay.classList.contains('open');
            if (isOpen) {
                overlay.classList.remove('open');
                body.style.overflow = '';
                menuBtn.classList.remove('active');
            } else {
                overlay.classList.add('open');
                body.style.overflow = 'hidden';
                menuBtn.classList.add('active');
            }
        });

        // Close when clicking on a link inside the overlay
        var overlayLinks = overlay.querySelectorAll('a:not(.mobile-menu-lang a)');
        overlayLinks.forEach(function (link) {
            // Skip parent links that have submenus
            var parentLi = link.parentElement;
            if (parentLi && parentLi.querySelector('.submenu')) {
                return;
            }
            link.addEventListener('click', function () {
                overlay.classList.remove('open');
                body.style.overflow = '';
                menuBtn.classList.remove('active');
            });
        });
    }

    // ===== Submenu Accordion in Overlay =====
    if (overlay) {
        var menuItems = overlay.querySelectorAll('.mobile-menu-nav > ul > li');
        menuItems.forEach(function (li) {
            var submenu = li.querySelector('.submenu');
            if (submenu) {
                var link = li.querySelector(':scope > a');
                submenu.style.maxHeight = '0';
                submenu.style.overflow = 'hidden';
                submenu.style.transition = 'max-height 0.3s ease';

                link.addEventListener('click', function (e) {
                    e.preventDefault();
                    var isExpanded = submenu.style.maxHeight !== '0px' && submenu.style.maxHeight !== '0';
                    if (isExpanded) {
                        submenu.style.maxHeight = '0';
                    } else {
                        submenu.style.maxHeight = submenu.scrollHeight + 'px';
                    }
                });
            }
        });
    }

    // ===== Hero Touch Swipe =====
    var hero = document.querySelector('.hero');
    if (hero) {
        var startX = 0;
        var startY = 0;
        var threshold = 50;
        var slides = document.querySelectorAll('.hero-slide');
        var indicators = document.querySelectorAll('.hero-indicators .indicator');

        if (slides.length > 1) {
            hero.addEventListener('touchstart', function (e) {
                startX = e.touches[0].clientX;
                startY = e.touches[0].clientY;
            }, { passive: true });

            hero.addEventListener('touchend', function (e) {
                var endX = e.changedTouches[0].clientX;
                var endY = e.changedTouches[0].clientY;
                var diffX = startX - endX;
                var diffY = startY - endY;

                // Only trigger if horizontal swipe is dominant
                if (Math.abs(diffX) > threshold && Math.abs(diffX) > Math.abs(diffY)) {
                    var currentIdx = 0;
                    slides.forEach(function (s, i) {
                        if (s.classList.contains('active')) currentIdx = i;
                    });

                    var nextIdx;
                    if (diffX > 0) {
                        nextIdx = (currentIdx + 1) % slides.length;
                    } else {
                        nextIdx = (currentIdx - 1 + slides.length) % slides.length;
                    }

                    if (indicators[nextIdx]) {
                        indicators[nextIdx].click();
                    }
                }
            }, { passive: true });
        }
    }

    // ===== Tab Bar Auto-hide on Scroll =====
    if (tabBar) {
        var lastScrollY = window.pageYOffset;
        var ticking = false;

        window.addEventListener('scroll', function () {
            if (!ticking) {
                window.requestAnimationFrame(function () {
                    var currentScrollY = window.pageYOffset;
                    if (currentScrollY > lastScrollY && currentScrollY > 200) {
                        tabBar.style.transform = 'translateY(100%)';
                    } else {
                        tabBar.style.transform = 'translateY(0)';
                    }
                    lastScrollY = currentScrollY;
                    ticking = false;
                });
                ticking = true;
            }
        }, { passive: true });
    }

    // ===== Prevent double-tap zoom on interactive elements =====
    var touchElements = document.querySelectorAll('.tab-item, .filter-btn, .btn');
    touchElements.forEach(function (el) {
        el.addEventListener('touchend', function (e) {
            // Allow natural behavior but prevent zoom
        }, { passive: true });
    });

})();
