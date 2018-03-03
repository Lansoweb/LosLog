<?php

namespace LosMiddleware\LosLog;

use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\Response;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2015-11-02 at 10:24:10.
 */
class LosLogTest extends TestCase
{
    /**
     * @var LosLog
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        vfsStream::setup('home');
        $file = vfsStream::url('home/static.log');

        $logger = AbstractLogger::generateFileLogger($file, null);
        $this->object = new LosLog($logger);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers LosMiddleware\LosLog\LosLog::__construct
     * @covers LosMiddleware\LosLog\LosLog::__invoke
     */
    public function testInvoke()
    {
        $request = new ServerRequest();
        $response = new Response();

        $error = new \Exception('Exception test!');

        $this->object->__invoke($error, $request, $response);
    }

    /**
     * @covers LosMiddleware\LosLog\LosLog::__invoke
     */
    public function testInvokeWithCallable()
    {
        $request = new ServerRequest();
        $response = new Response();

        $error = new \Exception('Exception test!');

        $this->object->__invoke($error, $request, $response, function ($request, $response) {
            return $response;
        });
    }
}
