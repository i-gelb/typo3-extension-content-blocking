<?php

use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

call_user_func(function () {

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
        'ig_content_blocking',
        'Configuration/TypoScript',
        'i-gelb content blocking'
    );
});
