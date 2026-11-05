# Website copy draft: Business Details PL 0.1.0

Internal copy for a future bilingual Digicyfr download page. Do not publish until the screenshot, support contact, and downloadable ZIP URL are set and checked.

## English

**Title:** Business Details PL — free WordPress plugin

**Summary:** Enter your Polish business details once and show only the fields you choose on a page. Use a WordPress block or the `[digicyfr_business_details]` shortcode. The plugin works without an account or external lookup.

**What it shows:** business name, address, NIP, optional REGON and KRS, email, phone, and website. Empty fields stay hidden. The NIP check covers format and checksum only; it does not check a register, VAT status, or legal disclosure requirements.

**Install:** In WordPress, open Plugins → Add New → Upload Plugin, select the ZIP, and activate it. Open Settings → Business Details PL, save the details you intend to publish, then add the Business Details PL block or `[digicyfr_business_details fields="name,address,nip"]` to a page.

**Compatibility tested:** WordPress 7.1 and PHP 8.3.33, including interactive block-editor insertion. Older versions and multisite network behavior have not been verified. A manual screen-reader check remains open.

**Privacy and removal:** The details stay in the site's database and are public only where you place the block or shortcode. The plugin sends no telemetry and makes no registry requests. Uninstall removes its saved option; export or back up the details first if needed.

**Updates:** Manual ZIP update. No automatic update service is provided in 0.1.0.

## Polski

**Tytuł:** Business Details PL — bezpłatna wtyczka WordPress

**Opis:** Wpisz dane polskiej firmy raz i pokaż na stronie tylko wybrane pola. Użyj bloku WordPress albo shortcode `[digicyfr_business_details]`. Wtyczka działa bez konta i bez zewnętrznego wyszukiwania.

**Dostępne pola:** nazwa firmy, adres, NIP, opcjonalnie REGON i KRS, e-mail, telefon i strona internetowa. Puste pola są ukrywane. Kontrola NIP obejmuje wyłącznie format i sumę kontrolną; nie potwierdza wpisu w rejestrze, statusu VAT ani zgodności z obowiązkami informacyjnymi.

**Instalacja:** W panelu WordPress wybierz Wtyczki → Dodaj nową → Wyślij wtyczkę, wskaż plik ZIP i aktywuj wtyczkę. Otwórz Ustawienia → Business Details PL, zapisz dane przeznaczone do publikacji i dodaj blok Business Details PL albo `[digicyfr_business_details fields="name,address,nip"]` na stronie.

**Sprawdzona zgodność:** WordPress 7.1 i PHP 8.3.33, w tym interaktywne wstawianie bloku. Nie zweryfikowano starszych wersji ani sieci multisite. Test z czytnikiem ekranu pozostaje do wykonania.

**Dane i usuwanie:** Dane pozostają w bazie witryny i są publiczne tylko w miejscu użycia bloku lub shortcode. Wtyczka nie wysyła telemetrii ani zapytań do rejestrów. Odinstalowanie usuwa zapisane ustawienie; w razie potrzeby wykonaj wcześniej kopię danych.

**Aktualizacje:** Ręcznie przez plik ZIP. W wersji 0.1.0 nie ma automatycznych aktualizacji.

## Release metadata to render with either language

- Version: `0.1.0` (local release candidate)
- License: GPL-2.0-or-later
- ZIP: `dist/digicyfr-business-details-pl-0.1.0.zip`
- SHA-256: `91549cc23873085c24e728bfd030b038a8e1765ade3239c33d5c2e9f7519cb2c`
- Source URL: https://github.com/digicyfr/digicyfr-business-details-pl
- Screenshot: pending capture/selection
- Public ZIP URL and support contact: pending release setup
- Changelog: [CHANGELOG.md](../CHANGELOG.md)
- Full instructions: [README.md](../README.md)
