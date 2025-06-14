<?php
/**
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @copyright (c) Tobias Merkl | 2025
 * @link https://oxid-module.eu
 * @link https://github.com/tabsl/tabslTurnstile
 * @package tabslTurnstile
 **/

/**
 * Metadata version
 */
$sMetadataVersion = '2.1';

// tabsl module id
$psModuleId = 'tabslTurnstile';
$psModuleName = '<b>tabsl</b>Turnstile';
$psModuleVersion = '1.0.1';

// tabsl module description
$psModuleDesc = 'Integration von Cloudflare Turnstile Captcha.';

/**
 * Module information
 */
$aModule = [
    'id' => $psModuleId,
    'title' => [
        'de' => $psModuleName,
        'en' => $psModuleName,
    ],
    'description' => [
        'de' => $psModuleDesc,
        'en' => $psModuleDesc
    ],
    'thumbnail' => '',
    'version' => $psModuleVersion,
    'author' => 'Tobias Merkl',
    'url' => 'https://github.com/tabsl',
    'email' => '',
    'extend' => [
        \OxidEsales\Eshop\Application\Controller\ContactController::class => \Tabsl\Turnstile\Controller\ContactController::class,
        \OxidEsales\Eshop\Application\Component\UserComponent::class => \Tabsl\Turnstile\Component\UserComponent::class,
        \OxidEsales\Eshop\Application\Controller\NewsletterController::class => \Tabsl\Turnstile\Controller\NewsletterController::class,
        \OxidEsales\Eshop\Application\Controller\ForgotPasswordController::class => \Tabsl\Turnstile\Controller\ForgotPasswordController::class,
        \OxidEsales\Eshop\Core\ViewConfig::class => \Tabsl\Turnstile\Core\ViewConfig::class,
    ],
    'controllers' => [],
    'blocks' => [
        ['template' => 'form/contact.tpl', 'block' => 'contact_form_fields', 'file' => 'views/blocks/turnstile_contact_form.tpl'],
        ['template' => 'form/fieldset/user_billing.tpl', 'block' => 'form_user_billing_country', 'file' => 'views/blocks/turnstile_register_form.tpl'],
        ['template' => 'form/newsletter.tpl', 'block' => 'captcha_form', 'file' => 'views/blocks/turnstile_newsletter_form.tpl'],
        ['template' => 'form/forgotpwd_email.tpl', 'block' => 'captcha_form', 'file' => 'views/blocks/turnstile_forgotpwd_form.tpl'],
    ],
    'settings' => [
        ['group' => 'tabslturnstile_main', 'name' => 'tabslturnstile_site_key', 'type' => 'str', 'value' => ''],
        ['group' => 'tabslturnstile_main', 'name' => 'tabslturnstile_secret_key', 'type' => 'password', 'value' => ''],
        ['group' => 'tabslturnstile_main', 'name' => 'tabslturnstile_theme', 'type' => 'select', 'value' => 'auto', 'constraints' => 'auto|light|dark'],
        ['group' => 'tabslturnstile_main', 'name' => 'tabslturnstile_size', 'type' => 'select', 'value' => 'normal', 'constraints' => 'normal|compact'],
        ['group' => 'tabslturnstile_features', 'name' => 'tabslturnstile_enable_contact', 'type' => 'bool', 'value' => true],
        ['group' => 'tabslturnstile_features', 'name' => 'tabslturnstile_enable_register', 'type' => 'bool', 'value' => true],
        ['group' => 'tabslturnstile_features', 'name' => 'tabslturnstile_enable_newsletter', 'type' => 'bool', 'value' => true],
        ['group' => 'tabslturnstile_features', 'name' => 'tabslturnstile_enable_forgotpassword', 'type' => 'bool', 'value' => true],
    ],
    'events' => [
        'onActivate' => '\Tabsl\Turnstile\Core\Setup::onActivate',
        'onDeactivate' => '\Tabsl\Turnstile\Core\Setup::onDeactivate',
    ],
    'templates' => [],
    'smartyPluginDirectories' => [],
    'lang' => [
        'de' => [
            'translations/de/tabslturnstile_lang.php',
            'Application/translations/de/tabslturnstile_lang.php',
        ],
        'en' => [
            'translations/en/tabslturnstile_lang.php',
            'Application/translations/en/tabslturnstile_lang.php',
        ],
    ],
];
