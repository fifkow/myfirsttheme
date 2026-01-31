/**
 * Neve Lite Main JavaScript
 *
 * @package Neve_Lite
 * @since 1.0.0
 */

(function () {
    'use strict';

    /**
     * Check if element is in viewport
     *
     * @param {HTMLElement} element
     * @returns {boolean}
     */
    function isInViewport(element) {
        const rect = element.getBoundingClientRect();
        return (
            rect.top >= 0 &&
            rect.left >= 0 &&
            rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
            rect.right <= (window.innerWidth || document.documentElement.clientWidth)
        );
    }

    /**
     * Debounce function
     *
     * @param {Function} func
     * @param {number} wait
     * @returns {Function}
     */
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    /**
     * Throttle function
     *
     * @param {Function} func
     * @param {number} limit
     * @returns {Function}
     */
    function throttle(func, limit) {
        let inThrottle;
        return function (...args) {
            if (!inThrottle) {
                func.apply(this, args);
                inThrottle = true;
                setTimeout(() => (inThrottle = false), limit);
            }
        };
    }

    /**
     * Smooth scroll to anchor
     */
    function initSmoothScroll() {
        document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
            anchor.addEventListener('click', function (e) {
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;

                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    e.preventDefault();
                    targetElement.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start',
                    });
                }
            });
        });
    }

    /**
     * Lazy load images
     */
    function initLazyLoad() {
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        if (img.dataset.src) {
                            img.src = img.dataset.src;
                            img.removeAttribute('data-src');
                        }
                        if (img.dataset.srcset) {
                            img.srcset = img.dataset.srcset;
                            img.removeAttribute('data-srcset');
                        }
                        img.classList.add('nv-lazy-loaded');
                        observer.unobserve(img);
                    }
                });
            });

            document.querySelectorAll('img[data-src]').forEach((img) => {
                imageObserver.observe(img);
            });
        }
    }

    /**
     * Header scroll effect
     */
    function initHeaderScroll() {
        const header = document.querySelector('.nv-header');
        if (!header) return;

        const scrollThreshold = 100;
        let lastScroll = 0;

        window.addEventListener(
            'scroll',
            throttle(() => {
                const currentScroll = window.pageYOffset;

                // Add scrolled class
                if (currentScroll > scrollThreshold) {
                    header.classList.add('nv-header-scrolled');
                } else {
                    header.classList.remove('nv-header-scrolled');
                }

                // Hide/show on scroll (for sticky header)
                if (header.classList.contains('nv-header-sticky')) {
                    if (currentScroll > lastScroll && currentScroll > 200) {
                        header.classList.add('nv-header-hidden');
                    } else {
                        header.classList.remove('nv-header-hidden');
                    }
                }

                lastScroll = currentScroll;
            }, 100)
        );
    }

    /**
     * Back to top button
     */
    function initBackToTop() {
        const backToTop = document.createElement('button');
        backToTop.className = 'nv-back-to-top';
        backToTop.innerHTML = `
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M18 15l-6-6-6 6"/>
            </svg>
        `;
        backToTop.setAttribute('aria-label', 'Back to top');
        document.body.appendChild(backToTop);

        window.addEventListener(
            'scroll',
            throttle(() => {
                if (window.pageYOffset > 500) {
                    backToTop.classList.add('is-visible');
                } else {
                    backToTop.classList.remove('is-visible');
                }
            }, 100)
        );

        backToTop.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth',
            });
        });
    }

    /**
     * Initialize animations on scroll
     */
    function initScrollAnimations() {
        const animatedElements = document.querySelectorAll('.nv-animate');
        if (!animatedElements.length) return;

        if ('IntersectionObserver' in window) {
            const animationObserver = new IntersectionObserver(
                (entries) => {
                    entries.forEach((entry) => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('nv-animated');
                            animationObserver.unobserve(entry.target);
                        }
                    });
                },
                {
                    threshold: 0.1,
                    rootMargin: '0px 0px -50px 0px',
                }
            );

            animatedElements.forEach((el) => {
                animationObserver.observe(el);
            });
        } else {
            // Fallback for browsers without IntersectionObserver
            animatedElements.forEach((el) => {
                el.classList.add('nv-animated');
            });
        }
    }

    /**
     * Initialize dropdown hover delay
     */
    function initDropdownDelay() {
        const dropdowns = document.querySelectorAll('.nv-nav-menu .menu-item-has-children');

        dropdowns.forEach((dropdown) => {
            let timeout;

            dropdown.addEventListener('mouseenter', () => {
                clearTimeout(timeout);
                dropdown.classList.add('is-open');
            });

            dropdown.addEventListener('mouseleave', () => {
                timeout = setTimeout(() => {
                    dropdown.classList.remove('is-open');
                }, 200);
            });
        });
    }

    /**
     * Initialize search toggle
     */
    function initSearchToggle() {
        const searchToggle = document.querySelector('.nv-search-toggle');
        const searchOverlay = document.querySelector('.nv-search-overlay');

        if (!searchToggle || !searchOverlay) return;

        searchToggle.addEventListener('click', () => {
            searchOverlay.classList.add('is-active');
            searchOverlay.querySelector('input[type="search"]').focus();
        });

        searchOverlay.querySelector('.nv-search-close').addEventListener('click', () => {
            searchOverlay.classList.remove('is-active');
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && searchOverlay.classList.contains('is-active')) {
                searchOverlay.classList.remove('is-active');
            }
        });
    }

    /**
     * Initialize masonry layout
     */
    function initMasonry() {
        const masonryContainer = document.querySelector('.nv-layout-masonry .nv-posts-grid');
        if (!masonryContainer) return;

        // Simple masonry using CSS columns for better performance
        const posts = masonryContainer.querySelectorAll('.nv-post-item');
        const columnCount = getComputedStyle(masonryContainer).getPropertyValue('--nv-posts-columns') || 3;

        posts.forEach((post, index) => {
            post.style.breakInside = 'avoid';
            post.style.marginBottom = 'var(--nv-spacing-lg)';
        });
    }

    /**
     * Initialize reading progress indicator
     */
    function initReadingProgress() {
        const progressBar = document.createElement('div');
        progressBar.className = 'nv-reading-progress';
        progressBar.innerHTML = '<div class="nv-reading-progress-bar"></div>';
        document.body.appendChild(progressBar);

        const progressBarInner = progressBar.querySelector('.nv-reading-progress-bar');

        window.addEventListener(
            'scroll',
            throttle(() => {
                const scrollTop = window.pageYOffset;
                const docHeight = document.documentElement.scrollHeight - window.innerHeight;
                const scrollPercent = (scrollTop / docHeight) * 100;
                progressBarInner.style.width = scrollPercent + '%';
            }, 50)
        );
    }

    /**
     * Initialize external links
     */
    function initExternalLinks() {
        document.querySelectorAll('a[href^="http"]').forEach((link) => {
            if (link.hostname !== window.location.hostname) {
                link.setAttribute('target', '_blank');
                link.setAttribute('rel', 'noopener noreferrer');
                link.classList.add('nv-external-link');
            }
        });
    }

    /**
     * Initialize focus trap for modals
     */
    function initFocusTrap() {
        const modals = document.querySelectorAll('.nv-modal');

        modals.forEach((modal) => {
            const focusableElements = modal.querySelectorAll(
                'a[href], button, input, textarea, select, details, [tabindex]:not([tabindex="-1"])'
            );
            const firstFocusable = focusableElements[0];
            const lastFocusable = focusableElements[focusableElements.length - 1];

            modal.addEventListener('keydown', (e) => {
                if (e.key !== 'Tab') return;

                if (e.shiftKey) {
                    if (document.activeElement === firstFocusable) {
                        lastFocusable.focus();
                        e.preventDefault();
                    }
                } else {
                    if (document.activeElement === lastFocusable) {
                        firstFocusable.focus();
                        e.preventDefault();
                    }
                }
            });
        });
    }

    /**
     * Initialize copy to clipboard
     */
    function initCopyToClipboard() {
        document.querySelectorAll('.nv-copy-button').forEach((button) => {
            button.addEventListener('click', () => {
                const text = button.dataset.copyText;
                if (text) {
                    navigator.clipboard.writeText(text).then(() => {
                        button.classList.add('is-copied');
                        button.textContent = neveLiteData.strings.copied || 'Copied!';

                        setTimeout(() => {
                            button.classList.remove('is-copied');
                            button.textContent = neveLiteData.strings.copy || 'Copy';
                        }, 2000);
                    });
                }
            });
        });
    }

    /**
     * Initialize responsive tables
     */
    function initResponsiveTables() {
        document.querySelectorAll('table').forEach((table) => {
            if (!table.parentElement.classList.contains('nv-table-wrapper')) {
                const wrapper = document.createElement('div');
                wrapper.className = 'nv-table-wrapper';
                table.parentNode.insertBefore(wrapper, table);
                wrapper.appendChild(table);
            }
        });
    }

    /**
     * Initialize image lightbox
     */
    function initLightbox() {
        const lightboxImages = document.querySelectorAll('.nv-lightbox');
        if (!lightboxImages.length) return;

        // Check if there's a lightbox library loaded
        if (typeof GLightbox !== 'undefined') {
            const lightbox = GLightbox({
                selector: '.nv-lightbox',
            });
        }
    }

    /**
     * Initialize video lazy loading
     */
    function initVideoLazyLoad() {
        const videoPlaceholders = document.querySelectorAll('.nv-video-placeholder');

        videoPlaceholders.forEach((placeholder) => {
            const playButton = placeholder.querySelector('.nv-play-button');
            const videoEmbed = placeholder.dataset.videoEmbed;

            if (playButton && videoEmbed) {
                playButton.addEventListener('click', () => {
                    const iframe = document.createElement('div');
                    iframe.innerHTML = videoEmbed;
                    placeholder.innerHTML = '';
                    placeholder.appendChild(iframe.firstChild);
                });
            }
        });
    }

    /**
     * Initialize counter animation
     */
    function initCounterAnimation() {
        const counters = document.querySelectorAll('.nv-counter');
        if (!counters.length) return;

        const animateCounter = (counter) => {
            const target = parseInt(counter.dataset.target, 10);
            const duration = parseInt(counter.dataset.duration, 10) || 2000;
            const step = target / (duration / 16);
            let current = 0;

            const updateCounter = () => {
                current += step;
                if (current < target) {
                    counter.textContent = Math.floor(current);
                    requestAnimationFrame(updateCounter);
                } else {
                    counter.textContent = target;
                }
            };

            updateCounter();
        };

        if ('IntersectionObserver' in window) {
            const counterObserver = new IntersectionObserver(
                (entries) => {
                    entries.forEach((entry) => {
                        if (entry.isIntersecting) {
                            animateCounter(entry.target);
                            counterObserver.unobserve(entry.target);
                        }
                    });
                },
                { threshold: 0.5 }
            );

            counters.forEach((counter) => {
                counterObserver.observe(counter);
            });
        }
    }

    /**
     * Initialize tabs
     */
    function initTabs() {
        document.querySelectorAll('.nv-tabs').forEach((tabsContainer) => {
            const tabs = tabsContainer.querySelectorAll('.nv-tab');
            const panels = tabsContainer.querySelectorAll('.nv-tab-panel');

            tabs.forEach((tab, index) => {
                tab.addEventListener('click', () => {
                    // Deactivate all tabs and panels
                    tabs.forEach((t) => t.classList.remove('is-active'));
                    panels.forEach((p) => p.classList.remove('is-active'));

                    // Activate clicked tab and corresponding panel
                    tab.classList.add('is-active');
                    panels[index].classList.add('is-active');
                });
            });
        });
    }

    /**
     * Initialize accordions
     */
    function initAccordions() {
        document.querySelectorAll('.nv-accordion').forEach((accordion) => {
            const items = accordion.querySelectorAll('.nv-accordion-item');

            items.forEach((item) => {
                const header = item.querySelector('.nv-accordion-header');
                const content = item.querySelector('.nv-accordion-content');

                header.addEventListener('click', () => {
                    const isOpen = item.classList.contains('is-open');

                    // Close all items if accordion is not multi
                    if (!accordion.classList.contains('nv-accordion-multi')) {
                        items.forEach((i) => {
                            i.classList.remove('is-open');
                            i.querySelector('.nv-accordion-content').style.maxHeight = null;
                        });
                    }

                    // Toggle current item
                    if (!isOpen) {
                        item.classList.add('is-open');
                        content.style.maxHeight = content.scrollHeight + 'px';
                    } else {
                        item.classList.remove('is-open');
                        content.style.maxHeight = null;
                    }
                });
            });
        });
    }

    /**
     * Initialize tooltips
     */
    function initTooltips() {
        document.querySelectorAll('[data-tooltip]').forEach((element) => {
            const tooltipText = element.dataset.tooltip;
            const tooltip = document.createElement('span');
            tooltip.className = 'nv-tooltip';
            tooltip.textContent = tooltipText;
            element.appendChild(tooltip);

            element.addEventListener('mouseenter', () => {
                tooltip.classList.add('is-visible');
            });

            element.addEventListener('mouseleave', () => {
                tooltip.classList.remove('is-visible');
            });
        });
    }

    /**
     * DOM ready
     */
    function domReady() {
        initSmoothScroll();
        initLazyLoad();
        initHeaderScroll();
        initBackToTop();
        initScrollAnimations();
        initDropdownDelay();
        initSearchToggle();
        initMasonry();
        initReadingProgress();
        initExternalLinks();
        initFocusTrap();
        initCopyToClipboard();
        initResponsiveTables();
        initLightbox();
        initVideoLazyLoad();
        initCounterAnimation();
        initTabs();
        initAccordions();
        initTooltips();
    }

    // Initialize on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', domReady);
    } else {
        domReady();
    }
})();
