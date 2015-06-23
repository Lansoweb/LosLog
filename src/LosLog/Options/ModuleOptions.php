<?php
/**
 * Module options
 *
 * @package   LosLog\Options
 * @author    Leandro Silva <leandro@leandrosilva.info>
 * @link      http://leandrosilva.info Development Blog
 * @link      http://github.com/LansoWeb/LosLog for the canonical source repository
 * @copyright Copyright (c) 2011-2013 Leandro Silva (http://leandrosilva.info)
 * @license   http://leandrosilva.info/licenca-bsd New BSD license
 */
namespace LosLog\Options;

use Zend\Stdlib\AbstractOptions;

/**
 * Module options
 *
 * @package LosLog\Options
 * @author Leandro Silva <leandro@leandrosilva.info>
 * @link http://leandrosilva.info Development Blog
 * @link http://github.com/LansoWeb/LosLog for the canonical source repository
 * @copyright Copyright (c) 2011-2013 Leandro Silva (http://leandrosilva.info)
 * @license http://leandrosilva.info/licenca-bsd New BSD license
 */
class ModuleOptions extends AbstractOptions
{

    /**
     * Rollbar API url
     *
     * @var string
     */
    const API_URL = 'https://api.rollbar.com/api/1/';

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
     * Rollbar Section
     */
    /**
     *
     * @var bool Enabled module or not
     */
    protected $useRollbarLogger = false;

    /**
     *
     * @var string server-side access token
     */
    protected $accessToken = '';

    /**
     *
     * @var string client-side access token
     */
    protected $clientAccessToken = '';

    /**
     *
     * @var string The rollbar api url (defaults to 'https://api.rollbar.com/api/1/')
     */
    protected $baseApiUrl = self::API_URL;

    /**
     *
     * @var int Flush batch early if it reaches this size. default: 50
     */
    protected $batchSize = 50;

    /**
     *
     * @var bool True to batch all reports from a single request together. default true.
     */
    protected $batched = false;

    /**
     *
     * @var string Name of the current branch (default 'master')
     */
    protected $branch = 'master';

    /**
     *
     * @var bool record full stacktraces for PHP errors. default: true
     */
    protected $captureErrorBacktraces = true;

    /**
     *
     * @var string Environment name, e.g. 'production' or 'development'. default: 'production'
     */
    protected $environment = 'production';

    /**
     *
     * @var array Associative array mapping error numbers to sample rates
     */
    protected $errorSampleRates = [];

    /**
     *
     * @var string Either "blocking" (default) or "agent". "blocking" uses curl to send
     *      requests immediately; "agent" writes a relay log to be consumed by rollbar-agent.
     */
    protected $handler = "blocking";

    /**
     *
     * @var string Path to the directory where agent relay log files should be written. default 'data/logs'
     */
    protected $agentLogLocation = 'data/logs';

    /**
     *
     * @var string Server hostname. Default: null, which will result in a call to `gethostname()`)
     */
    protected $host;

    /**
     *
     * @var object An object that has a log($level, $message) method
     */
    protected $logger;

    /**
     *
     * @var int Max PHP error number to report. e.g. 1024 will ignore all errors
     *      above E_USER_NOTICE. default: 1024 (ignore E_STRICT and above)
     */
    protected $maxErrno = 1024;

    /**
     *
     * @var array An associative array containing data about the currently-logged in user.
     *      Required: 'id', optional: 'username', 'email'. All values are strings.
     * @todo Replace array by object
     */
    protected $person = [];

    /**
     *
     * @var a callable Function reference (string, etc. - anything that
     *      [call_user_func()](http://php.net/call_user_func) can handle) returning
     *      an array like the one for 'person'
     */
    protected $personFn;

    /**
     *
     * @var string Path to your project's root dir
     */
    protected $root;

    /**
     *
     * @var array Array of field names to scrub out of POST
     *
     *      Values will be replaced with astrickses. If overridiing, make sure to list all fields you want to scrub,
     *      not just fields you want to add to the default. Param names are converted
     *      to lowercase before comparing against the scrub list.
     *      default: ('passwd', 'password', 'secret', 'confirm_password', 'password_confirmation')
     */
    protected $scrubFields = [
        'passwd',
        'password',
        'secret',
        'confirm_password',
        'password_confirmation'
    ];

    /**
     *
     * @var bool Whether to shift function names in stack traces down one frame, so that the
     *      function name correctly reflects the context of each frame. default: true.
     */
    protected $shiftFunction;

    /**
     *
     * @var int Request timeout for posting to rollbar, in seconds. default 3
     */
    protected $timeout = 3;

    /**
     *
     * @var bool Register Rollbar as an exception handler to log PHP exceptions
     */
    protected $exceptionhandler;

    /**
     *
     * @var bool Register Rollbar as an error handler to log PHP errors
     */
    protected $errorhandler;

    /**
     *
     * @var bool Register Rollbar as an shutdown function
     */
    protected $shutdownfunction;

    /**
     * End Rollbar Section
     */

    /**
     *
     * @return the $logDir
     */
    public function getLogDir()
    {
        return $this->logDir;
    }

    /**
     *
     * @param string $logDir
     */
    public function setLogDir($logDir)
    {
        $logDir = trim($logDir);
        if (! file_exists($logDir)) {
            throw new \InvalidArgumentException("Directory does not exist!");
        }
        if (! is_writable($logDir)) {
            throw new \InvalidArgumentException("Directory not writable!");
        }

        $this->logDir = $logDir;
    }

    /**
     *
     * @return the $useEntityLogger
     */
    public function getUseEntityLogger()
    {
        return $this->useEntityLogger;
    }

    /**
     *
     * @param boolean $useEntityLogger
     */
    public function setUseEntityLogger($useEntityLogger)
    {
        $this->useEntityLogger = $useEntityLogger;
    }

    /**
     *
     * @return the $useSqlLogger
     */
    public function getUseSqlLogger()
    {
        return $this->useSqlLogger;
    }

    /**
     *
     * @param boolean $useSqlLogger
     */
    public function setUseSqlLogger($useSqlLogger)
    {
        $this->useSqlLogger = $useSqlLogger;
    }

    /**
     *
     * @return the $entityLoggerFile
     */
    public function getEntityLoggerFile()
    {
        return $this->entityLoggerFile;
    }

    /**
     *
     * @param string $entityLoggerFile
     */
    public function setEntityLoggerFile($entityLoggerFile)
    {
        $this->entityLoggerFile = $entityLoggerFile;
    }

    /**
     *
     * @return the $sqlLoggerFile
     */
    public function getSqlLoggerFile()
    {
        return $this->sqlLoggerFile;
    }

    /**
     *
     * @param string $sqlLoggerFile
     */
    public function setSqlLoggerFile($sqlLoggerFile)
    {
        $this->sqlLoggerFile = $sqlLoggerFile;
    }

    /**
     *
     * @return the $useErrorLogger
     */
    public function getUseErrorLogger()
    {
        return $this->useErrorLogger;
    }

    /**
     *
     * @param boolean $useErrorLogger
     */
    public function setUseErrorLogger($useErrorLogger)
    {
        $this->useErrorLogger = $useErrorLogger;
    }

    /**
     *
     * @return the $errorLoggerFile
     */
    public function getErrorLoggerFile()
    {
        return $this->errorLoggerFile;
    }

    /**
     *
     * @param string $errorLoggerFile
     */
    public function setErrorLoggerFile($errorLoggerFile)
    {
        $this->errorLoggerFile = $errorLoggerFile;
    }

    /**
     *
     * @return the $staticLoggerFile
     */
    public function getStaticLoggerFile()
    {
        return $this->staticLoggerFile;
    }

    /**
     *
     * @param string $staticLoggerFile
     */
    public function setStaticLoggerFile($staticLoggerFile)
    {
        $this->staticLoggerFile = $staticLoggerFile;
    }

    public function getUseRollbarLogger()
    {
        return $this->useRollbarLogger;
    }

    public function setUseRollbarLogger($useRollbarLogger)
    {
        $this->useRollbarLogger = $useRollbarLogger;
        return $this;
    }

    public function getAccessToken()
    {
        return $this->accessToken;
    }

    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
        return $this;
    }

    public function getClientAccessToken()
    {
        return $this->clientAccessToken;
    }

    public function setClientAccessToken($clientAccessToken)
    {
        $this->clientAccessToken = $clientAccessToken;
        return $this;
    }

    public function getBaseApiUrl()
    {
        return $this->baseApiUrl;
    }

    public function setBaseApiUrl($baseApiUrl)
    {
        $this->baseApiUrl = $baseApiUrl;
        return $this;
    }

    public function getBatchSize()
    {
        return $this->batchSize;
    }

    public function setBatchSize($batchSize)
    {
        $this->batchSize = $batchSize;
        return $this;
    }

    public function getBatched()
    {
        return $this->batched;
    }

    public function setBatched($batched)
    {
        $this->batched = $batched;
        return $this;
    }

    public function getBranch()
    {
        return $this->branch;
    }

    public function setBranch($branch)
    {
        $this->branch = $branch;
        return $this;
    }

    public function getCaptureErrorBacktraces()
    {
        return $this->captureErrorBacktraces;
    }

    public function setCaptureErrorBacktraces($captureErrorBacktraces)
    {
        $this->captureErrorBacktraces = $captureErrorBacktraces;
        return $this;
    }

    public function getEnvironment()
    {
        return $this->environment;
    }

    public function setEnvironment($environment)
    {
        $this->environment = $environment;
        return $this;
    }

    public function getErrorSampleRates()
    {
        return $this->errorSampleRates;
    }

    public function setErrorSampleRates($errorSampleRates)
    {
        $this->errorSampleRates = $errorSampleRates;
        return $this;
    }

    public function getHandler()
    {
        return $this->handler;
    }

    public function setHandler($handler)
    {
        $this->handler = $handler;
        return $this;
    }

    public function getAgentLogLocation()
    {
        if (!empty($this->agentLogLocation) && $this->agentLogLocation[0] != '/') {
            return realpath(getcwd().'/'.$this->agentLogLocation);
        }

        return $this->agentLogLocation;
    }

    public function setAgentLogLocation($agentLogLocation)
    {
        $this->agentLogLocation = $agentLogLocation;
        return $this;
    }

    public function getHost()
    {
        return $this->host;
    }

    public function setHost($host)
    {
        $this->host = $host;
        return $this;
    }

    public function getLogger()
    {
        return $this->logger;
    }

    public function setLogger($logger)
    {
        $this->logger = $logger;
        return $this;
    }

    public function getMaxErrno()
    {
        return $this->maxErrno;
    }

    public function setMaxErrno($maxErrno)
    {
        $this->maxErrno = $maxErrno;
        return $this;
    }

    public function getPerson()
    {
        return $this->person;
    }

    public function setPerson($person)
    {
        $this->person = $person;
        return $this;
    }

    public function getPersonFn()
    {
        return $this->personFn;
    }

    public function setPersonFn($personFn)
    {
        $this->personFn = $personFn;
        return $this;
    }

    public function getRoot()
    {
        return $this->root;
    }

    public function setRoot($root)
    {
        $this->root = $root;
        return $this;
    }

    public function getScrubFields()
    {
        return $this->scrubFields;
    }

    public function setScrubFields($scrubFields)
    {
        $this->scrubFields = $scrubFields;
        return $this;
    }

    public function getShiftFunction()
    {
        return $this->shiftFunction;
    }

    public function setShiftFunction($shiftFunction)
    {
        $this->shiftFunction = $shiftFunction;
        return $this;
    }

    public function getTimeout()
    {
        return $this->timeout;
    }

    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;
        return $this;
    }

    public function getExceptionhandler()
    {
        return $this->exceptionhandler;
    }

    public function setExceptionhandler($exceptionhandler)
    {
        $this->exceptionhandler = $exceptionhandler;
        return $this;
    }

    public function getErrorhandler()
    {
        return $this->errorhandler;
    }

    public function setErrorhandler($errorhandler)
    {
        $this->errorhandler = $errorhandler;
        return $this;
    }

    public function getShutdownfunction()
    {
        return $this->shutdownfunction;
    }

    public function setShutdownfunction($shutdownfunction)
    {
        $this->shutdownfunction = $shutdownfunction;
        return $this;
    }
}
