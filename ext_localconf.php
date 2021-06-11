<?php

defined('TYPO3_MODE') || die('Access denied.');

use \Igelb\IgContentBlocking\Hooks\ContentPostProcessorHook;

// This hook replaces all iframes with a note
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['contentPostProc-all'][] = ContentPostProcessorHook::class.'->removeExternalContent';
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['contentPostProc-output'][] = ContentPostProcessorHook::class.'->removeExternalContent';
