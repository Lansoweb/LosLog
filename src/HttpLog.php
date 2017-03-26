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
            'full' => false,
        ], $options);
    }

    private function generateRequestLog(Request $request, Response $response)
    {
        if ($this->options['full']) {
            return RequestSerializer::toString($request);
        }

        $msg = sprintf(
            "%s %s",
            $request->getMethod(),
            $request->getRequestTarget()
        );

        if ($request->hasHeader('X-Request-Id')) {
            $msg .= ' RequestId: '. $request->getHeader('X-Request-Id')[0];
        } elseif ($response->hasHeader('X-Request-Id')) {
            $msg .= ' RequestId: '. $response->getHeader('X-Request-Id')[0];
        }

        return $msg;
    }

    private function generateResponseLog(Request $request, Response $response)
    {
        if ($this->options['full']) {
            return ResponseSerializer::toString($response);
        }

        $reasonPhrase = $response->getReasonPhrase();
        $msg = sprintf(
            "%s %s",
            $response->getStatusCode(),
            ($reasonPhrase ? $reasonPhrase : '')
        );

        if ($response->hasHeader('X-Request-Id')) {
            $msg .= ' RequestId: '. $response->getHeader('X-Request-Id')[0];
        } elseif ($request->hasHeader('X-Request-Id')) {
            $msg .= ' RequestId: '. $request->getHeader('X-Request-Id')[0];
        }
        if ($response->hasHeader('X-Response-Time')) {
            $msg .= ' ResponseTime: '. $response->getHeader('X-Response-Time')[0];
        }

        return $msg;
    }

    public function __invoke(Request $request, Response $response, callable $next = null)
    {

        if ($this->options['log_request']) {
            $requestMessage = $this->generateRequestLog($request, $response);
            $this->logger->log($this->options['level'], sprintf("Request: %s", $requestMessage));
        }

        $response = $next($request, $response);

        if ($this->options['log_response']) {
            $responseMessage = $this->generateResponseLog($request, $response);
            $this->logger->log($this->options['level'], sprintf("Response: %s", $responseMessage));
        }

        return $response;
    }
}
