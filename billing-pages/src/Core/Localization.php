<?php

namespace BillingPages\Core;

/**
 * Localization and Translation Handler
 */
class Localization
{
    private string $locale;
    private array $translations = [];
    private array $supportedLocales = ['de', 'en'];

    public function __construct()
    {
        $this->locale = $this->detectLocale();
        $this->loadTranslations();
    }

    /**
     * Detect user's preferred locale
     */
    private function detectLocale(): string
    {
        // Check if locale is set in session
        if (isset($_SESSION['locale']) && in_array($_SESSION['locale'], $this->supportedLocales)) {
            return $_SESSION['locale'];
        }

        // Check if locale is set in URL parameter
        if (isset($_GET['lang']) && in_array($_GET['lang'], $this->supportedLocales)) {
            $_SESSION['locale'] = $_GET['lang'];
            return $_GET['lang'];
        }

        // Check browser language
        if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $browserLang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
            if (in_array($browserLang, $this->supportedLocales)) {
                return $browserLang;
            }
        }

        // Default to German
        return DEFAULT_LOCALE;
    }

    /**
     * Load translation files
     */
    private function loadTranslations(): void
    {
        $translationFile = __DIR__ . "/../../locales/{$this->locale}.php";
        if (file_exists($translationFile)) {
            $this->translations = include $translationFile;
        }
    }

    /**
     * Get translation for a key
     */
    public function get(string $key, array $params = []): string
    {
        $translation = $this->translations[$key] ?? $key;

        // Replace parameters in translation
        foreach ($params as $param => $value) {
            $translation = str_replace(":{$param}", $value, $translation);
        }

        return $translation;
    }

    /**
     * Get current locale
     */
    public function getLocale(): string
    {
        return $this->locale;
    }

    /**
     * Set locale
     */
    public function setLocale(string $locale): void
    {
        if (in_array($locale, $this->supportedLocales)) {
            $this->locale = $locale;
            $_SESSION['locale'] = $locale;
            $this->loadTranslations();
        }
    }

    /**
     * Get supported locales
     */
    public function getSupportedLocales(): array
    {
        return $this->supportedLocales;
    }

    /**
     * Get locale name for display
     */
    public function getLocaleName(string $locale): string
    {
        $names = [
            'de' => 'Deutsch',
            'en' => 'English'
        ];
        return $names[$locale] ?? $locale;
    }

    /**
     * Check if translation exists
     */
    public function has(string $key): bool
    {
        return isset($this->translations[$key]);
    }

    /**
     * Get all translations for current locale
     */
    public function getAll(): array
    {
        return $this->translations;
    }
} 