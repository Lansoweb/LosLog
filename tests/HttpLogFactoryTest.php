<?php

namespace LosMiddleware\LosLog;

use PHPUnit\Framework\TestCase;
use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\Config;
use org\bovigo\vfs\vfsStream;

class HttpLogFactoryTest extends TestCase
{
    /**
     * @var HttpLogFactory
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new HttpLogFactory();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers LosMiddleware\LosLog\HttpLogFactory::__invoke
     */
    public function testInvoke()
    {
        $root = vfsStream::setup('home');
        $file = vfsStream::url('home/static.log');

        $container = new ServiceManager(new Config([]));
        $container->setService('config', [
            'loslog' => [
                'log_dir' => 'home',
                'http_logger_file' => $file,
                'log_request' => true,
                'log_response' => true,
            ],
        ]);
        $this->assertInstanceOf(HttpLog::class, $this->object->__invoke($container));
    }
}
