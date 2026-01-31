/**
 * Neve Lite Customizer Live Preview
 *
 * @package Neve_Lite
 * @since 1.0.0
 */

(function ($) {
    'use strict';

    // Site title and description
    wp.customize('blogname', function (value) {
        value.bind(function (to) {
            $('.nv-logo-text a').text(to);
        });
    });

    wp.customize('blogdescription', function (value) {
        value.bind(function (to) {
            $('.site-description').text(to);
        });
    });

    // Header text color
    wp.customize('header_textcolor', function (value) {
        value.bind(function (to) {
            if ('blank' === to) {
                $('.nv-logo-text, .site-description').css({
                    clip: 'rect(1px, 1px, 1px, 1px)',
                    position: 'absolute',
                });
            } else {
                $('.nv-logo-text, .site-description').css({
                    clip: 'auto',
                    position: 'relative',
                });
                $('.nv-logo-text a, .site-description').css({
                    color: to,
                });
            }
        });
    });

    // Primary color
    wp.customize('neve_lite_primary_color', function (value) {
        value.bind(function (to) {
            document.documentElement.style.setProperty('--nv-primary-color', to);
        });
    });

    // Secondary color
    wp.customize('neve_lite_secondary_color', function (value) {
        value.bind(function (to) {
            document.documentElement.style.setProperty('--nv-secondary-color', to);
        });
    });

    // Text color
    wp.customize('neve_lite_text_color', function (value) {
        value.bind(function (to) {
            document.documentElement.style.setProperty('--nv-text-color', to);
        });
    });

    // Background color
    wp.customize('neve_lite_background_color', function (value) {
        value.bind(function (to) {
            document.documentElement.style.setProperty('--nv-background-color', to);
        });
    });

    // Link color
    wp.customize('neve_lite_link_color', function (value) {
        value.bind(function (to) {
            document.documentElement.style.setProperty('--nv-link-color', to);
        });
    });

    // Link hover color
    wp.customize('neve_lite_link_hover_color', function (value) {
        value.bind(function (to) {
            document.documentElement.style.setProperty('--nv-link-hover-color', to);
        });
    });

    // Container width
    wp.customize('neve_lite_container_width_px', function (value) {
        value.bind(function (to) {
            document.documentElement.style.setProperty('--nv-container-width', to + 'px');
        });
    });

    // Container padding
    wp.customize('neve_lite_container_padding', function (value) {
        value.bind(function (to) {
            document.documentElement.style.setProperty('--nv-container-padding', to + 'px');
        });
    });

    // Header height
    wp.customize('neve_lite_header_height', function (value) {
        value.bind(function (to) {
            document.documentElement.style.setProperty('--nv-header-height', to + 'px');
        });
    });

    // Header background
    wp.customize('neve_lite_header_background', function (value) {
        value.bind(function (to) {
            document.documentElement.style.setProperty('--nv-header-background', to);
            $('.nv-header').css('background-color', to);
        });
    });

    // Header text color
    wp.customize('neve_lite_header_text_color', function (value) {
        value.bind(function (to) {
            $('.nv-nav-menu a').css('color', to);
        });
    });

    // Base font size
    wp.customize('neve_lite_base_font_size', function (value) {
        value.bind(function (to) {
            document.documentElement.style.setProperty('--nv-font-size-base', to + 'px');
        });
    });

    // Line height
    wp.customize('neve_lite_line_height', function (value) {
        value.bind(function (to) {
            document.documentElement.style.setProperty('--nv-line-height-base', to);
        });
    });

    // Footer background
    wp.customize('neve_lite_footer_background', function (value) {
        value.bind(function (to) {
            document.documentElement.style.setProperty('--nv-footer-background', to);
            $('.nv-footer').css('background-color', to);
        });
    });

    // Footer text color
    wp.customize('neve_lite_footer_text_color', function (value) {
        value.bind(function (to) {
            document.documentElement.style.setProperty('--nv-footer-text-color', to);
            $('.nv-footer').css('color', to);
        });
    });

    // Copyright text
    wp.customize('neve_lite_copyright_text', function (value) {
        value.bind(function (to) {
            // Replace placeholders
            to = to.replace('{year}', new Date().getFullYear());
            to = to.replace('{site_title}', wp.customize('blogname').get());
            $('.nv-footer-bottom p').html(to);
        });
    });

    // CTA button text
    wp.customize('neve_lite_header_cta_text', function (value) {
        value.bind(function (to) {
            $('.nv-header-cta').text(to);
        });
    });

    // CTA button URL
    wp.customize('neve_lite_header_cta_url', function (value) {
        value.bind(function (to) {
            $('.nv-header-cta').attr('href', to);
        });
    });

    // Excerpt length (refresh required)
    wp.customize('neve_lite_excerpt_length', function (value) {
        value.bind(function () {
            // This requires a page refresh
        });
    });

    // Blog layout (refresh required)
    wp.customize('neve_lite_blog_layout', function (value) {
        value.bind(function (to) {
            $('.nv-posts-wrapper')
                .removeClass('nv-layout-list nv-layout-grid nv-layout-masonry')
                .addClass('nv-layout-' + to);
        });
    });

    // Posts per row (refresh required)
    wp.customize('neve_lite_posts_per_row', function (value) {
        value.bind(function (to) {
            $('.nv-posts-grid').css('--nv-posts-columns', to);
        });
    });

    // Show/hide elements
    wp.customize('neve_lite_show_author', function (value) {
        value.bind(function (to) {
            $('.nv-entry-meta .byline').toggle(to);
        });
    });

    wp.customize('neve_lite_show_date', function (value) {
        value.bind(function (to) {
            $('.nv-entry-meta .posted-on').toggle(to);
        });
    });

    wp.customize('neve_lite_show_categories', function (value) {
        value.bind(function (to) {
            $('.nv-entry-meta .cat-links').toggle(to);
        });
    });

    wp.customize('neve_lite_show_featured_image', function (value) {
        value.bind(function (to) {
            $('.nv-post-thumbnail-wrap').toggle(to);
        });
    });
})(jQuery);
