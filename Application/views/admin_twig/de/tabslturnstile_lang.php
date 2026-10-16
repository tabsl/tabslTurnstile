<?php

$sLangName = 'Deutsch';

$aLang = [
    'charset' => 'UTF-8',

    // Modulkonfiguration - Gruppen
    'SHOP_MODULE_GROUP_tabslturnstile_main' => 'Grundeinstellungen',
    'SHOP_MODULE_GROUP_tabslturnstile_features' => 'Features',

    // Grundeinstellungen
    'SHOP_MODULE_tabslturnstile_site_key' => 'Site Key',
    'HELP_SHOP_MODULE_tabslturnstile_site_key' => 'Öffentlicher Schlüssel von Cloudflare Turnstile (erhältlich in der Cloudflare Dashboard)',

    'SHOP_MODULE_tabslturnstile_secret_key' => 'Secret Key',
    'HELP_SHOP_MODULE_tabslturnstile_secret_key' => 'Privater Schlüssel von Cloudflare Turnstile (geheim halten!)',

    'SHOP_MODULE_tabslturnstile_theme' => 'Theme',
    'HELP_SHOP_MODULE_tabslturnstile_theme' => 'Farbschema des Turnstile-Widgets',
    'SHOP_MODULE_tabslturnstile_theme_auto' => 'Automatisch',
    'SHOP_MODULE_tabslturnstile_theme_light' => 'Hell',
    'SHOP_MODULE_tabslturnstile_theme_dark' => 'Dunkel',

    'SHOP_MODULE_tabslturnstile_size' => 'Größe',
    'HELP_SHOP_MODULE_tabslturnstile_size' => 'Größe des Turnstile-Widgets',
    'SHOP_MODULE_tabslturnstile_size_normal' => 'Normal',
    'SHOP_MODULE_tabslturnstile_size_compact' => 'Kompakt',

    // Features
    'SHOP_MODULE_tabslturnstile_enable_contact' => 'Kontaktformular',
    'HELP_SHOP_MODULE_tabslturnstile_enable_contact' => 'Turnstile im Kontaktformular aktivieren',

    'SHOP_MODULE_tabslturnstile_enable_register' => 'Registrierung',
    'HELP_SHOP_MODULE_tabslturnstile_enable_register' => 'Turnstile bei der Benutzerregistrierung, der Account-Anlage im Checkout und beim Gastkonto aktivieren. Master-Schalter: aus bleibt aus, auch bei einer Welle.',

    'SHOP_MODULE_tabslturnstile_enable_register_adaptive' => 'Registrierung nur bei Welle (adaptiv)',
    'HELP_SHOP_MODULE_tabslturnstile_enable_register_adaptive' => 'Wenn aktiv, erscheint Turnstile an Registrierung, Checkout-Account und Gastkonto nur bei einer Welle (viele verschiedene E-Mails im Zeitfenster). Im Alltag kein Widget. Default: aus.',

    'SHOP_MODULE_tabslturnstile_register_window' => 'Wellen-Zeitfenster (Minuten)',
    'HELP_SHOP_MODULE_tabslturnstile_register_window' => 'Gleitendes Fenster in Minuten (mindestens 1, Default 5). Innerhalb dieses Zeitraums werden verschiedene E-Mail-Adressen gezählt. Die Mindest-An-Dauer des Schutzes entspricht diesem Wert.',

    'SHOP_MODULE_tabslturnstile_register_threshold' => 'Wellen-Schwelle (E-Mails)',
    'HELP_SHOP_MODULE_tabslturnstile_register_threshold' => 'Anzahl verschiedener E-Mail-Adressen im Zeitfenster, ab der der Schutz anspringt (mindestens 1, Default 10). Dieselbe Adresse mehrfach zählt als 1. Der auslösende Versuch läuft noch ohne Widget durch.',

    'SHOP_MODULE_tabslturnstile_register_domains' => 'Domainliste (optional)',
    'HELP_SHOP_MODULE_tabslturnstile_register_domains' => 'Leer: alle Domains werden geprüft. Gefüllt: nur diese Domains zählen für die Welle (z. B. gmail.com, yahoo.com). Eine Domain pro Eintrag, ohne @, Groß/Kleinschreibung egal. googlemail.com extra eintragen. Die Liste filtert nur den Zähler — ist der Schutz an, sehen alle das Widget.',

    'SHOP_MODULE_tabslturnstile_enable_newsletter' => 'Newsletter',
    'HELP_SHOP_MODULE_tabslturnstile_enable_newsletter' => 'Turnstile bei der Newsletter-Anmeldung aktivieren',

    'SHOP_MODULE_tabslturnstile_enable_forgotpassword' => 'Passwort vergessen',
    'HELP_SHOP_MODULE_tabslturnstile_enable_forgotpassword' => 'Turnstile beim Passwort-vergessen-Formular aktivieren',
];
