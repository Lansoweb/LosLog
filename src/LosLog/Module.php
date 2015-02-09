<?php

/**
 * Module definition
 *
 * @package   LosLog
 * @author    Leandro Silva <leandro@leandrosilva.info>
 * @link      http://leandrosilva.info Development Blog
 * @link      http://github.com/LansoWeb/LosLog for the canonical source repository
 * @copyright Copyright (c) 2011-2013 Leandro Silva (http://leandrosilva.info)
 * @license   http://leandrosilva.info/licenca-bsd New BSD license
 */
namespace LosLog;

use LosLog\Log\StaticLogger;
use LosLog\Log\ErrorLogger;
use LosLog\Log\EntityLogger;
use LosLog\Log\SqlLogger;
use LosLog\Options\ModuleOptions;
use Zend\ModuleManager\Feature\LocatorRegisteredInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Module definition
 *
 * @package LosLog
 * @author Leandro Silva <leandro@leandrosilva.info>
 * @link http://leandrosilva.info Development Blog
 * @link http://github.com/LansoWeb/LosLog for the canonical source repository
 * @copyright Copyright (c) 2011-2013 Leandro Silva (http://leandrosilva.info)
 * @license http://leandrosilva.info/licenca-bsd New BSD license
 */
class Module implements AutoloaderProviderInterface, LocatorRegisteredInterface
{
    /**
     * Module bootstrap
     */
    public function onBootstrap($e)
    {
        $sm = $e->getApplication()->getServiceManager();
        $config = $sm->get('loslog_options');

        if ($config->getUseErrorLogger()) {
            $logger = $sm->get('LosLog\Log\ErrorLogger');

            $eventManager = $e->getApplication()->getEventManager();
            $eventManager->attach(\Zend\Mvc\MvcEvent::EVENT_DISPATCH_ERROR, [
                $logger,
                'dispatchError',
            ], - 100);
        }

        if ($config->getUseSqlLogger()) {
            $em = $sm->get('doctrine.entitymanager.orm_default');
            $sqlLogger = $sm->get('LosLog\Log\SqlLogger');
            $sqlLogger->addLoggerTo($em);
        }
    }

    public function getServiceConfig()
    {
        return [
            'factories' => [
                'loslog_options' => function (ServiceLocatorInterface $sm) {
                    $config = $sm->get('Configuration');

                    return new ModuleOptions(isset($config['loslog']) ? $config['loslog'] : []);
                },
                'LosLog\Log\EntityLogger' => function (ServiceLocatorInterface $sm) {
                    $config = $sm->get('loslog_options');
                    $logger = new EntityLogger($config->getEntityLoggerFile(), $config->getLogDir());

                    return $logger;
                },
                'LosLog\Log\ErrorLogger' => function (ServiceLocatorInterface $sm) {
                    $config = $sm->get('loslog_options');
                    $logger = new ErrorLogger($config->getErrorLoggerFile(), $config->getLogDir());

                    return $logger;
                },
                'LosLog\Log\SqlLogger' => function (ServiceLocatorInterface $sm) {
                    $config = $sm->get('loslog_options');
                    $logger = new SqlLogger($config->getSqlLoggerFile(), $config->getLogDir());

                    return $logger;
                },
                'LosLog\Log\StaticLogger' => function (ServiceLocatorInterface $sm) {
                    $config = $sm->get('loslog_options');
                    $logger = StaticLogger::getInstance($config->getStaticLoggerFile(), $config->getLogDir());

                    return $logger;
                },
            ],
            'aliases' => [
                'loslog_entitylogger' => 'LosLog\Log\EntityLogger',
                'loslog_errorlogger' => 'LosLog\Log\ErrorLogger',
                'loslog_sqllogger' => 'LosLog\Log\SqlLogger',
                'loslog_staticlogger' => 'LosLog\Log\StaticLogger',
            ],
        ];
    }

    public function getAutoloaderConfig()
    {
        return [
            'Zend\Loader\ClassMapAutoloader' => [
                __DIR__.'/../../autoload_classmap.php',
            ],
            'Zend\Loader\StandardAutoloader' => [
                'namespaces' => [
                    __NAMESPACE__ => __DIR__.'/src/'.__NAMESPACE__,
                ],
            ],
        ];
    }

    public function getConfig()
    {
        return include __DIR__.'/../../config/module.config.php';
    }
}
