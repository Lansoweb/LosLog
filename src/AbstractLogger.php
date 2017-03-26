<?php

namespace LosMiddleware\LosLog;

use Zend\Log\Logger;
use Zend\Log\PsrLoggerAdapter;
use Zend\Log\Writer\Stream;

abstract class AbstractLogger
{
    public static function generateFileLogger($logFile, $logDir)
    {
        $fileName = static::validateLogFile($logFile, $logDir);
        $zendLogLogger = new Logger();
        $zendLogLogger->addWriter(new Stream($fileName));

        return new PsrLoggerAdapter($zendLogLogger);
    }

    public static function validateLogFile($logFile, $logDir)
    {
        // Is logFile a stream url?
        if (strpos($logFile, '://') !== false) {
            return $logFile;
        }

        if (!file_exists($logDir) || !is_writable($logDir)) {
            throw new Exception\InvalidArgumentException("Log dir {$logDir} must exist and be writable!");
        }

        $fileName = $logDir.DIRECTORY_SEPARATOR.$logFile;

        if (file_exists($fileName) && !is_writable($fileName)) {
            throw new Exception\InvalidArgumentException("Log file {$fileName} must be writable!");
        }

        return $fileName;
    }
}
