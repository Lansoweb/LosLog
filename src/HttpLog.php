<?php

namespace LosMiddleware\LosLog;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Zend\Diactoros\Request\Serializer as RequestSerializer;
use Zend\Diactoros\Response\Serializer as ResponseSerializer;
use Zend\Stratigility\MiddlewareInterface;

class HttpLog implements MiddlewareInterface
{
    private $logger;
    private $options;

    public function __construct(LoggerInterface $logger, $options = [])
    {
        $this->logger = $logger;
        $this->options = array_merge([
            'level' => LogLevel::INFO,
            'log_request' => true,
            'log_response' => true,
        ], $options);
    }

    public function __invoke(Request $request, Response $response, callable $next = null)
    {
        if ($next !== null) {
            $response = $next($request, $response);
        }

        if ($this->options['log_request']) {
            $requestMessage = RequestSerializer::toString($request);
            $this->logger->log($this->options['level'], sprintf("Request: %s", $requestMessage));
        }

        if ($this->options['log_response']) {
            $responseMessage = ResponseSerializer::toString($response);
            $this->logger->log($this->options['level'], sprintf("Response: %s", $responseMessage));
        }

        return $response;
    }
}
