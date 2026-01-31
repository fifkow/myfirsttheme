/**
 * Neve Lite Navigation JavaScript
 *
 * @package Neve_Lite
 * @since 1.0.0
 */

(function () {
    'use strict';

    /**
     * Mobile menu toggle
     */
    function initMobileMenu() {
        const menuToggle = document.querySelector('.nv-menu-toggle');
        const primaryMenu = document.querySelector('.nv-nav-menu');
        const siteNavigation = document.getElementById('site-navigation');

        if (!menuToggle || !primaryMenu) return;

        // Toggle menu on button click
        menuToggle.addEventListener('click', () => {
            const isExpanded = menuToggle.getAttribute('aria-expanded') === 'true';
            menuToggle.setAttribute('aria-expanded', !isExpanded);
            primaryMenu.classList.toggle('is-active');
            document.body.classList.toggle('nv-menu-open');
        });

        // Close menu when clicking outside
        document.addEventListener('click', (e) => {
            if (
                !siteNavigation.contains(e.target) &&
                primaryMenu.classList.contains('is-active')
            ) {
                menuToggle.setAttribute('aria-expanded', 'false');
                primaryMenu.classList.remove('is-active');
                document.body.classList.remove('nv-menu-open');
            }
        });

        // Close menu on escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && primaryMenu.classList.contains('is-active')) {
                menuToggle.setAttribute('aria-expanded', 'false');
                primaryMenu.classList.remove('is-active');
                document.body.classList.remove('nv-menu-open');
                menuToggle.focus();
            }
        });
    }

    /**
     * Submenu toggle for mobile
     */
    function initSubmenuToggle() {
        const menuItems = document.querySelectorAll('.nv-nav-menu .menu-item-has-children');

        menuItems.forEach((item) => {
            const link = item.querySelector('a');
            const submenu = item.querySelector('.sub-menu');

            if (!link || !submenu) return;

            // Create toggle button
            const toggleButton = document.createElement('button');
            toggleButton.className = 'nv-submenu-toggle';
            toggleButton.setAttribute('aria-expanded', 'false');
            toggleButton.setAttribute('aria-label', neveLiteData.strings.submenuOpen || 'Open submenu');
            toggleButton.innerHTML = `
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="6 9 12 15 18 9"></polyline>
                </svg>
            `;

            // Insert toggle button after link
            link.parentNode.insertBefore(toggleButton, link.nextSibling);

            // Toggle submenu on button click
            toggleButton.addEventListener('click', (e) => {
                e.preventDefault();
                const isExpanded = toggleButton.getAttribute('aria-expanded') === 'true';
                toggleButton.setAttribute('aria-expanded', !isExpanded);
                item.classList.toggle('is-open');
            });

            // Handle link click on mobile
            link.addEventListener('click', (e) => {
                // If menu is in mobile mode and item has children
                if (
                    window.innerWidth <= 991 &&
                    item.classList.contains('menu-item-has-children') &&
                    !item.classList.contains('is-open')
                ) {
                    e.preventDefault();
                    toggleButton.setAttribute('aria-expanded', 'true');
                    item.classList.add('is-open');
                }
            });
        });
    }

    /**
     * Focus trap for mobile menu
     */
    function initFocusTrap() {
        const siteNavigation = document.getElementById('site-navigation');
        if (!siteNavigation) return;

        const menuToggle = document.querySelector('.nv-menu-toggle');
        const focusableElements = siteNavigation.querySelectorAll(
            'a[href], button, input, textarea, select, details, [tabindex]:not([tabindex="-1"])'
        );

        if (focusableElements.length === 0) return;

        const firstFocusable = focusableElements[0];
        const lastFocusable = focusableElements[focusableElements.length - 1];

        siteNavigation.addEventListener('keydown', (e) => {
            if (e.key !== 'Tab') return;

            // Only trap focus when menu is open
            if (!siteNavigation.querySelector('.nv-nav-menu').classList.contains('is-active')) {
                return;
            }

            if (e.shiftKey) {
                if (document.activeElement === firstFocusable) {
                    menuToggle.focus();
                    e.preventDefault();
                }
            } else {
                if (document.activeElement === lastFocusable) {
                    menuToggle.focus();
                    e.preventDefault();
                }
            }
        });
    }

    /**
     * Handle window resize
     */
    function handleResize() {
        const menuToggle = document.querySelector('.nv-menu-toggle');
        const primaryMenu = document.querySelector('.nv-nav-menu');

        if (!menuToggle || !primaryMenu) return;

        // Reset menu state when switching to desktop
        if (window.innerWidth > 991) {
            menuToggle.setAttribute('aria-expanded', 'false');
            primaryMenu.classList.remove('is-active');
            document.body.classList.remove('nv-menu-open');

            // Close all submenus
            document.querySelectorAll('.nv-nav-menu .is-open').forEach((item) => {
                item.classList.remove('is-open');
            });
        }
    }

    /**
     * Initialize sticky header
     */
    function initStickyHeader() {
        const header = document.querySelector('.nv-header-sticky');
        if (!header) return;

        let lastScroll = 0;
        const scrollThreshold = 100;

        window.addEventListener('scroll', () => {
            const currentScroll = window.pageYOffset;

            // Add/remove scrolled class
            if (currentScroll > scrollThreshold) {
                header.classList.add('is-scrolled');
            } else {
                header.classList.remove('is-scrolled');
            }

            // Hide/show on scroll direction
            if (currentScroll > lastScroll && currentScroll > 200) {
                header.classList.add('is-hidden');
            } else {
                header.classList.remove('is-hidden');
            }

            lastScroll = currentScroll;
        });
    }

    /**
     * Initialize dropdown hover
     */
    function initDropdownHover() {
        const dropdowns = document.querySelectorAll('.nv-nav-menu .menu-item-has-children');

        dropdowns.forEach((dropdown) => {
            let timeout;

            dropdown.addEventListener('mouseenter', () => {
                clearTimeout(timeout);
                if (window.innerWidth > 991) {
                    dropdown.classList.add('is-hovered');
                }
            });

            dropdown.addEventListener('mouseleave', () => {
                timeout = setTimeout(() => {
                    dropdown.classList.remove('is-hovered');
                }, 200);
            });

            // Keyboard navigation
            const link = dropdown.querySelector('a');
            if (link) {
                link.addEventListener('keydown', (e) => {
                    if (e.key === 'ArrowDown') {
                        e.preventDefault();
                        const submenu = dropdown.querySelector('.sub-menu');
                        if (submenu) {
                            const firstItem = submenu.querySelector('a');
                            if (firstItem) {
                                firstItem.focus();
                            }
                        }
                    }
                });
            }
        });
    }

    /**
     * Initialize skip link
     */
    function initSkipLink() {
        const skipLink = document.querySelector('.skip-link');
        if (!skipLink) return;

        skipLink.addEventListener('click', (e) => {
            e.preventDefault();
            const target = document.getElementById('primary');
            if (target) {
                target.setAttribute('tabindex', '-1');
                target.focus();
                target.addEventListener(
                    'blur',
                    () => {
                        target.removeAttribute('tabindex');
                    },
                    { once: true }
                );
            }
        });
    }

    /**
     * Initialize accessibility improvements
     */
    function initAccessibility() {
        // Add aria-current to current menu item
        const currentMenuItem = document.querySelector('.nv-nav-menu .current-menu-item a');
        if (currentMenuItem) {
            currentMenuItem.setAttribute('aria-current', 'page');
        }

        // Add aria-haspopup to menu items with children
        document.querySelectorAll('.nv-nav-menu .menu-item-has-children > a').forEach((link) => {
            link.setAttribute('aria-haspopup', 'true');
            link.setAttribute('aria-expanded', 'false');
        });
    }

    /**
     * DOM ready
     */
    function domReady() {
        initMobileMenu();
        initSubmenuToggle();
        initFocusTrap();
        initStickyHeader();
        initDropdownHover();
        initSkipLink();
        initAccessibility();

        // Handle window resize
        let resizeTimer;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(handleResize, 250);
        });
    }

    // Initialize on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', domReady);
    } else {
        domReady();
    }
})();
