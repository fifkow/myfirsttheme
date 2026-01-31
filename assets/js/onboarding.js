(function ($) {
    'use strict';

    const NeveOnboarding = {
        data: {
            selectedDemo: null,
            selectedBuilder: 'gutenberg',
            previewDemo: null,
            steps: {
                1: '.step-select-demo',
                2: '.step-select-builder',
                3: '.step-import',
                4: '.step-success'
            }
        },

        init: function () {
            this.renderDemos();
            this.bindEvents();
        },

        renderDemos: function () {
            const container = $('.demos-grid');
            const demos = neveOnboarding.demos;

            if (!container.length) return;

            Object.keys(demos).forEach(key => {
                const demo = demos[key];
                const img = demo.preview ? demo.preview : 'https://via.placeholder.com/400x300?text=Demo';

                const html = `
                    <div class="demo-card" data-demo="${key}" data-img="${img}">
                        <div class="demo-preview trigger-preview">
                            <img src="${img}" alt="${demo.title}">
                        </div>
                        <div class="demo-footer">
                            <h3>${demo.title}</h3>
                            <div class="demo-actions">
                                <button class="button button-secondary button-preview trigger-preview">Podgląd</button>
                                <button class="button button-primary select-demo-btn">Wybierz</button>
                            </div>
                        </div>
                    </div>
                `;
                container.append(html);
            });
        },

        bindEvents: function () {
            const self = this;

            // PREVIEW MODAL
            $(document).on('click', '.trigger-preview', function (e) {
                e.preventDefault();
                e.stopPropagation();
                const card = $(this).closest('.demo-card');
                const img = card.data('img');
                const demoKey = card.data('demo');

                self.data.previewDemo = demoKey; // Zapamiętaj co podglądamy

                $('#modal-preview-image').attr('src', img);
                $('.neve-modal-overlay').fadeIn(200).css('display', 'flex');
            });

            $('.close-modal, .neve-modal-overlay').on('click', function(e) {
                if (e.target !== this && !$(e.target).hasClass('close-modal') && !$(e.target).parent().hasClass('close-modal')) return;
                $('.neve-modal-overlay').fadeOut(200);
            });

            // Wybór z Modala
            $('.select-demo-from-modal').on('click', function() {
                if(self.data.previewDemo) {
                    self.selectDemo(self.data.previewDemo);
                    $('.neve-modal-overlay').fadeOut();
                }
            });

            // Wybór z Grida
            $(document).on('click', '.select-demo-btn', function (e) {
                e.preventDefault();
                const card = $(this).closest('.demo-card');
                self.selectDemo(card.data('demo'));
            });

            // Wybór Buildera
            $(document).on('click', '.builder-card', function () {
                $('.builder-card').removeClass('selected');
                $(this).addClass('selected');
                self.data.selectedBuilder = $(this).data('builder');
            });

            // Nawigacja
            $('.step-select-builder .next-step').on('click', function () {
                self.goToStep(3);
                self.prepareImport();
            });

            $('.prev-step').on('click', function() {
                self.goToStep($(this).data('goto'));
            });

            // Start Importu
            $('.start-import').on('click', function () {
                $(this).addClass('disabled').text('Instalowanie w toku...');
                self.processQueue();
            });
        },

        selectDemo: function(key) {
            this.data.selectedDemo = key;

            // UI Update
            $('.demo-card').removeClass('selected');
            $(`.demo-card[data-demo="${key}"]`).addClass('selected');

            this.goToStep(2);
        },

        goToStep: function (stepNumber) {
            $('.step-content').removeClass('active');
            $('.step-dot').removeClass('active');
            $(this.data.steps[stepNumber]).addClass('active');

            for(let i=1; i<=stepNumber; i++) {
                $(`.step-dot[data-step="${i}"]`).addClass('active');
            }
        },

        prepareImport: function () {
            const list = $('.import-progress-list');
            list.empty();
            list.append('<li class="status-waiting" id="task-plugins"><span class="dashicons dashicons-admin-plugins"></span> Sprawdzanie wymaganych wtyczek...</li>');
            list.append('<li class="status-waiting" id="task-content"><span class="dashicons dashicons-download"></span> Pobieranie i import treści demo...</li>');

            this.getPluginsToInstall();
        },

        getPluginsToInstall: function () {
            const self = this;
            $.ajax({
                url: neveOnboarding.ajaxurl,
                type: 'POST',
                data: {
                    action: 'neve_onboarding_get_plugins',
                    nonce: neveOnboarding.nonce,
                    demo: self.data.selectedDemo,
                    builder: self.data.selectedBuilder
                },
                success: function (response) {
                    if (response.success) {
                        self.pluginsQueue = response.data;
                        if (self.pluginsQueue.length > 0) {
                            $('#task-plugins').html('<span class="dashicons dashicons-admin-plugins"></span> Do zainstalowania: ' + self.pluginsQueue.length + ' wtyczek...');
                        } else {
                            $('#task-plugins').addClass('done').html('<span class="dashicons dashicons-yes"></span> Wtyczki gotowe.');
                        }
                    }
                }
            });
        },

        processQueue: function () {
            const self = this;

            // 1. Instalacja wtyczek
            if (self.pluginsQueue && self.pluginsQueue.length > 0) {
                const plugin = self.pluginsQueue.shift();
                $('#task-plugins').html(`<span class="spinner is-active" style="float:none;margin:0 5px 0 0;"></span> Instaluję: ${plugin.name}...`);

                $.ajax({
                    url: neveOnboarding.ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'neve_onboarding_install_plugins',
                        nonce: neveOnboarding.nonce,
                        slug: plugin.slug
                    },
                    success: function (res) {
                        if (res.success) {
                            self.processQueue();
                        } else {
                            alert('Błąd instalacji wtyczki: ' + res.data.message);
                            $('#task-plugins').addClass('error').text('Błąd instalacji.');
                        }
                    },
                    error: function() {
                        $('#task-plugins').addClass('error').text('Błąd połączenia.');
                    }
                });
                return;
            }

            $('#task-plugins').removeClass('status-waiting').addClass('done').html('<span class="dashicons dashicons-yes"></span> Wszystkie wtyczki zainstalowane.');

            // 2. Import Contentu
            $('#task-content').html('<span class="spinner is-active" style="float:none;margin:0 5px 0 0;"></span> Importowanie XML (nie zamykaj okna)...');

            $.ajax({
                url: neveOnboarding.ajaxurl,
                type: 'POST',
                data: {
                    action: 'neve_onboarding_import_content',
                    nonce: neveOnboarding.nonce,
                    demo: self.data.selectedDemo,
                    builder: self.data.selectedBuilder
                },
                success: function (response) {
                    if (response.success) {
                        $('#task-content').addClass('done').html('<span class="dashicons dashicons-yes"></span> Sukces!');
                        setTimeout(function() {
                            self.goToStep(4);
                        }, 1000);
                    } else {
                        $('#task-content').addClass('error').html('<span class="dashicons dashicons-warning"></span> Błąd: ' + response.data.message);
                        console.error(response.data);
                        alert('Błąd importu: ' + response.data.message);
                    }
                },
                error: function(xhr) {
                     $('#task-content').addClass('error').html('<span class="dashicons dashicons-warning"></span> Błąd serwera (500).');
                     alert('Serwer zwrócił błąd 500. Sprawdź logi PHP. Prawdopodobnie timeout lub brak pamięci.');
                }
            });
        }
    };

    $(document).ready(function () {
        NeveOnboarding.init();
    });

})(jQuery);
