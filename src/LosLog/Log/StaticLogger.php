<?php
/**
 * Development logger
 *
 * @package   LosLog\Log
 * @author    Leandro Silva <leandro@leandrosilva.info>
 * @link      http://leandrosilva.info Development Blog
 * @link      http://github.com/LansoWeb/LosLog for the canonical source repository
 * @copyright Copyright (c) 2011-2013 Leandro Silva (http://leandrosilva.info)
 * @license   http://leandrosilva.info/licenca-bsd New BSD license
 */
namespace LosLog\Log;

/**
 * Development logger
 *
 * @package   LosLog\Log
 * @author    Leandro Silva <leandro@leandrosilva.info>
 * @link      http://leandrosilva.info Development Blog
 * @link      http://github.com/LansoWeb/LosLog for the canonical source repository
 * @copyright Copyright (c) 2011-2013 Leandro Silva (http://leandrosilva.info)
 * @license   http://leandrosilva.info/licenca-bsd New BSD license
 */
class StaticLogger extends AbstractLogger
{
    /**
     * Logger instance
     *
     * @var \LosLog\Log\StaticLogger
     */
    protected static $instance;

    /**
     * Saves a message to a logfile
     *
     * @param mixed  $message
     * @param string $logFile
     * @param string $logDir
     */
    public static function save($message, $logFile = 'static.log', $logDir = 'data/logs')
    {
        if ($logFile === null) {
            // Useful for just Z-Ray logging
            return;
        }
        $logger = static::getInstance($logFile, $logDir);
        if (is_object($message) && $message instanceof LoggableObject) {
            $message = json_encode($message->losLogMe());
        }
        $logger->debug($message);
    }

    /**
     * Gets an instance of this logger and sets the log directory and filename
     *
     * @param  string                   $logFile
     * @param  string                   $logDir
     * @return \LosLog\Log\StaticLogger
     */
    public static function getInstance($logFile = 'static.log', $logDir = 'data/logs')
    {
        if (static::$instance instanceof StaticLogger) {
            return static::$instance;
        }
        static::$instance = new self($logFile, $logDir);

        return static::$instance;
    }
}
