<?php

defined('TYPO3_MODE') or exit();

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    'ig_content_blocking',
    'Configuration/TypoScript',
    'i-gelb content blocking'
);