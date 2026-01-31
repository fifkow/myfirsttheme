# Neve Lite - Motyw WordPress

Lekki, szybki i w pełni kompatybilny z Elementor oraz WooCommerce motyw WordPress.

## Funkcje

- **Performance-first** - lekki DOM, brak zbędnych wrapperów
- **W pełni responsywny** - mobile-first approach
- **SEO-friendly** - zoptymalizowany pod kątem wyszukiwarek
- **Accessibility** - zgodność z WCAG basics
- **Elementor Ready** - pełna kompatybilność z Elementor
- **WooCommerce Ready** - gotowy do sprzedaży online

## Wymagania

- WordPress 6.x+
- PHP 8.x

## Instalacja

1. Pobierz motyw jako plik ZIP
2. Przejdź do Wygląd → Motywy → Dodaj nowy → Wgraj motyw
3. Aktywuj motyw

## Struktura motywu

```
neve-lite/
├── style.css              # Główny plik stylów
├── functions.php          # Funkcje motywu
├── index.php              # Główny szablon
├── header.php             # Szablon nagłówka
├── footer.php             # Szablon stopki
├── page.php               # Szablon strony
├── single.php             # Szablon pojedynczego wpisu
├── archive.php            # Szablon archiwum
├── search.php             # Szablon wyszukiwania
├── 404.php                # Szablon 404
├── elementor-full-width.php    # Szablon Elementor Full Width
├── elementor-canvas.php        # Szablon Elementor Canvas
├── woocommerce.php        # Szablon WooCommerce
├── template-parts/        # Części szablonów
│   ├── content/
│   │   ├── content.php
│   │   ├── content-page.php
│   │   ├── content-single.php
│   │   ├── content-none.php
│   │   └── content-search.php
│   ├── header/
│   └── footer/
├── assets/
│   ├── css/
│   │   ├── main.css
│   │   ├── woocommerce.css
│   │   └── elementor.css
│   ├── js/
│   │   ├── main.js
│   │   ├── navigation.js
│   │   └── customizer.js
│   └── images/
├── inc/                   # Pliki funkcji
│   ├── customizer.php     # Customizer
│   ├── template-functions.php
│   ├── template-tags.php
│   ├── woocommerce.php    # Integracja WooCommerce
│   └── elementor.php      # Integracja Elementor
├── woocommerce/           # Szablony WooCommerce
│   ├── archive-product.php
│   └── single-product.php
├── child-theme/           # Child theme
│   ├── style.css
│   └── functions.php
└── demo-content/          # Demo content
```

## Customizer

Motyw oferuje rozbudowane opcje w Customizerze:

### Kolory
- Kolor główny
- Kolor drugorzędny
- Kolor tekstu
- Kolor tła
- Kolor linków

### Typografia
- Czcionka body
- Czcionka nagłówków
- Rozmiar bazowy
- Wysokość linii

### Kontener
- Szerokość kontenera
- Padding kontenera

### Nagłówek
- Layout nagłówka
- Sticky header
- Wysokość nagłówka
- Kolor tła
- Kolor tekstu
- Przycisk CTA

### Stopka
- Liczba kolumn widgetów
- Kolor tła
- Kolor tekstu
- Tekst copyright

### Blog
- Layout bloga (list/grid/masonry)
- Posty w rzędzie
- Długość wstępu
- Pokaż/ukryj: autor, data, kategorie, obrazek wyróżniający

### WooCommerce
- Produkty na stronę
- Kolumny w sklepie
- Ilość produktów powiązanych

## Elementor

Motyw jest w pełni kompatybilny z Elementor:

- Szablony Full Width i Canvas
- Wsparcie dla globalnych kolorów
- Wsparcie dla globalnych czcionek
- Wsparcie dla kontenera Flexbox
- Możliwość wyłączenia tytułu/sidebarów

## WooCommerce

Motyw jest gotowy do sprzedaży online:

- Wsparcie dla galerii produktów
- Zoom produktów
- Lightbox
- Slider
- Mini koszyk
- Szybki podgląd
- Lista życzeń
- Odznaki produktów

## Hooki i filtry

### Hooki
- `neve_lite_before_header`
- `neve_lite_after_header`
- `neve_lite_before_archive`
- `neve_lite_after_archive`
- `neve_lite_before_page_content`
- `neve_lite_after_page_content`
- `neve_lite_before_single_content`
- `neve_lite_after_single_content`
- `neve_lite_before_footer`
- `neve_lite_after_footer`

### Filtry
- `neve_lite_show_page_title`
- `neve_lite_has_sidebar`
- `neve_lite_excerpt_length`

## Demo strony

Motyw zawiera 6 gotowych demo stron:

1. **Sklep WooCommerce - Odzież sportowa**
2. **Salon beauty / kosmetologia**
3. **Firma budowlana / remontowa**
4. **Restauracja / kawiarnia**
5. **Produkt cyfrowy / SaaS**
6. **Blog ekspercki / personal brand**

## Tłumaczenia

Motyw jest przygotowany do tłumaczeń. Pliki POT znajdują się w katalogu `languages/`.

## Licencja

GPL v2 lub nowsza

## Autor

Neve Lite Team
