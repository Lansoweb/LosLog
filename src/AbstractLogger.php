<?php

namespace LosMiddleware\LosLog;

use Laminas\Log\Logger;
use Laminas\Log\PsrLoggerAdapter;
use Laminas\Log\Writer\Stream;

abstract class AbstractLogger
{
    /**
     * @param string $logFile
     * @param string $logDir
     * @return PsrLoggerAdapter
     * @throws Exception\InvalidArgumentException
     */
    public static function generateFileLogger($logFile, $logDir)
    {
        $fileName = static::validateLogFile($logFile, $logDir);
        $zendLogLogger = new Logger();
        $zendLogLogger->addWriter(new Stream($fileName));

        return new PsrLoggerAdapter($zendLogLogger);
    }

    /**
     * @param string $logFile
     * @param string $logDir
     * @return string
     * @throws Exception\InvalidArgumentException
     */
    public static function validateLogFile($logFile, $logDir)
    {
        // Is logFile a stream url?
        if (strpos($logFile, '://') !== false) {
            return $logFile;
        }

        if (! file_exists($logDir) || ! is_writable($logDir)) {
            throw new Exception\InvalidArgumentException("Log dir {$logDir} must exist and be writable!");
        }

        $fileName = $logDir.DIRECTORY_SEPARATOR.$logFile;

        if (file_exists($fileName) && ! is_writable($fileName)) {
            throw new Exception\InvalidArgumentException("Log file {$fileName} must be writable!");
        }

        return $fileName;
    }
}
