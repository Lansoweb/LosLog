<?php
namespace LosMiddleware\LosLog;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class HttpLogFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     * @see \Zend\ServiceManager\Factory\FactoryInterface::__invoke()
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('config');
        $losConfig = array_key_exists('loslog', $config) ? $config['loslog'] : [];

        $logDir = array_key_exists('log_dir', $losConfig) ? $losConfig['log_dir'] : 'data/logs';
        $logFile = array_key_exists('http_logger_file', $losConfig) ? $losConfig['http_logger_file'] : 'http.log';

        $logger = AbstractLogger::generateFileLogger($logFile, $logDir);

        return new HttpLog($logger, $losConfig);
    }
}
