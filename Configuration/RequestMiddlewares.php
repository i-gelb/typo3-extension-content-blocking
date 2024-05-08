<?php

return [
    'frontend' => [
        'igelb/ig-content-blocking/content-replacement' => [
            'target' => \Igelb\IgContentBlocking\Middleware\ContentReplacement::class,
            'after' => [
                'typo3/cms-frontend/output-compression',
            ],
        ],
    ],
];
