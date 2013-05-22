<?php
/**
 * Module options
 *
 * @package    LosLog
 * @author     Leandro Silva <lansoweb@hotmail.com>
 * @copyright  2011-2012 Leandro Silva
 */
namespace LosLog\Options;
use Zend\Stdlib\AbstractOptions;

/**
 * Module options
 *
 * @package    LosLog
 * @author     Leandro Silva <lansoweb@hotmail.com>
 * @copyright  2011-2012 Leandro Silva
 */
class ModuleOptions extends AbstractOptions
{
    /**
     * Default log directory
     *
     * @var string
     */
    protected $logDir = 'data/logs';

    /**
     * Use EntityLogger
     *
     * @var bool Default is false
     */
    protected $useEntityLogger = false;

    /**
     * Log filename for the EntityLogger
     *
     * @var string
     */
    protected $entityLoggerFile = 'entity.log';

    /**
     * Use SqlLogger
     *
     * @var bool Default is false
     */
    protected $useSqlLogger = false;

    /**
     * Log filename for the SqlLogger
     *
     * @var string
     */
    protected $sqlLoggerFile = 'sql.log';

    /**
     * Use ErrorLogger
     *
     * @var bool Default is false
     */
    protected $useErrorLogger = false;

    /**
     * Log filename for the ErrorLogger
     *
     * @var string
     */
    protected $errorLoggerFile = 'error.log';

    /**
     * Log filename for the StaticLogger
     *
     * @var string
     */
    protected $staticLoggerFile = 'static.log';

    /**
     * @return the $logDir
     */
    public function getLogDir ()
    {
        return $this->logDir;
    }

    /**
     * @param string $logDir
     */
    public function setLogDir ($logDir)
    {
        $logDir = trim($logDir);
        if (!file_exists($logDir) || !is_writable($logDir)) {
            throw new \InvalidArgumentException("Invalid log directory!");
        }

        $this->logDir = $logDir;
    }

    /**
     * @return the $useEntityLogger
     */
    public function getUseEntityLogger ()
    {
        return $this->useEntityLogger;
    }

    /**
     * @param boolean $useEntityLogger
     */
    public function setUseEntityLogger ($useEntityLogger)
    {
        $this->useEntityLogger = $useEntityLogger;
    }

    /**
     * @return the $useSqlLogger
     */
    public function getUseSqlLogger ()
    {
        return $this->useSqlLogger;
    }

    /**
     * @param boolean $useSqlLogger
     */
    public function setUseSqlLogger ($useSqlLogger)
    {
        $this->useSqlLogger = $useSqlLogger;
    }
    /**
     * @return the $useAppLogger
     */
    public function getUseAppLogger ()
    {
        return $this->useAppLogger;
    }

    /**
     * @param boolean $useAppLogger
     */
    public function setUseAppLogger ($useAppLogger)
    {
        $this->useAppLogger = $useAppLogger;
    }
    /**
     * @return the $entityLoggerFile
     */
    public function getEntityLoggerFile ()
    {
        return $this->entityLoggerFile;
    }

    /**
     * @param string $entityLoggerFile
     */
    public function setEntityLoggerFile ($entityLoggerFile)
    {
        $this->entityLoggerFile = $entityLoggerFile;
    }

    /**
     * @return the $sqlLoggerFile
     */
    public function getSqlLoggerFile ()
    {
        return $this->sqlLoggerFile;
    }

    /**
     * @param string $sqlLoggerFile
     */
    public function setSqlLoggerFile ($sqlLoggerFile)
    {
        $this->sqlLoggerFile = $sqlLoggerFile;
    }

    /**
     * @return the $useErrorLogger
     */
    public function getUseErrorLogger ()
    {
        return $this->useErrorLogger;
    }

    /**
     * @param boolean $useErrorLogger
     */
    public function setUseErrorLogger ($useErrorLogger)
    {
        $this->useErrorLogger = $useErrorLogger;
    }

    /**
     * @return the $errorLoggerFile
     */
    public function getErrorLoggerFile ()
    {
        return $this->errorLoggerFile;
    }

    /**
     * @param string $errorLoggerFile
     */
    public function setErrorLoggerFile ($errorLoggerFile)
    {
        $this->errorLoggerFile = $errorLoggerFile;
    }

    /**
     * @return the $staticLoggerFile
     */
    public function getStaticLoggerFile ()
    {
        return $this->staticLoggerFile;
    }

    /**
     * @param string $staticLoggerFile
     */
    public function setStaticLoggerFile ($staticLoggerFile)
    {
        $this->staticLoggerFile = $staticLoggerFile;
    }

}
