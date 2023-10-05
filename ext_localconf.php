<?php

defined('TYPO3_MODE') || die('Access denied.');

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use Igelb\IgContentBlocking\Hooks\ContentPostProcessorHook;
use Igelb\IgContentBlocking\Controller\ConsentController;

call_user_func(function () {

    ExtensionManagementUtility::configurePlugin(
        'IgContentBlocking',
        'ManageConsent',
        [ConsentController::class => 'manage'],
        [ConsentController::class => 'manage']
    );

    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['contentPostProc-all'][] = ContentPostProcessorHook::class.'->removeExternalContent';
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['contentPostProc-output'][] = ContentPostProcessorHook::class.'->removeExternalContent';
});
