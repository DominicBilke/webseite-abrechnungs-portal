<?php

namespace BillingPages\Controllers;

use BillingPages\Controllers\BaseController;

/**
 * Info Controller for external links
 */
class InfoController extends BaseController
{
    /**
     * Redirect to help page (README.md)
     */
    public function help(): void
    {
        // Redirect to README.md in the project root
        header('Location: /README.md');
        exit;
    }

    /**
     * Redirect to contact page
     */
    public function contact(): void
    {
        // Redirect to external contact page
        header('Location: https://www.dominic-bilke.de/en/imprint');
        exit;
    }

    /**
     * Redirect to privacy policy page
     */
    public function privacy(): void
    {
        // Redirect to external privacy policy page
        header('Location: https://www.dominic-bilke.de/en/privacy-policy');
        exit;
    }

    /**
     * Redirect to imprint page
     */
    public function imprint(): void
    {
        // Redirect to external imprint page
        header('Location: https://www.dominic-bilke.de/en/imprint');
        exit;
    }
} 