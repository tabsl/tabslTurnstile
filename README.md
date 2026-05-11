# tabslTurnstile

OXID eShop Modul zur Integration von [Cloudflare Turnstile](https://www.cloudflare.com/products/turnstile/) — der datenschutzfreundlichen, kostenlosen Alternative zu Google reCAPTCHA.

## Warum dieses Modul nutzen?

Bot-Traffic auf Shop-Formularen führt zu:

- ❌ Spam-Einträgen über Kontaktformulare
- ❌ Fake-Newsletter-Anmeldungen
- ❌ Missbrauch der Passwort-Vergessen-Funktion
- ❌ Automatisierten Fake-Registrierungen
- ❌ Schlechter User Experience durch klassische CAPTCHAs (Bilderrätsel, verzerrter Text)

**tabslTurnstile** löst diese Probleme durch:

- ✅ Unsichtbare bzw. nicht-interaktive Bot-Erkennung von Cloudflare
- ✅ Keine nervigen Bilderrätsel für echte Kunden
- ✅ DSGVO-freundlich — kein Tracking, keine personenbezogenen Daten
- ✅ Kostenlos und unbegrenzt nutzbar (auch im kommerziellen Einsatz)
- ✅ Per Klick aktivierbar pro Formular

## Hauptfunktionen

### Bot-Schutz für die wichtigsten Shop-Formulare

- **Kontaktformular** — Schutz vor Spam-Anfragen
- **Registrierung** — Verhindert automatisierte Fake-Accounts
- **Newsletter-Anmeldung** — Keine Bot-Eintragungen mehr in den Verteiler
- **Passwort vergessen** — Schutz vor Missbrauch des E-Mail-Versands

Jedes Formular kann einzeln aktiviert oder deaktiviert werden.

### Konfigurierbares Erscheinungsbild

- **Theme**: `auto`, `light` oder `dark` — passt sich an das Shop-Design an
- **Größe**: `normal` oder `compact` für unterschiedliche Layouts

### Sichere Server-Side-Validierung

Die Turnstile-Prüfung erfolgt **vor** der eigentlichen Aktion (Kontaktversand, Registrierung, Newsletter-Anmeldung, Passwort-Reset). Schlägt die Validierung fehl, wird die Aktion abgebrochen und eine Fehlermeldung angezeigt.

## Schnellstart

### Installation

```bash
composer require tabsl/tabslturnstile
```

Anschließend das Modul im OXID Admin unter **Erweiterungen → Module** aktivieren.

### Cloudflare Turnstile einrichten

Turnstile ist ein **kostenloser** Dienst von Cloudflare. Es ist **kein** Cloudflare-Account für DNS/Proxy notwendig — ein einfacher Cloudflare-Account reicht aus.

1. **Cloudflare-Account erstellen** (falls noch nicht vorhanden): <https://dash.cloudflare.com/sign-up>
2. **Turnstile Dashboard öffnen**: <https://dash.cloudflare.com/?to=/:account/turnstile>
3. Auf **„Add Site"** (bzw. „Widget hinzufügen") klicken
4. Folgende Daten eintragen:
   - **Site name**: Frei wählbarer Name (z.B. „Mein Shop")
   - **Domain**: Domain des Shops eintragen (z.B. `meinshop.de`). Bei Bedarf weitere Domains hinzufügen (z.B. Staging-Umgebungen).
   - **Widget Mode**: Empfehlung **„Managed"** — Cloudflare entscheidet automatisch, ob eine Interaktion notwendig ist. Alternativen: „Non-interactive" (immer ohne Klick) oder „Invisible" (komplett unsichtbar).
5. Auf **„Create"** klicken
6. Cloudflare zeigt nun zwei Schlüssel an:
   - **Site Key** (öffentlich, wird im Frontend eingebunden)
   - **Secret Key** (geheim, ausschließlich für die Server-Validierung)
7. Beide Schlüssel kopieren

### Modul konfigurieren

Im OXID Admin unter **Erweiterungen → Module → tabslTurnstile → Einstellungen**:

| Einstellung                            | Beschreibung                                       |
| -------------------------------------- | -------------------------------------------------- |
| `tabslturnstile_site_key`              | Site Key aus dem Cloudflare Dashboard              |
| `tabslturnstile_secret_key`            | Secret Key aus dem Cloudflare Dashboard            |
| `tabslturnstile_theme`                 | `auto`, `light` oder `dark`                        |
| `tabslturnstile_size`                  | `normal` oder `compact`                            |
| `tabslturnstile_enable_contact`        | Turnstile im Kontaktformular aktivieren            |
| `tabslturnstile_enable_register`       | Turnstile bei der Registrierung aktivieren         |
| `tabslturnstile_enable_newsletter`     | Turnstile bei der Newsletter-Anmeldung aktivieren  |
| `tabslturnstile_enable_forgotpassword` | Turnstile bei Passwort vergessen aktivieren        |

Nach dem Speichern ist Turnstile in allen aktivierten Formularen aktiv.

### Test-Schlüssel (für Entwicklung)

Cloudflare stellt fixe Test-Keys bereit, die immer ein bestimmtes Ergebnis liefern — praktisch für lokale Entwicklung und Staging:

- **Always passes**: Site Key `1x00000000000000000000AA`, Secret `1x0000000000000000000000000000000AA`
- **Always blocks**: Site Key `2x00000000000000000000AB`, Secret `2x0000000000000000000000000000000AA`
- **Always challenges**: Site Key `3x00000000000000000000FF`

Vollständige Liste: <https://developers.cloudflare.com/turnstile/troubleshooting/testing/>

## Kosten

Cloudflare Turnstile ist **dauerhaft kostenlos** — unbegrenzte Anfragen, auch im kommerziellen Einsatz. Es ist kein Cloudflare-Pro-Tarif notwendig.

## Datenschutz / DSGVO

Cloudflare Turnstile wurde speziell als datenschutzfreundliche CAPTCHA-Alternative entwickelt:

- Keine Cookies für die reine Bot-Erkennung
- Keine Speicherung personenbezogener Daten
- Kein Tracking über mehrere Seiten hinweg
- Privacy-Statement: <https://www.cloudflare.com/privacypolicy/>

Trotzdem sollte die Nutzung in der Datenschutzerklärung des Shops erwähnt werden (Auftragsverarbeitung durch Cloudflare).

## Kompatibilität

- OXID eShop 6.x
- PHP 7.x und PHP 8.x
- Standard-Theme sowie Shop-Themes mit Standard-Templates für Kontakt, Registrierung, Newsletter und Passwort vergessen

## License

Dieses Modul steht unter der **GNU General Public License v3.0 (oder neuer)**.
Den vollständigen Lizenztext findest du in der Datei [LICENSE](LICENSE) bzw. unter <https://www.gnu.org/licenses/gpl-3.0.html>.

## Copyright

Tobias Merkl | https://oxid-module.eu
