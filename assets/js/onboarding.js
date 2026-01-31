(function ($) {
    'use strict';

    const NeveOnboarding = {
        data: {
            selectedDemo: null,
            selectedBuilder: 'gutenberg', // Domyślna wartość
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
                // Fallback dla obrazka jeśli nie istnieje
                const img = demo.preview ? demo.preview : 'https://via.placeholder.com/400x300?text=Demo';

                const html = `
                    <div class="demo-card" data-demo="${key}">
                        <div class="demo-preview">
                            <img src="${img}" alt="${demo.title}">
                        </div>
                        <div class="demo-footer">
                            <h3>${demo.title}</h3>
                            <button class="button button-primary select-demo-btn">Wybierz</button>
                        </div>
                    </div>
                `;
                container.append(html);
            });
        },

        bindEvents: function () {
            const self = this;

            // Krok 1: Wybór Demo
            $(document).on('click', '.select-demo-btn', function (e) {
                e.preventDefault();
                const card = $(this).closest('.demo-card');
                self.data.selectedDemo = card.data('demo');

                $('.demo-card').removeClass('selected');
                card.addClass('selected');

                self.goToStep(2);
            });

            // Krok 2: Wybór Buildera - KLIKALNOŚĆ
            $(document).on('click', '.builder-card', function () {
                $('.builder-card').removeClass('selected');
                $(this).addClass('selected');
                self.data.selectedBuilder = $(this).data('builder');
                console.log('Wybrano builder:', self.data.selectedBuilder);
            });

            // Nawigacja "Dalej" w kroku 2
            $('.step-select-builder .next-step').on('click', function () {
                self.goToStep(3);
                self.prepareImport();
            });

            // Nawigacja "Wstecz"
            $('.prev-step').on('click', function() {
                const goto = $(this).data('goto');
                self.goToStep(goto);
            });

            // Krok 3: Start Importu
            $('.start-import').on('click', function () {
                $(this).addClass('disabled').text('Instalowanie w toku...');
                self.processQueue();
            });
        },

        goToStep: function (stepNumber) {
            // Ukryj wszystko
            $('.step-content').removeClass('active');
            $('.step-dot').removeClass('active');

            // Pokaż właściwy krok
            $(this.data.steps[stepNumber]).addClass('active');

            // Zaktualizuj nagłówek (kropki)
            $(`.step-dot[data-step="${stepNumber}"]`).addClass('active');
            // Zaznacz też poprzednie jako aktywne dla estetyki
            for(let i=1; i<stepNumber; i++) {
                $(`.step-dot[data-step="${i}"]`).addClass('active');
            }
        },

        prepareImport: function () {
            const list = $('.import-progress-list');
            list.empty();
            list.append('<li class="status-waiting" id="task-plugins"><span class="dashicons dashicons-admin-plugins"></span> Sprawdzanie i instalacja wtyczek...</li>');
            list.append('<li class="status-waiting" id="task-content"><span class="dashicons dashicons-download"></span> Importowanie treści demo...</li>');

            // Pobierz listę wtyczek do instalacji
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
                    builder: self.data.selectedBuilder // Przekazujemy wybrany builder!
                },
                success: function (response) {
                    if (response.success) {
                        self.pluginsQueue = response.data;
                        if (self.pluginsQueue.length > 0) {
                            $('#task-plugins').html('<span class="dashicons dashicons-admin-plugins"></span> Do zainstalowania: ' + self.pluginsQueue.length + ' wtyczek (w tym Importer)...');
                        } else {
                            $('#task-plugins').addClass('done').html('<span class="dashicons dashicons-yes"></span> Wtyczki gotowe.');
                        }
                    }
                }
            });
        },

        processQueue: function () {
            const self = this;

            // 1. Instalacja wtyczek (rekurencyjnie)
            if (self.pluginsQueue && self.pluginsQueue.length > 0) {
                const plugin = self.pluginsQueue.shift(); // Pobierz pierwszą
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
                            self.processQueue(); // Jedziemy z kolejną
                        } else {
                            alert('Błąd instalacji wtyczki: ' + res.data.message);
                        }
                    },
                    error: function() {
                        alert('Błąd połączenia podczas instalacji wtyczek.');
                    }
                });
                return;
            }

            // Oznacz wtyczki jako gotowe
            $('#task-plugins').removeClass('status-waiting').addClass('done').html('<span class="dashicons dashicons-yes"></span> Wszystkie wtyczki zainstalowane i aktywne.');

            // 2. Import Contentu
            $('#task-content').html('<span class="spinner is-active" style="float:none;margin:0 5px 0 0;"></span> Importowanie XML (może to zająć chwilę)...');

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
                        $('#task-content').addClass('done').html('<span class="dashicons dashicons-yes"></span> Treść zaimportowana pomyślnie.');
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
                     $('#task-content').addClass('error').html('<span class="dashicons dashicons-warning"></span> Błąd serwera (Timeout?).');
                     alert('Błąd serwera. Sprawdź czy plik XML istnieje w folderze demo-content i czy serwer nie zabił procesu.');
                }
            });
        }
    };

    $(document).ready(function () {
        NeveOnboarding.init();
    });

})(jQuery);
