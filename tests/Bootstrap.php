<?php
/**
 * Bootstrap file for tests
 *
 * @package    LosLosTests
 * @author     Leandro Silva <lansoweb@hotmail.com>
 * @copyright  2011-2012 Leandro Silva
 */
namespace LosLogtests;

use Zend\Loader\AutoloaderFactory;
use Zend\Mvc\Service\ServiceManagerConfig;
use Zend\ServiceManager\ServiceManager;
use Zend\Stdlib\ArrayUtils;
use RuntimeException;

error_reporting ( E_ALL | E_STRICT );
chdir ( __DIR__ );
/**
 * Bootstrap class for tests
 *
 * @package    LosLos
 * @author     Leandro Silva <lansoweb@hotmail.com>
 * @copyright  2011-2012 Leandro Silva
 */
class Bootstrap
{
    protected static $serviceManager;
    protected static $app;

    public static function init()
    {
        putenv ( 'APPLICATION_ENV=testing' );

        // Load the user-defined test configuration file, if it exists;
        // otherwise, load
        if (is_readable ( __DIR__ . '/TestConfig.php' )) {
            $testConfig = include __DIR__ . '/TestConfig.php';
        } else {
            $testConfig = include __DIR__ . '/TestConfig.php.dist';
        }

        $zf2ModulePaths = [
                dirname ( dirname ( __DIR__ ) )
        ];
        $vendorPath = static::findParentPath ( 'vendor' );

        if ($vendorPath) {
            $zf2ModulePaths [] = $vendorPath;
        }
        if (($path = static::findParentPath ( 'module' )) !== $zf2ModulePaths [0]) {
            $zf2ModulePaths [] = $path;
        }

        $zf2ModulePaths = implode ( PATH_SEPARATOR, $zf2ModulePaths ) . PATH_SEPARATOR;
        $zf2ModulePaths .= getenv ( 'ZF2_MODULES_TEST_PATHS' ) ?  : (defined ( 'ZF2_MODULES_TEST_PATHS' ) ? ZF2_MODULES_TEST_PATHS : '');

        static::initAutoloader ($vendorPath);

        // use ModuleManager to load this module and it's dependencies
        $baseConfig = [
                'module_listener_options' => [
                        'module_paths' => explode ( PATH_SEPARATOR, $zf2ModulePaths )
                ]
        ];

        $config = ArrayUtils::merge ( $baseConfig, $testConfig );

        $serviceManager = new ServiceManager ( new ServiceManagerConfig () );
        $serviceManager->setService ( 'ApplicationConfig', $config );
        $serviceManager->get ( 'ModuleManager' )->loadModules ();
        static::$serviceManager = $serviceManager;

        $app = \Zend\Mvc\Application::init ( include 'config/application.config.php' );
        $em = $app->getServiceManager ()->get ( 'Doctrine\ORM\EntityManager' );

        static::$app = $app;
        static::$serviceManager = $app->getServiceManager ();

        if (file_exists ( 'data/logs/entity.log' )) {
            @unlink ( 'data/logs/entity.log' );
        }
        if (file_exists ( 'data/logs/erros.log' )) {
            @unlink ( 'data/logs/erros.log' );
        }
        if (file_exists ( 'data/logs/sql.log' )) {
            @unlink ( 'data/logs/sql.log' );
        }
        if (file_exists ( 'data/logs/dev.log' )) {
            @unlink ( 'data/logs/dev.log' );
        }
    }

    public static function getServiceManager()
    {
        return static::$serviceManager;
    }

    public static function getApp()
    {
        return static::$app;
    }

    protected static function initAutoloader($vendorPath)
    {
        if (file_exists($vendorPath .'/autoload.php')) {
            $loader = include $vendorPath .'/autoload.php';
        } elseif (file_exists(__DIR__ .'/../../Libs/autoload.php')) {
            $loader = include __DIR__ .'/../../Libs/autoload.php';
        }

        if (!class_exists('Zend\Loader\AutoloaderFactory')) {
            throw new RuntimeException('Unable to load ZF2. Run `php composer.phar install` or define a ZF2_PATH environment variable.');
        }

        $loader->add ( 'LosLogTests', __DIR__ );
    }

    protected static function findParentPath($path)
    {
        $dir = __DIR__;
        $previousDir = '.';
        while ( ! is_dir ( $dir . '/' . $path ) ) {
            $dir = dirname ( $dir );
            if ($previousDir === $dir)
                return false;
            $previousDir = $dir;
        }

        return $dir . '/' . $path;
    }
}

Bootstrap::init ();
