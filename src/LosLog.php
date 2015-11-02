<?php

namespace LosMiddleware\LosLog;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Zend\Stratigility\ErrorMiddlewareInterface;
use Psr\Log\LoggerInterface;

class LosLog implements ErrorMiddlewareInterface
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function __invoke($error, Request $request, Response $response, callable $next = null)
    {
        if ($error instanceof \Exception) {
            $this->logger->error($error->getMessage());
        }

        if ($next !== null) {
            return $next($request, $response);
        }

        return $response;
    }
}
