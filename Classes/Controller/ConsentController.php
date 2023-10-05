<?php

declare(strict_types=1);

namespace Igelb\IgContentBlocking\Controller;

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/*
 *
 * This file is part of the "i-gelb Content Blocking" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2022 Lasse Blomenkemper <blomenkemper@i-gelb.net>, i-gelb GmbH
 *
 */

class ConsentController extends ActionController
{
    // The name of the cookie in which the allowed domains are stored
    private const _COOKIE_NAME = 'allowed_domains';

    /**
     * Assignes all allowed domains to the view for consent management
     *
     * @return void
     */
    public function manageAction(): void
    {
        $domainCookieValue = '';
        if (isset($_COOKIE[self::_COOKIE_NAME])) {
            $domainCookieValue = $_COOKIE[self::_COOKIE_NAME];
        }

        $this->view->assign(
            'domains',
            self::parseDomainsFromJson($domainCookieValue)
        );
    }

    /**
     * Parses json into a php array of domains
     *
     * @param mixed $json
     *
     * @return array
     */
    private static function parseDomainsFromJson($inputJson): array
    {
        $inputJson = (string) $inputJson;
        $inputDomains = json_decode($inputJson);
        $validatedDomains = [];

        // Since the cookie value was sent by the user, we cannot trust
        // that it has not been tampered with. Therefore we have to check
        // if it is valid json at all.
        if (json_last_error() === JSON_ERROR_NONE) {
            foreach ($inputDomains as $domain) {
                // Same reason as above. Also checking if the domain is valid.
                if (self::_isValidDomainName($domain)) {
                    $validatedDomains[] = $domain;
                }
            }
        }

        return $validatedDomains;
    }

    /**
     * Validates a domain name
     *
     * @param $domain
     *
     * @return bool
     */
    private static function _isValidDomainName($domain): bool
    {
        // Regex from https://gist.github.com/egulhan/4b2495499cc229b8e6426621993d11b5#file-validate-domain-name-php
        $pattern = '/^(http[s]?\:\/\/)?(?!\-)(?:[a-zA-Z\d\-]{0,62}[a-zA-Z\d]\.){1,126}(?!\d+)[a-zA-Z\d]{1,63}$/';

        return (preg_match($pattern, $domain) === 1);
    }
}
