<?php
namespace LosLogTest;

use Zend\ServiceManager\ServiceManager;
use Zend\Mvc\Service\ServiceManagerConfig;

/**
 * Base test case to be used when a service manager instance is required
 */
class ServiceManagerTestCase
{
    /**
     * @var array
     */
    protected static $configuration = [];

    /**
     * @static
     * @param array $configuration
     */
    public static function setConfiguration(array $configuration)
    {
        static::$configuration = $configuration;
    }

    /**
     * @static
     * @return array
     */
    public static function getConfiguration()
    {
        return static::$configuration;
    }

    /**
     * Retrieves a new ServiceManager instance
     *
     * @param  array|null     $configuration
     * @return ServiceManager
     */
    public function getServiceManager(array $configuration = null)
    {
        $configuration = $configuration ?: static::getConfiguration();

        $serviceManager = new ServiceManager(
            new ServiceManagerConfig(
                isset($configuration['service_manager']) ? $configuration['service_manager'] : []
            )
        );

        $serviceManager->setService('ApplicationConfig', $configuration);
        $serviceManager->setFactory('ServiceListener', 'Zend\Mvc\Service\ServiceListenerFactory');

        /* @var $moduleManager \Zend\ModuleManager\ModuleManagerInterface */
        $moduleManager = $serviceManager->get('ModuleManager');
        $moduleManager->loadModules();

        return $serviceManager;
    }
}
