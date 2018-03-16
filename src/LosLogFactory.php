<?php
namespace LosMiddleware\LosLog;

use Zend\ServiceManager\Factory\FactoryInterface;

class LosLogFactory implements FactoryInterface
{
    /**
     * @param \Interop\Container\ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return LosLog|object
     * @throws Exception\InvalidArgumentException
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(\Interop\Container\ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('config');
        $losConfig = $config['loslog'] ?? [];

        $logDir = $losConfig['log_dir'] ?? 'data/logs';
        $logFile = $losConfig['error_logger_file'] ?? 'error.log';

        $logger = AbstractLogger::generateFileLogger($logFile, $logDir);

        return new LosLog($logger);
    }
}
