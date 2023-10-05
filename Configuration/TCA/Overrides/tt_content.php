<?php

use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

call_user_func(function () {

    ExtensionUtility::registerPlugin(
        'IgContentBlocking',
        'ManageConsent',
        'External content consent management',
        'EXT:ig_consent_blocking/ext_icon.png'
    );
});
