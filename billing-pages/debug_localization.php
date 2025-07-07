<?php
require_once __DIR__ . '/vendor/autoload.php';

use BillingPages\Core\Localization;

// Start session
session_start();

echo "<h1>Localization Debug</h1>";

// Test German localization
echo "<h2>Testing German Localization</h2>";
$_SESSION['locale'] = 'de';
$deLocalization = new Localization();
echo "Current locale: " . $deLocalization->getLocale() . "<br>";
echo "App name: " . $deLocalization->get('app_name') . "<br>";
echo "Dashboard: " . $deLocalization->get('nav_dashboard') . "<br>";
echo "Logout: " . $deLocalization->get('logout') . "<br>";

// Test English localization
echo "<h2>Testing English Localization</h2>";
$_SESSION['locale'] = 'en';
$enLocalization = new Localization();
echo "Current locale: " . $enLocalization->getLocale() . "<br>";
echo "App name: " . $enLocalization->get('app_name') . "<br>";
echo "Dashboard: " . $enLocalization->get('nav_dashboard') . "<br>";
echo "Logout: " . $enLocalization->get('logout') . "<br>";

// Test missing key
echo "<h2>Testing Missing Key</h2>";
echo "Missing key test: " . $enLocalization->get('this_key_does_not_exist') . "<br>";

// Show all translations for English
echo "<h2>All English Translations (first 10)</h2>";
$allTranslations = $enLocalization->getAll();
$count = 0;
foreach ($allTranslations as $key => $value) {
    if ($count++ < 10) {
        echo "$key => $value<br>";
    } else {
        break;
    }
}

echo "<h2>Total English translations: " . count($allTranslations) . "</h2>";

// Test language detection
echo "<h2>Language Detection Test</h2>";
echo "Supported locales: " . implode(', ', $enLocalization->getSupportedLocales()) . "<br>";
echo "Default locale from config: " . (defined('DEFAULT_LOCALE') ? DEFAULT_LOCALE : 'not defined') . "<br>";
?> 