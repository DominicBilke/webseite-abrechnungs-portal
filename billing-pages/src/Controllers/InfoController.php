<?php

namespace BillingPages\Controllers;

use BillingPages\Controllers\BaseController;

/**
 * Info Controller for external links
 */
class InfoController extends BaseController
{
    /**
     * Show help page
     */
    public function help(): void
    {
        $this->render('help/index', [
            'page_title' => $this->localization->get('help'),
            'scripts' => []
        ]);
    }

    /**
     * Redirect to contact page
     */
    public function contact(): void
    {
        // Redirect to external contact page
        header('Location: https://www.dominic-bilke.de/en/contact');
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