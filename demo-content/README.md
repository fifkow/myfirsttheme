# Neve Lite - Demo Content

Ten katalog zawiera 6 gotowych demo stron do importu w WordPressie.

## Lista demo stron

### 1. Sklep WooCommerce - Odzież sportowa
**Plik:** `demo-sportswear-shop.xml`
- **Nazwa:** ActiveWear Pro
- **Branża:** Odzież sportowa i fitness
- **Kolorystyka:** Niebieski (#0073aa), szary
- **Typografia:** Inter
- **Strony:** Home, O marce
- **Funkcje:** Hero slider, kategorie produktów, bestsellery, opinie klientów

### 2. Salon Beauty / Kosmetologia
**Plik:** `demo-beauty-salon.xml`
- **Nazwa:** LaVie Beauty Clinic
- **Branża:** Kosmetologia estetyczna
- **Kolorystyka:** Złoty/beżowy (#d4a574), biały
- **Typografia:** Lekkie, eleganckie nagłówki
- **Strony:** Home, Cennik
- **Funkcje:** Hero z video tłem, zabiegi, cennik, opinie, promocje

### 3. Firma Budowlana / Remontowa
**Plik:** `demo-construction.xml`
- **Nazwa:** BudMax
- **Branża:** Wykończenia wnętrz i remonty
- **Kolorystyka:** Pomarańczowy (#f39c12), ciemny szary
- **Typografia:** Mocna, profesjonalna
- **Strony:** Home
- **Funkcje:** Usługi, realizacje, formularz wyceny, galeria

### 4. Restauracja / Kawiarnia
**Plik:** `demo-restaurant.xml`
- **Nazwa:** Trattoria Bella Italia
- **Branża:** Kuchnia włoska
- **Kolorystyka:** Czerwień włoska (#c41e3a), kremowy
- **Typografia:** Kursywa, włoski styl
- **Strony:** Home, Menu
- **Funkcje:** Menu, rezerwacje, godziny otwarcia, opinie

### 5. Produkt Cyfrowy / SaaS
**Plik:** `demo-saas.xml`
- **Nazwa:** CloudFlow
- **Branża:** Zarządzanie projektami (SaaS)
- **Kolorystyka:** Fioletowy/Indigo (#6366f1), biały
- **Typografia:** Nowoczesna, startupowa
- **Strony:** Landing page (Home)
- **Funkcje:** Features, pricing, FAQ, CTA, social proof

### 6. Blog Ekspercki / Personal Brand
**Plik:** `demo-blog.xml`
- **Nazwa:** Marek Nowakowski - Marketing i Biznes
- **Branża:** Marketing, biznes, rozwój osobisty
- **Kolorystyka:** Bursztynowy (#f59e0b), ciemny szary
- **Typografia:** Czytelna, profesjonalna
- **Strony:** Home, O mnie, Przykładowy post
- **Funkcje:** Newsletter, kategorie, ebook, opinie czytelników

## Jak zaimportować demo content

### Metoda 1: Przez WordPress Importer

1. Zaloguj się do panelu WordPress
2. Przejdź do **Narzędzia → Import**
3. Zainstaluj i uruchom **WordPress Importer**
4. Wybierz jeden z plików XML z tego katalogu
5. Kliknij "Prześlij plik i zaimportuj"
6. Przypisz autorów lub utwórz nowych
7. Zaznacz "Pobierz i zaimportuj załączniki plików" (opcjonalnie)
8. Kliknij "Zatwierdź"

### Metoda 2: Przez WP All Import (zalecane)

1. Zainstaluj wtyczkę **WP All Import**
2. Przejdź do **All Import → New Import**
3. Wybierz plik XML
4. Wybierz typ importu (posty, strony, produkty)
5. Skonfiguruj mapowanie pól
6. Uruchom import

### Metoda 3: Ręcznie przez kod

```php
// W functions.php lub wtyczce
require_once( ABSPATH . 'wp-admin/includes/import.php' );
$importer = get_importer( 'wordpress' );
```

## Wymagania przed importem

1. **Zainstaluj wymagane wtyczki:**
   - WooCommerce (dla demo sklepu)
   - Elementor (dla wszystkich demo)
   - Contact Form 7 lub WPForms (dla formularzy)

2. **Skonfiguruj motyw Neve Lite**
   - Aktywuj motyw
   - Skonfiguruj Customizer (logo, kolory)

3. **Przygotuj media:**
   - Demo używa obrazów z Unsplash (linki zewnętrzne)
   - Możesz zastąpić je własnymi po imporcie

## Po imporcie

1. **Ustaw stronę główną:**
   - Ustawienia → Czytanie → Strona główna

2. **Skonfiguruj menu:**
   - Wygląd → Menu
   - Przypisz menu do lokalizacji "Primary"

3. **Dostosuj widgety:**
   - Wygląd → Widgety

4. **Sprawdź ustawienia WooCommerce:**
   - WooCommerce → Ustawienia (dla demo sklepu)

## Rozwiązywanie problemów

### Brak obrazków
- Demo używa zewnętrznych linków do Unsplash
- Jeśli obrazki się nie wyświetlają, sprawdź połączenie internetowe
- Możesz ręcznie pobrać i zastąpić obrazki

### Błąd importu
- Zwiększ limit pamięci PHP w wp-config.php:
  ```php
  define('WP_MEMORY_LIMIT', '256M');
  ```
- Zwiększ limit czasu wykonania w php.ini

### Brakujące wtyczki
- Instaluj wtyczki w kolejności: Elementor → WooCommerce → pozostałe

## Dostosowanie demo

Po imporcie możesz dostosować demo do swoich potrzeb:

1. **Zmień kolory** w Customizerze
2. **Zastąp obrazki** własnymi
3. **Edytuj treść** w Elementorze
4. **Dodaj/usuń sekcje** według potrzeb
5. **Skonfiguruj formularze** kontaktowe

## Licencja obrazków

Wszystkie obrazki użyte w demo pochodzą z Unsplash i są objęte licencją Unsplash License (darmowe do użytku komercyjnego).

## Wsparcie

W razie problemów z importem:
1. Sprawdź dokumentację WordPress Importer
2. Skonsultuj się z dokumentacją Neve Lite
3. Skontaktuj się z autorem motywu
