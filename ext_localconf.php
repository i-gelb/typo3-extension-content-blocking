<?php

defined('TYPO3_MODE') || die('Access denied.');

use Igelb\IgContentBlocking\Hooks\ContentPostProcessorHook;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
use Igelb\IgContentBlocking\Controller\ConsentController;

// This hook replaces all iframes with a note
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['contentPostProc-all'][] = ContentPostProcessorHook::class.'->removeExternalContent';
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['contentReplacementHook'][] = ContentPostProcessorHook::class . '->removeExternalContent';

ExtensionUtility::configurePlugin(
    'Igelb.IgContentBlocking',
    'ManageConsent',
    [
        ConsentController::class => 'manage',
    ],
    // Non cachable actions
    [
        ConsentController::class => 'manage',
    ]
);
