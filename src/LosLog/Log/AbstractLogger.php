<?php
/**
 * Abstract class for Loggers
 *
 * @package   LosLog\Log
 * @author    Leandro Silva <leandro@leandrosilva.info>
 * @link      http://leandrosilva.info Development Blog
 * @link      http://github.com/LansoWeb/LosLog for the canonical source repository
 * @copyright Copyright (c) 2011-2013 Leandro Silva (http://leandrosilva.info)
 * @license   http://leandrosilva.info/licenca-bsd New BSD license
 */
namespace LosLog\Log;

use Zend\Log\Writer\Stream;
use Zend\Log\Logger;

/**
 * Abstract class for Loggers
 *
 * @package   LosLog\Log
 * @author    Leandro Silva <leandro@leandrosilva.info>
 * @link      http://leandrosilva.info Development Blog
 * @link      http://github.com/LansoWeb/LosLog for the canonical source repository
 * @copyright Copyright (c) 2011-2013 Leandro Silva (http://leandrosilva.info)
 * @license   http://leandrosilva.info/licenca-bsd New BSD license
 */
abstract class AbstractLogger extends Logger
{
    /**
     * Log directory
     *
     * @var string
     */
    private $_logDir;

    /**
     * Log file
     *
     * @var string
     */
    private $_logFile;

    /**
     * Construtor
     *
     * Sets the logDir, logFile and thr writer. If the logDir is null, the system's temp dir will be used
     *
     * @param string $logFile
     * @param string $logDir
     */
    public function __construct($logFile, $logDir = null)
    {
        parent::__construct();

        if (null === $logDir) {
            $logDir = sys_get_temp_dir();
        }
        $this->setLogDir($logDir);
        $this->setLogFile($logFile);

        $writer = new Stream($logDir.DIRECTORY_SEPARATOR.$logFile);
        $this->addWriter($writer);
    }

    /**
     * Returns the log dir
     *
     * @return string
     */
    public function getLogDir()
    {
        return $this->_logDir;
    }

    /**
     * Setter for log dir
     *
     * @param  string                    $logDir
     * @throws \InvalidArgumentException
     */
    public function setLogDir($logDir)
    {
        $logDir = trim($logDir);
        if (!file_exists($logDir) || !is_writable($logDir)) {
            throw new \InvalidArgumentException("Invalid log directory!");
        }

        $this->_logDir = $logDir;
    }
    /**
     * @return string $_logFile
     */
    public function getLogFile()
    {
        return $this->_logFile;
    }

    /**
     * @param string $logFile
     */
    public function setLogFile($logFile)
    {
        $logFile = trim($logFile);
        if (null === $logFile || '' == $logFile) {
            throw new \InvalidArgumentException("Invalid log directory!");
        }
        $this->_logFile = $logFile;
    }
}
