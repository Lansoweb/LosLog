<?php

/**
 * Logs errors and exceptions.
 *
 * @author    Leandro Silva <leandro@leandrosilva.info>
 *
 * @link      http://leandrosilva.info Development Blog
 * @link      http://github.com/LansoWeb/LosLog for the canonical source repository
 *
 * @copyright Copyright (c) 2011-2013 Leandro Silva (http://leandrosilva.info)
 * @license   http://leandrosilva.info/licenca-bsd New BSD license
 */
namespace LosMiddleware\LosLog;

use Zend\Log\Logger;

/**
 * Logs errors and exceptions.
 *
 * @author    Leandro Silva <leandro@leandrosilva.info>
 *
 * @link      http://leandrosilva.info Development Blog
 * @link      http://github.com/LansoWeb/LosLog for the canonical source repository
 *
 * @copyright Copyright (c) 2011-2013 Leandro Silva (http://leandrosilva.info)
 * @license   http://leandrosilva.info/licenca-bsd New BSD license
 * @codeCoverageIgnore
 */
class ErrorLogger extends AbstractLogger
{
    /**
     * Registers the handlers for errors and exceptions.
     *
     * @param string $logFile
     * @param string $logDir
     */
    public static function registerHandlers($logFile = 'error.log', $logDir = 'data/logs', $continue = true)
    {
        $logger = AbstractLogger::generateFileLogger($logFile, $logDir);

        Logger::registerErrorHandler($logger->getLogger(), $continue);
        Logger::registerFatalErrorShutdownFunction($logger->getLogger());
    }
}
