<?php

namespace Igelb\IgContentBlocking\Hooks;

class ContentPostProcessorHook
{
    public function removeExternalContent(&$parameters)
    {
        $html = &$parameters['pObj']->content;
        $dom  = $this->_loadHtml($html);

        $scriptTags = $dom->getElementsByTagName('script');
        $iframeTags = $dom->getElementsByTagName('iframe');

        if (count($scriptTags) || count($iframeTags)) {
            $scriptIterator = $scriptTags->length - 1;
            while ($scriptIterator > -1) {
                $tag = $scriptTags->item($scriptIterator);

                if ($tag->getAttribute('data-block-script') === 'true') {
                    $tag = $scriptTags->item($scriptIterator);
                    $this->_modifyContent($tag, $dom, 'script');
                }

                $scriptIterator--;
            }

            $iframeIterator = $iframeTags->length - 1;
            while ($iframeIterator > -1) {
                $tag = $iframeTags->item($iframeIterator);

                if ($tag->getAttribute('data-ignore') !== 'true') {
                    $this->_modifyContent($tag, $dom, 'iframe');
                }

                $iframeIterator--;
            }

            $html = $dom->saveHTML();
        }
    }
    
    private function _modifyContent($tag, $dom, string $tagType)
    {
        $src = $tag->getAttribute('src');
        $host = parse_url($src)['host'];
        $host = str_replace('www.', '', $host);

        // Create replacement element
        $div = $dom->createElement('div');
        $div->setAttribute('data-attribute-src', $src);
        $div->setAttribute('class', 'cc-blocked');

        $container = $dom->createElement('div');
        $container->setAttribute('class', 'cc-blocked-container');

        $headline = $dom->createElement('p');
        $headline->setAttribute('class', 'cc-blocked-headline');
        $headline->nodeValue = 'Externer Inhalt';

        $text1 = $dom->createElement('p');
        $text1->setAttribute('class', 'cc-blocked-text');
        $text1->nodeValue = 'Dieser Inhalt von';

        $domain = $dom->createElement('p');
        $domain->setAttribute('class', 'cc-blocked-host');
        $domain->nodeValue = $host;

        $text2 = $dom->createElement('p');
        $text2->setAttribute('class', 'cc-blocked-text');
        $text2->nodeValue = 'wird aus Datenschutzgründen erst nach expliziter Zustimmung angezeigt.';

        $button = $dom->createElement('button');
        $button->nodeValue = 'Inhalt anzeigen';

        $container->appendChild($headline);
        $container->appendChild($text1);
        $container->appendChild($domain);
        $container->appendChild($text2);
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
        $div->setAttribute('data-node-name', $tagType);

        $tag->parentNode->replaceChild($div, $tag);

        return $tag;
    }
    
    private function _loadHtml(string $html)
    {
        $doc = new \DOMDocument();

        // Avoid errors when loading html5 tags
        libxml_use_internal_errors(true);

        $doc->loadHTML(
            mb_convert_encoding(
                $html,
                'HTML-ENTITIES',
                'UTF-8'
            ),
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );

        libxml_clear_errors();

        return $doc;
    }
}
