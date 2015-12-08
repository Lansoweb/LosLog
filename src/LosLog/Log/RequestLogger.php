<?php

/**
 * Request logger.
 *
 * @author    Leandro Silva <leandro@leandrosilva.info>
 *
 * @link      http://leandrosilva.info Development Blog
 * @link      http://github.com/LansoWeb/LosLog for the canonical source repository
 *
 * @copyright Copyright (c) 2011-2013 Leandro Silva (http://leandrosilva.info)
 * @license   http://leandrosilva.info/licenca-bsd New BSD license
 */
namespace LosLog\Log;

/**
 * Request logger.
 *
 * @author    Leandro Silva <leandro@leandrosilva.info>
 *
 * @link      http://leandrosilva.info Development Blog
 * @link      http://github.com/LansoWeb/LosLog for the canonical source repository
 *
 * @copyright Copyright (c) 2011-2013 Leandro Silva (http://leandrosilva.info)
 * @license   http://leandrosilva.info/licenca-bsd New BSD license
 */
class RequestLogger extends AbstractLogger
{
    /**
     * Logger instance.
     *
     * @var \LosLog\Log\RequestLogger
     */
    protected static $instance;

    /**
     * Saves a message to a logfile.
     *
     * @param mixed  $message
     * @param string $logFile
     * @param string $logDir
     */
    public static function save($request, $data = [], $logFile = 'request.log', $logDir = 'data/logs')
    {
        if ($logFile === null) {
            // Useful for just Z-Ray logging
            return;
        }
        $logger = static::getInstance($logFile, $logDir);
        $data = array_merge([
            'path' => $request->getUri()->getPath(),
            'method' => $request->getMethod(),
        ], $data);

        $header = $request->getHeader('X-Request-Id');
        if ($header) {
            $requestId = $header->getFieldValue();
            $data['request_id'] = $requestId;
        }
        $header = $request->getHeader('X-Request-Name');
        if ($header) {
            $requestName = $header->getFieldValue();
            $data['request_name'] = $requestName;
        }
        $header = $request->getHeader('X-Request-Time');
        if ($header) {
            $requestTime = $header->getFieldValue();
            $data['request_time'] = $requestTime;
        }
        $logger->debug(json_encode($data));
    }

    /**
     * Gets an instance of this logger and sets the log directory and filename.
     *
     * @param string $logFile
     * @param string $logDir
     *
     * @return \LosLog\Log\RequestLogger
     */
    public static function getInstance($logFile = 'request.log', $logDir = 'data/logs')
    {
        if (static::$instance instanceof self) {
            return static::$instance;
        }
        static::$instance = new self($logFile, $logDir);

        return static::$instance;
    }
}
