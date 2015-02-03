<?php
/**
 * Logs all doctrine database operations
 *
 * @package   LosLog\Log
 * @author    Leandro Silva <leandro@leandrosilva.info>
 * @link      http://leandrosilva.info Development Blog
 * @link      http://github.com/LansoWeb/LosLog for the canonical source repository
 * @copyright Copyright (c) 2011-2013 Leandro Silva (http://leandrosilva.info)
 * @license   http://leandrosilva.info/licenca-bsd New BSD license
 */
namespace LosLog\Log;

use Doctrine\DBAL\Logging\LoggerChain;
use Doctrine\DBAL\Logging\SQLLogger as LogInterface;

/**
 * Logs all doctrine database operations
 *
 * @package   LosLog\Log
 * @author    Leandro Silva <leandro@leandrosilva.info>
 * @link      http://leandrosilva.info Development Blog
 * @link      http://github.com/LansoWeb/LosLog for the canonical source repository
 * @copyright Copyright (c) 2011-2013 Leandro Silva (http://leandrosilva.info)
 * @license   http://leandrosilva.info/licenca-bsd New BSD license
 */
class SqlLogger extends AbstractLogger implements LogInterface
{
    /*
     * (non-PHPdoc)
     * @see \Doctrine\DBAL\Logging\SQLLogger::startQuery()
     */
    public function startQuery($sql, array $params = null, array $types = null)
    {
        $msg = 'SQL: '.$sql;
        if ($params) {
            $msg .= PHP_EOL."\tPARAMS: ".json_encode($params);
        }
        if ($types) {
            $msg .= PHP_EOL."\tTYPES: ".json_encode($types);
        }
        $this->debug($msg);
    }

    /*
     * (non-PHPdoc)
     * @see \Doctrine\DBAL\Logging\SQLLogger::stopQuery()
     */
    public function stopQuery()
    {
    }

    public function addLoggerTo($em)
    {
        if (null !== $em->getConfiguration()->getSQLLogger()) {
            $logger = new LoggerChain();
            $logger->addLogger($this);
            $logger->addLogger($em->getConfiguration()->getSQLLogger());
            $em->getConfiguration()->setSQLLogger($logger);
        } else {
            $em->getConfiguration()->setSQLLogger($this);
        }
    }
}
