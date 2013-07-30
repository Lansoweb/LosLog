<?php
/**
 * Module definition
 *
 * @package    LosLos
 * @author     Leandro Silva <lansoweb@hotmail.com>
 * @copyright  2011-2012 Leandro Silva
 */
namespace LosLog;

use LosLog\Log\StaticLogger;

use LosLog\Log\ErrorLogger;

use LosLog\Log\EntityLogger;

use LosLog\Log\SqlLogger;

use LosLog\Options\ModuleOptions;

use Zend\ModuleManager\Feature\LocatorRegisteredInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;

/**
 * Module definition
 *
 * @package    LosLos
 * @author     Leandro Silva <lansoweb@hotmail.com>
 * @copyright  2011-2012 Leandro Silva
 */
class Module implements AutoloaderProviderInterface, LocatorRegisteredInterface
{
    /**
     * Module bootstrap
     */
    public function onBootstrap ($e)
    {
        $sm = $e->getApplication()->getServiceManager();
        $config = $sm->get('loslog_options');

        if ($config->getUseErrorLogger()) {
            $logger = $sm->get('LosLog\Log\ErrorLogger');
            $em = $sm->get('Doctrine\ORM\EntityManager');

            $eventManager = $e->getApplication()->getEventManager();
            $eventManager->attach(\Zend\Mvc\MvcEvent::EVENT_DISPATCH_ERROR,
                array(
                    $logger,
                    'dispatchError'
                ), - 100);
            $events = $e->getApplication()->getEventManager()->getSharedManager();
            $events->attach('*','save.invalid', function($e) use ($sm, $logger) {
                $form = $e->getParam('form');
                $entity = $e->getParam('entity');
                $logger->crit('Erro salvando form: ' . print_r($form->getMessages(),true));
            });
        }

        if ($config->getUseSqlLogger()) {
            $sqlLogger = $sm->get('LosLog\Log\SqlLogger');
            if (null !== $em->getConfiguration()->getSQLLogger()) {
                $logger = new LoggerChain();
                $logger->addLogger($sqlLogger);
                $logger->addLogger($em->getConfiguration()->getSQLLogger());
                $em->getConfiguration()->setSQLLogger($logger);
            } else {
                $em->getConfiguration()->setSQLLogger($sqlLogger);
            }
        }
    }

    public function getServiceConfig ()
    {
        return array(
                'factories' => array(
                        'loslog_options' => function  ($sm) {
                            $config = $sm->get('Configuration');
                            return new ModuleOptions(
                                    isset($config['loslog']) ? $config['loslog'] : array());
                        },
                        'LosLog\Log\EntityLogger' => function  ($sm) {
                            $config = $sm->get('loslog_options');
                            $logger = new EntityLogger($config->getEntityLoggerFile(), $config->getLogDir());
                            return $logger;
                        },
                        'LosLog\Log\ErrorLogger' => function  ($sm) {
                            $config = $sm->get('loslog_options');
                            $logger = new ErrorLogger($config->getErrorLoggerFile(), $config->getLogDir());
                            return $logger;
                        },
                        'LosLog\Log\SqlLogger' => function  ($sm) {
                            $config = $sm->get('loslog_options');
                            $logger = new SqlLogger($config->getSqlLoggerFile(), $config->getLogDir());
                            return $logger;
                        },
                        'LosLog\Log\StaticLogger' => function  ($sm) {
                            $config = $sm->get('loslog_options');
                            $logger = StaticLogger::getInstance($config->getStaticLoggerFile(), $config->getLogDir());
                            return $logger;
                        },
                )
        );
    }

    public function getAutoloaderConfig ()
    {
        return array(
                'Zend\Loader\ClassMapAutoloader' => array(
                        __DIR__ . '/autoload_classmap.php'
                ),
                'Zend\Loader\StandardAutoloader' => array(
                        'namespaces' => array(
                                __NAMESPACE__ => __DIR__ .'/src/'. __NAMESPACE__
                        )
                )
        );
    }

    public function getConfig ()
    {
        return include __DIR__ . '/config/module.config.php';
    }

}
