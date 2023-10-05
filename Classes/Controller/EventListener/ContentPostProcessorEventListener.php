<?php

declare(strict_types=1);

namespace Igelb\IgContentBlocking\EventListener;

use TYPO3\CMS\Core\Attribute\AsEventListener;
use TYPO3\CMS\Frontend\Event\AfterCacheableContentIsGeneratedEvent;

#[AsEventListener(
    identifier: 'ig-content-blocking.content-modifier'
)]
final class ContentPostProcessorEventListener
{
    public function __invoke(AfterCacheableContentIsGeneratedEvent $event): void
    {
        // Only do this when caching is enabled
        if (!$event->isCachingEnabled()) {
            return;
        }
        $event->getController()->content = str_replace(
            'iframe',
            'noframe',
            $event->getController()->content
        );
    }
}
