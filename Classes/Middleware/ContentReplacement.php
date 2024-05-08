<?php

namespace Igelb\IgContentBlocking\Middleware;

use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Core\Http\Response;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ContentReplacement implements MiddlewareInterface
{
    private $responseFactory;
    private $requestFactory;

    public function __construct(
        ResponseFactoryInterface $responseFactory,
        RequestFactoryInterface $requestFactory
    ) {
        $this->responseFactory = $responseFactory;
        $this->requestFactory = $requestFactory;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $controller = $GLOBALS['TSFE'];
        $response = new Response();
        $controller->content = $handler->handle($request)->getBody()->__toString();
        $response = $controller->applyHttpHeadersToResponse($response);

        // Führe deinen eigenen Hook aus
        $_params = ['pObj' => &$controller];
        foreach ($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['contentReplacementHook'] ?? [] as $_funcRef) {
            GeneralUtility::callUserFunction($_funcRef, $_params, $controller);
        }

        $response->getBody()->write($controller->content);
        return $response;
    }
}
