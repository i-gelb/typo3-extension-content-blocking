<?php

defined('TYPO3') or die('yo');

use Igelb\IgContentBlocking\Hooks\ContentPostProcessorHook;
use Igelb\IgContentBlocking\Controller\ConsentController;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

call_user_func(function () {
    ExtensionUtility::configurePlugin(
        'IgContentBlocking',
        'ManageConsent',
        [ConsentController::class => 'manage'],
        [ConsentController::class => 'manage']
    );
});
