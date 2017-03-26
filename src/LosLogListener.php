<?php

namespace LosMiddleware\LosLog;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

class LosLogListener
{
    const LOG_FORMAT = '%d [%s] %s: %s. File: %s:%s';
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function __invoke($error, Request $request, Response $response)
    {
        $this->logger->error(sprintf(
            self::LOG_FORMAT,
            $response->getStatusCode(),
            $request->getMethod(),
            (string) $request->getUri(),
            $error->getMessage(),
            $error->getFile(),
            $error->getLine()
        ));
    }
}
