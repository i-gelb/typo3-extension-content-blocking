<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'GDPR Content Blocking',
    'description' => 'Automatically blocks iframes/scripts and asks the user for consent',
    'category' => 'fe',
    'author' => 'Lasse Blomenkemper',
    'author_company' => 'i-gelb GmbH',
    'author_email' => 'blomenkemper@i-gelb.net',
    'state' => 'stable',
    'clearCacheOnLoad' => true,
    'version' => '1.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '7.6.54-7.6.99',
            'php' => '7.2.0-7.3.99',
        ],
    ],
    'autoload' => [
        'psr-4' => [
            'Igelb\\IgContentBlocking\\' => 'Classes',
        ],
    ],
];
