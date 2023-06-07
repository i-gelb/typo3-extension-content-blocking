<?php

namespace Igelb\IgContentBlocking\Hooks;

use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

class ContentPostProcessorHook
{
    /**
     * "Controller" of the hook
     *
     * @param array $parameters
     *
     * @return void
     */
    public function removeExternalContent(array &$parameters)
    {
        $html = &$parameters['pObj']->content;
        $document = $this->_loadHtml($html);

        $scriptTags = $document->getElementsByTagName('script');
        $iframeTags = $document->getElementsByTagName('iframe');

        if (count($scriptTags) || count($iframeTags)) {
            // Iterate thourgh `<script>` tags
            $scriptIterator = $scriptTags->length - 1;
            while ($scriptIterator > -1) {
                // Get elment by index
                $tag = $scriptTags->item($scriptIterator);

                if ($tag->getAttribute('data-no-consent-required') === 'true') {
                    $tag = $scriptTags->item($scriptIterator);
                    $this->_modifyContent($tag, $document, 'script');
                }

                $scriptIterator--;
            }

            // Iterate thourgh `<iframe>` tags
            $iframeIterator = $iframeTags->length - 1;
            while ($iframeIterator > -1) {
                // Get elment by index
                $tag = $iframeTags->item($iframeIterator);

                if ($tag->getAttribute('data-no-consent-required') !== 'true') {
                    $this->_modifyContent($tag, $document, 'iframe');
                }

                $iframeIterator--;
            }

            $html = $document->saveHTML();
        }
    }

    /**
     * Replaces the original element with the consent banner
     *
     * @param \DOMElement $tag The tag
     * @param \DOMDocument $dom The Document
     * @param string $tagName The name/type of the tag
     *
     * @return \DOMElement
     */
    private function _modifyContent(
        \DOMElement $tag,
        \DOMDocument $document,
        string $tagName
    ): \DOMElement {
        $src = $tag->getAttribute('src');
        $host = parse_url($src)['host'];
        $host = str_replace('www.', '', $host);

        // Create replacement element
        $div = $document->createElement('div');
        $div->setAttribute('data-attribute-src', $src);
        $div->setAttribute('data-hostname', $host);
        $div->setAttribute('class', 'cc-blocked');

        $container = $document->createElement('div');
        $container->setAttribute('class', 'cc-blocked-container');

        $headline = $document->createElement('p');
        $headline->setAttribute('class', 'cc-blocked-headline');
        $headline->nodeValue = LocalizationUtility::translate(
            key: 'header',
            extensionName: 'ig_content_blocking'
        );

        $text = $document->createElement('p');
        $text->setAttribute('class', 'cc-blocked-text');
        $text->nodeValue = LocalizationUtility::translate(
            key: 'host_text',
            extensionName: 'ig_content_blocking',
            arguments: [$host]
        );

        $helpText = $document->createElement('p');
        $helpText->setAttribute('class', 'cc-blocked-text');
        $helpText->nodeValue = LocalizationUtility::translate(
            key: 'help_text',
            extensionName: 'ig_content_blocking'
        );

        $additionalText = $document->createElement('p');
        $additionalText->setAttribute('class', 'cc-blocked-text');
        $additionalText->nodeValue = LocalizationUtility::translate(
            key: 'additional_text',
            extensionName: 'ig_content_blocking'
        );

        $button = $document->createElement('button');
        $button->nodeValue = LocalizationUtility::translate(
            key: 'button_text',
            extensionName: 'ig_content_blocking'
        );

        $container->appendChild($headline);
        $container->appendChild($text);
        $container->appendChild($helpText);
        $container->appendChild($additionalText);
        $container->appendChild($button);
        $div->appendChild($container);

        // Set all attributes of the iframes for the replacement element
        foreach ($tag->attributes as $attribute) {
            if ('src' !== $attribute->nodeName) {
                $div->setAttribute(
                    "data-attribute-$attribute->nodeName",
                    $attribute->nodeValue
                );
            }
        }

        // Is used to detect which tag should be created in the frontend after consent
        $div->setAttribute('data-node-name', $tagName);

        $tag->parentNode->replaceChild($div, $tag);

        return $tag;
    }

    /**
     * Loads an HTML string into a DOMDocument object
     *
     * @param string $html
     *
     * @return \DOMDocument
     */
    private function _loadHtml(string $html)
    {
        $document = new \DOMDocument();
        libxml_use_internal_errors(true);

        $document->loadHTML(
            mb_convert_encoding(
                $html,
                'HTML-ENTITIES',
                'UTF-8'
            ),
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );

        // Basically, we just ignore any errors while loading the HTML,
        // since it's none of our business if it's valid.
        // This is also necessary because HTML5 tags would also throw an error every time.
        libxml_clear_errors();

        return $document;
    }
}
