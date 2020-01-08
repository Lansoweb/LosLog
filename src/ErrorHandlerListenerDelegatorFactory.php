<?php
namespace LosMiddleware\LosLog;

use Interop\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Laminas\ServiceManager\Factory\DelegatorFactoryInterface;
use Laminas\Stratigility\Middleware\ErrorHandler;

class ErrorHandlerListenerDelegatorFactory implements DelegatorFactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $name
     * @param callable $callback
     * @param array|null $options
     * @return object|ErrorHandler
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container, $name, callable $callback, array $options = null)
    {
        if (! $container->has(LoggerInterface::class)) {
            throw new \RuntimeException(
                "You must define a factory for LoggerInterface. Check loslog.global.php.dist for an example."
            );
        }
        $logger = $container->get(LoggerInterface::class);

        /* @var ErrorHandler $errorHandler */
        $errorHandler = $callback();
        $errorHandler->attachListener(new LosLogListener($logger));
        return $errorHandler;
    }
}
