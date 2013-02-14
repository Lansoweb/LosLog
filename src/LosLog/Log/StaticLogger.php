<?php
/**
 * Development logger
 *
 * @package    LosLos\Log
 * @author     Leandro Silva <lansoweb@hotmail.com>
 * @copyright  2011-2012 Leandro Silva
 */
namespace LosLog\Log;

use Zend\Log\Writer\Stream;
use LosLog\Log\AbstractLogger;

/**
 * Development logger
 *
 * @package    LosLos\Log
 * @author     Leandro Silva <lansoweb@hotmail.com>
 * @copyright  2011-2012 Leandro Silva
 */
class StaticLogger extends AbstractLogger
{
    /**
     * Logger instance
     *
     * @var LosLog\Log\StaticLogger
     */
    protected static $instance;

    /**
     * Saves a message to a logfile
     *
     * @param string $message
     * @param string $filename
     * @param string $logDir
     */
    public static function save($message, $logFile = 'static.log', $logDir = 'data/logs')
    {
        $logger = static::getInstance($logFile, $logDir);
        $logger->debug($message);
    }

    /**
     * Gets an instance of this logger and sets the log directory and filename
     *
     * @param string $logFile
     * @param string $logDir
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
