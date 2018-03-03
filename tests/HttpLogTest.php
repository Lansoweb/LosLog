<?php

namespace LosMiddleware\LosLog;

use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\Response;

class HttpLogTest extends TestCase
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
        $this->object = new HttpLog($logger,[
            'log_request' => true,
            'log_response' => true,
        ]);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers LosMiddleware\LosLog\HttpLog::__construct
     * @covers LosMiddleware\LosLog\HttpLog::__invoke
     */
    public function testInvoke()
    {
        $request = new ServerRequest();
        $response = new Response();

        $this->object->__invoke($request, $response);
    }

    /**
     * @covers LosMiddleware\LosLog\HttpLog::__invoke
     */
    public function testInvokeWithCallable()
    {
        $request = new ServerRequest();
        $response = new Response();

        $this->object->__invoke($request, $response, function ($request, $response) {
            return $response;
        });
    }
}
