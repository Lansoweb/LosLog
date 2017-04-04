<?php
namespace LosMiddleware\LosLog;

use Interop\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Zend\ServiceManager\Factory\DelegatorFactoryInterface;
use Zend\Stratigility\Middleware\ErrorHandler;

class ErrorHandlerListenerDelegatorFactory implements DelegatorFactoryInterface
{
    /**
     * {@inheritDoc}
     * @see \Zend\ServiceManager\Factory\DelegatorFactoryInterface::__invoke()
     */
    public function __invoke(ContainerInterface $container, $name, callable $callback, array $options = null)
    {
        if (!$container->has(LoggerInterface::class)) {
            throw new \RuntimeException("You must define a factory for LoggerInterface. Check loslog.global.php.dist for an example.");
        }
        $logger = $container->get(LoggerInterface::class);

        /* @var ErrorHandler $errorHandler */
        $errorHandler = $callback();
        $errorHandler->attachListener(new LosLogListener($logger));
        return $errorHandler;
    }
}
