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
            'typo3' => '11.5.8-11.5.99',
            'php' => '7.4.0-7.4.99',
        ],
    ],
    'autoload' => [
        'psr-4' => [
            'Igelb\\IgContentBlocking\\' => 'Classes',
        ],
    ],
];
