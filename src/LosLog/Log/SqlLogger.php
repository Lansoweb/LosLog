<?php
/**
 * Logs all doctrine database operations
 *
 * @package    LosLos\Log
 * @author     Leandro Silva <lansoweb@hotmail.com>
 * @copyright  2011-2012 Leandro Silva
 */
namespace LosLog\Log;
use LosLog\Log\AbstractLogger;

use Doctrine\DBAL\Logging\SQLLogger as LogInterface;

/**
 * Logs all doctrine database operations
 *
 * @package    LosLos\Log
 * @author     Leandro Silva <lansoweb@hotmail.com>
 * @copyright  2011-2012 Leandro Silva
 */
class SqlLogger extends AbstractLogger implements LogInterface
{
    /*
     * (non-PHPdoc)
     * @see \Doctrine\DBAL\Logging\SQLLogger::startQuery()
     */
    public function startQuery ($sql, array $params = null, array $types = null)
    {
        $msg = 'SQL: ' . $sql;
        if ($params) {
            $msg .= PHP_EOL . "\tPARAMS: " . json_encode($params);
        }
        if ($types) {
            $msg .= PHP_EOL . "\tTYPES: " . json_encode($types);
        }
        $this->debug($msg);
    }

    /*
     * (non-PHPdoc)
     * @see \Doctrine\DBAL\Logging\SQLLogger::stopQuery()
     */
    public function stopQuery ()
    {}
}
