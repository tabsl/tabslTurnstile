# Changelog

Alle wesentlichen Änderungen an diesem Projekt werden in dieser Datei dokumentiert.

Das Format basiert auf [Keep a Changelog](https://keepachangelog.com/de/1.1.0/),
und dieses Projekt folgt [Semantic Versioning](https://semver.org/lang/de/).

## [Unreleased]

## [1.1.1] - 2026-08-21

### Changed
- Fehlgeschlagene Turnstile-Prüfungen und automatische Schutz-Ein-/Ausschaltungen werden jetzt im Shop-Log festgehalten

## [1.1.0] - 2026-08-21

### Added
- Optionaler adaptiver Turnstile-Schutz für Registrierung, Checkout-Kontoerstellung und Gastbestellung: bei normalem Traffic deaktiviert, bei einer Anfragewelle automatisch aktiviert und danach wieder abgeschaltet
- Wellenerkennung zählt unterschiedliche E-Mail-Adressen in einem konfigurierbaren Zeitfenster (Standard: 10 in 5 Minuten); optionale Domainliste schränkt die Zählung ein

### Fixed
- Fehlende serverseitige Turnstile-Prüfung behoben, wenn ein bestehendes Gastkonto seine Adresse im Checkout ändert — bisher war nur die Registrierung selbst abgesichert

## [1.0.6] - 2026-05-06

### Fixed
- Newsletter-Anmeldung prüft die Turnstile-Bestätigung jetzt an der richtigen Stelle im Ablauf

## [1.0.5] - 2026-03-05

### Added
- README um Modulbeschreibung, Installationsanleitung und Konfigurationshinweise ergänzt

### Fixed
- Turnstile-Prüfung läuft in allen Controllern jetzt vor der eigentlichen Aktion (Kontakt, Passwort zurücksetzen, Newsletter)

## [1.0.4] - 2025-08-19

### Fixed
- Kompatibilität mit PHP 7 für den Turnstile-Dienst wiederhergestellt

## [1.0.3] - 2025-07-31

### Fixed
- Fehler bei der Turnstile-Prüfung während der Registrierung behoben

## [1.0.2] - 2025-06-19

### Removed
- Überflüssige Sprachkonfiguration aus den Modul-Metadaten entfernt

## [1.0.1] - 2025-06-14

### Fixed
- Fehler bei der Registrierung in den Modul-Metadaten behoben

## [1.0.0] - 2025-06-08

### Added
- Erste Version des Cloudflare-Turnstile-Moduls für OXID eShop
- Captcha-Schutz für Kontaktformular, Passwort-vergessen, Newsletter-Anmeldung und Registrierung
- Admin-Konfiguration für Site Key, Secret Key, Theme und Größe
- Einzeln aktivierbare Turnstile-Prüfung je Formular

[Unreleased]: https://github.com/tabsl/tabslTurnstile/compare/1.1.0...HEAD
[1.1.0]: https://github.com/tabsl/tabslTurnstile/compare/1.0.6...1.1.0
[1.0.6]: https://github.com/tabsl/tabslTurnstile/compare/1.0.5...1.0.6
[1.0.5]: https://github.com/tabsl/tabslTurnstile/compare/1.0.4...1.0.5
[1.0.4]: https://github.com/tabsl/tabslTurnstile/compare/1.0.3...1.0.4
[1.0.3]: https://github.com/tabsl/tabslTurnstile/compare/1.0.2...1.0.3
[1.0.2]: https://github.com/tabsl/tabslTurnstile/compare/1.0.1...1.0.2
[1.0.1]: https://github.com/tabsl/tabslTurnstile/compare/1.0.0...1.0.1
