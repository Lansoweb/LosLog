<?php
namespace LosMiddleware\LosLog;

use Interop\Container\ContainerInterface;
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
        $logger = $container->get(LosLog::class);

        /* @var ErrorHandler $errorHandler */
        $errorHandler = $callback;
        $errorHandler->attachListener(new LosLogListener($logger));
        return $errorHandler;
    }
}
