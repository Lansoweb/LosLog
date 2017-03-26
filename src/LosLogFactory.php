<?php
namespace LosMiddleware\LosLog;

use Interop\Container\ContainerInterface;

class LosLogFactory
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
        $logFile = array_key_exists('error_logger_file', $losConfig) ? $losConfig['error_logger_file'] : 'error.log';

        $logger = AbstractLogger::generateFileLogger($logFile, $logDir);

        return new LosLog($logger);
    }
}
