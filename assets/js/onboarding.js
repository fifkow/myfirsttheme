/**
 * Neve Lite Onboarding JavaScript
 */

(function($) {
    'use strict';

    // State
    var state = {
        currentStep: 1,
        selectedDemo: null,
        selectedBuilder: 'gutenberg',
        plugins: {},
        isImporting: false
    };

    // Plugin display names
    var pluginNames = {
        'woocommerce': 'WooCommerce',
        'elementor': 'Elementor',
        'contact-form-7': 'Contact Form 7',
        'restaurant-reservations': 'Restaurant Reservations',
        'mailchimp-for-wp': 'Mailchimp for WordPress'
    };

    // Plugin descriptions
    var pluginDescriptions = {
        'woocommerce': 'Wtyczka e-commerce do sprzedaży produktów online',
        'elementor': 'Kreator stron typu drag & drop',
        'contact-form-7': 'Proste i elastyczne formularze kontaktowe',
        'restaurant-reservations': 'System rezerwacji stolików dla restauracji',
        'mailchimp-for-wp': 'Integracja z Mailchimp do newslettera'
    };

    // Initialize
    $(document).ready(function() {
        initBuilderSelection();
        initDemoSelection();
        initDemoPreview();
        initStepNavigation();
        initImportProcess();
    });

    /**
     * Initialize builder selection
     */
    function initBuilderSelection() {
        $('.builder-option').on('click', function() {
            $('.builder-option').removeClass('active');
            $(this).addClass('active');
            $(this).find('input').prop('checked', true);
            
            state.selectedBuilder = $(this).find('input').val();
        });
    }

    /**
     * Initialize demo selection
     */
    function initDemoSelection() {
        $('.demo-card').on('click', function(e) {
            // Don't select if preview button was clicked
            if ($(e.target).closest('.demo-preview-btn').length) {
                return;
            }

            var $card = $(this);
            var demo = $card.data('demo');

            // Remove selection from all cards
            $('.demo-card').removeClass('selected');
            
            // Add selection to clicked card
            $card.addClass('selected');
            
            // Update state
            state.selectedDemo = demo;
            
            // Enable next button
            $('.next-step[data-next="2"]').prop('disabled', false);
        });
    }

    /**
     * Initialize demo preview modal
     */
    function initDemoPreview() {
        // Open preview modal
        $(document).on('click', '.demo-preview-btn', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            var demo = $(this).data('demo');
            openPreviewModal(demo);
        });

        // Close modal
        $('#demo-preview-close').on('click', closePreviewModal);
        
        // Close on backdrop click
        $('#demo-preview-modal').on('click', function(e) {
            if (e.target === this) {
                closePreviewModal();
            }
        });

        // Close on escape key
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape') {
                closePreviewModal();
            }
        });

        // Select this demo from modal
        $('.select-this-demo').on('click', function() {
            var demo = $(this).data('demo');
            
            // Close modal
            closePreviewModal();
            
            // Select the demo card
            $('.demo-card').removeClass('selected');
            $('.demo-card[data-demo="' + demo + '"]').addClass('selected');
            
            // Update state
            state.selectedDemo = demo;
            
            // Enable next button
            $('.next-step[data-next="2"]').prop('disabled', false);
        });
    }

    /**
     * Open preview modal
     */
    function openPreviewModal(demo) {
        var demoData = neveLiteOnboarding.demos[demo];
        
        if (!demoData) {
            return;
        }

        // Set modal content
        $('#preview-demo-name').text(demoData.name);
        $('#preview-demo-description').text(demoData.description);
        $('#preview-demo-image').attr('src', demoData.preview).attr('alt', demoData.name);
        $('#preview-demo-color').css('background-color', demoData.color);
        $('#preview-demo-fonts').text(demoData.typography.heading + ' + ' + demoData.typography.body);
        
        // Build plugins list
        var pluginsHtml = '';
        if (demoData.plugins && demoData.plugins.length > 0) {
            demoData.plugins.forEach(function(plugin) {
                var name = pluginNames[plugin] || plugin;
                pluginsHtml += '<li>' + name + '</li>';
            });
        } else {
            pluginsHtml = '<li>Brak wymaganych wtyczek</li>';
        }
        $('#preview-demo-plugins').html(pluginsHtml);
        
        // Set demo data on select button
        $('.select-this-demo').data('demo', demo);
        
        // Show modal
        $('#demo-preview-modal').addClass('active');
        
        // Prevent body scroll
        $('body').css('overflow', 'hidden');
    }

    /**
     * Close preview modal
     */
    function closePreviewModal() {
        $('#demo-preview-modal').removeClass('active');
        $('body').css('overflow', '');
    }

    /**
     * Initialize step navigation
     */
    function initStepNavigation() {
        // Next step
        $('.next-step').on('click', function() {
            var nextStep = $(this).data('next');
            goToStep(nextStep);
        });

        // Previous step
        $('.prev-step').on('click', function() {
            var prevStep = $(this).data('prev');
            goToStep(prevStep);
        });
    }

    /**
     * Go to specific step
     */
    function goToStep(step) {
        // Update state
        state.currentStep = step;

        // Update step indicator
        $('.step').removeClass('active');
        $('.step[data-step="' + step + '"]').addClass('active');
        
        // Mark previous steps as completed
        $('.step').each(function() {
            var stepNum = parseInt($(this).data('step'));
            if (stepNum < step) {
                $(this).addClass('completed');
            }
        });

        // Show step content
        $('.step-content').removeClass('active');
        $('.step-content[data-step="' + step + '"]').addClass('active');

        // Step-specific actions
        if (step === 2) {
            loadPluginsList();
        }
    }

    /**
     * Load plugins list for selected demo
     */
    function loadPluginsList() {
        var $container = $('#plugins-list');
        
        if (!state.selectedDemo || state.selectedDemo === 'blank') {
            $container.html('<p style="padding: 30px; text-align: center; color: #646970;">' + 
                'Wybrałeś start od podstaw - nie są wymagane żadne wtyczki.</p>');
            return;
        }

        var demo = neveLiteOnboarding.demos[state.selectedDemo];
        
        // Debug
        console.log('Selected builder:', state.selectedBuilder);
        console.log('Demo:', demo);
        console.log('plugins_elementor:', demo.plugins_elementor);
        
        // Get plugins based on selected builder
        var plugins = [];
        if (state.selectedBuilder === 'elementor') {
            // Use Elementor plugins if available
            if (demo.plugins_elementor && Array.isArray(demo.plugins_elementor) && demo.plugins_elementor.length > 0) {
                plugins = demo.plugins_elementor;
            }
        } else {
            // Use Gutenberg plugins
            if (demo.plugins && Array.isArray(demo.plugins) && demo.plugins.length > 0) {
                plugins = demo.plugins;
            }
        }
        
        if (!plugins || plugins.length === 0) {
            $container.html('<p style="padding: 30px; text-align: center; color: #646970;">' + 
                'Brak wymaganych wtyczek dla tego szablonu.</p>');
            return;
        }

        // Build plugins list
        var html = '';
        plugins.forEach(function(plugin) {
            var name = pluginNames[plugin] || plugin;
            var description = pluginDescriptions[plugin] || '';
            
            html += '<div class="plugin-item" data-plugin="' + plugin + '">';
            html += '<div class="plugin-icon">📦</div>';
            html += '<div class="plugin-info">';
            html += '<h4>' + name + '</h4>';
            html += '<p>' + description + '</p>';
            html += '</div>';
            html += '<div class="plugin-status">';
            html += '<span class="status not-installed">Sprawdzanie...</span>';
            html += '</div>';
            html += '<div class="plugin-action">';
            html += '<button class="button install-plugin" data-plugin="' + plugin + '">Zainstaluj</button>';
            html += '</div>';
            html += '</div>';
        });

        $container.html(html);

        // Check plugin status
        checkPluginsStatus();

        // Bind install buttons
        bindPluginInstallButtons();
    }

    /**
     * Check plugins status via AJAX
     */
    function checkPluginsStatus() {
        if (!state.selectedDemo || state.selectedDemo === 'blank') {
            return;
        }

        var demo = neveLiteOnboarding.demos[state.selectedDemo];
        
        demo.plugins.forEach(function(plugin) {
            checkPluginStatus(plugin);
        });
    }

    /**
     * Check single plugin status
     */
    function checkPluginStatus(plugin) {
        // Check if plugin is active
        $.ajax({
            url: neveLiteOnboarding.ajaxUrl,
            type: 'POST',
            data: {
                action: 'neve_lite_check_plugin',
                plugin: plugin,
                nonce: neveLiteOnboarding.nonce
            },
            success: function(response) {
                updatePluginStatus(plugin, response.data.status);
            },
            error: function() {
                // Assume not installed on error
                updatePluginStatus(plugin, 'not-installed');
            }
        });
    }

    /**
     * Update plugin status UI
     */
    function updatePluginStatus(plugin, status) {
        var $item = $('.plugin-item[data-plugin="' + plugin + '"]');
        var $status = $item.find('.status');
        var $button = $item.find('.button');

        state.plugins[plugin] = status;

        switch (status) {
            case 'active':
                $status.removeClass('not-installed installed').addClass('active');
                $status.text('Aktywna');
                $button.text('Aktywna').prop('disabled', true).removeClass('button-primary');
                break;
            case 'installed':
                $status.removeClass('not-installed active').addClass('installed');
                $status.text('Zainstalowana');
                $button.text('Aktywuj').addClass('activate-plugin button-primary').removeClass('install-plugin');
                break;
            case 'not-installed':
            default:
                $status.removeClass('installed active').addClass('not-installed');
                $status.text('Niezainstalowana');
                $button.text('Zainstaluj').addClass('install-plugin button-primary').removeClass('activate-plugin');
                break;
        }
    }

    /**
     * Bind plugin install buttons
     */
    function bindPluginInstallButtons() {
        // Install plugin
        $(document).off('click', '.install-plugin').on('click', '.install-plugin', function(e) {
            e.preventDefault();
            var plugin = $(this).data('plugin');
            installPlugin(plugin);
        });

        // Activate plugin
        $(document).off('click', '.activate-plugin').on('click', '.activate-plugin', function(e) {
            e.preventDefault();
            var plugin = $(this).data('plugin');
            activatePlugin(plugin);
        });
    }

    /**
     * Install plugin
     */
    function installPlugin(plugin) {
        var $item = $('.plugin-item[data-plugin="' + plugin + '"]');
        var $button = $item.find('.button');
        var $status = $item.find('.status');

        $button.prop('disabled', true).html('<span class="spinner is-active"></span> Instalowanie...');
        $status.text('Instalowanie...');

        $.ajax({
            url: neveLiteOnboarding.ajaxUrl,
            type: 'POST',
            data: {
                action: 'neve_lite_install_plugin',
                plugin: plugin,
                nonce: neveLiteOnboarding.nonce
            },
            success: function(response) {
                if (response.success) {
                    // After install, activate
                    activatePlugin(plugin);
                } else {
                    $button.prop('disabled', false).text('Zainstaluj');
                    $status.text('Błąd: ' + response.data);
                    showNotice('Błąd podczas instalacji wtyczki: ' + response.data, 'error');
                }
            },
            error: function(xhr, status, error) {
                $button.prop('disabled', false).text('Zainstaluj');
                $status.text('Błąd');
                showNotice('Błąd podczas instalacji wtyczki. Spróbuj ponownie.', 'error');
            }
        });
    }

    /**
     * Activate plugin
     */
    function activatePlugin(plugin) {
        var $item = $('.plugin-item[data-plugin="' + plugin + '"]');
        var $button = $item.find('.button');
        var $status = $item.find('.status');

        $button.prop('disabled', true).html('<span class="spinner is-active"></span> Aktywowanie...');
        $status.text('Aktywowanie...');

        $.ajax({
            url: neveLiteOnboarding.ajaxUrl,
            type: 'POST',
            data: {
                action: 'neve_lite_activate_plugin',
                plugin: plugin,
                nonce: neveLiteOnboarding.nonce
            },
            success: function(response) {
                if (response.success) {
                    updatePluginStatus(plugin, 'active');
                    checkAllPluginsInstalled();
                } else {
                    $button.prop('disabled', false).text('Aktywuj');
                    $status.text('Błąd: ' + response.data);
                    showNotice('Błąd podczas aktywacji wtyczki: ' + response.data, 'error');
                }
            },
            error: function() {
                $button.prop('disabled', false).text('Aktywuj');
                $status.text('Błąd');
                showNotice('Błąd podczas aktywacji wtyczki. Spróbuj ponownie.', 'error');
            }
        });
    }

    /**
     * Check if all plugins are installed
     */
    function checkAllPluginsInstalled() {
        var allActive = true;
        
        Object.keys(state.plugins).forEach(function(plugin) {
            if (state.plugins[plugin] !== 'active') {
                allActive = false;
            }
        });

        if (allActive) {
            showNotice('Wszystkie wtyczki są gotowe!', 'success');
        }
    }

    /**
     * Initialize import process
     */
    function initImportProcess() {
        $('#start-import').on('click', function() {
            if (state.isImporting) {
                return;
            }

            startImport();
        });
    }

    /**
     * Start import process
     */
    function startImport() {
        state.isImporting = true;

        // Show progress
        $('#import-progress').show();
        updateProgress(5, 'Przygotowanie do importu...');

        // Disable button
        $('#start-import').prop('disabled', true).text('Importowanie...');

        // If blank starter, skip import
        if (state.selectedDemo === 'blank') {
            setTimeout(function() {
                updateProgress(100, 'Gotowe!');
                setTimeout(function() {
                    goToStep(4);
                }, 500);
            }, 1000);
            return;
        }

        // Step 1: Initialize import
        $.ajax({
            url: neveLiteOnboarding.ajaxUrl,
            type: 'POST',
            data: {
                action: 'neve_lite_import_start',
                demo: state.selectedDemo,
                builder: state.selectedBuilder,
                nonce: neveLiteOnboarding.nonce
            },
            success: function(response) {
                if (response.success) {
                    updateProgress(10, response.data.message);
                    // Start batch import
                    importBatch(response.data.total);
                } else {
                    importError(response.data);
                }
            },
            error: function() {
                importError('Błąd podczas inicjalizacji importu');
            }
        });
    }

    /**
     * Import batch of items
     */
    function importBatch(total) {
        $.ajax({
            url: neveLiteOnboarding.ajaxUrl,
            type: 'POST',
            data: {
                action: 'neve_lite_import_batch',
                nonce: neveLiteOnboarding.nonce
            },
            success: function(response) {
                if (response.success) {
                    updateProgress(response.data.percentage, response.data.message);
                    
                    if (response.data.complete) {
                        // All batches done, finish import
                        finishImport();
                    } else {
                        // Continue with next batch
                        setTimeout(function() {
                            importBatch(total);
                        }, 200);
                    }
                } else {
                    importError(response.data);
                }
            },
            error: function() {
                importError('Błąd podczas importowania treści');
            }
        });
    }

    /**
     * Finish import - set homepage and cleanup
     */
    function finishImport() {
        updateProgress(95, 'Finalizowanie...');
        
        $.ajax({
            url: neveLiteOnboarding.ajaxUrl,
            type: 'POST',
            data: {
                action: 'neve_lite_import_finish',
                nonce: neveLiteOnboarding.nonce
            },
            success: function(response) {
                if (response.success) {
                    updateProgress(100, 'Import zakończony!');
                    setTimeout(function() {
                        goToStep(4);
                    }, 800);
                } else {
                    importError(response.data);
                }
            },
            error: function() {
                importError('Błąd podczas finalizacji importu');
            }
        });
    }

    /**
     * Handle import error
     */
    function importError(message) {
        state.isImporting = false;
        $('#start-import').prop('disabled', false).text('Spróbuj ponownie');
        showNotice('Błąd podczas importu: ' + message, 'error');
    }

    /**
     * Update progress bar
     */
    function updateProgress(percent, status) {
        $('.progress-fill').css('width', percent + '%');
        $('.progress-status').text(status);
    }

    /**
     * Show notice
     */
    function showNotice(message, type) {
        var $notice = $('<div class="notice-inline ' + type + '">' + message + '</div>');
        
        // Remove existing notices
        $('.notice-inline').remove();
        
        // Add new notice
        $('.step-content.active .step-actions').before($notice);

        // Auto remove after 5 seconds
        setTimeout(function() {
            $notice.fadeOut(function() {
                $(this).remove();
            });
        }, 5000);
    }

})(jQuery);
