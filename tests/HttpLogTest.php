<?php

namespace LosMiddleware\LosLog;

use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use Psr\Http\Server\RequestHandlerInterface;
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
        $this->root = vfsStream::setup('home');
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
     * @covers LosMiddleware\LosLog\HttpLog::process
     */
    public function testProcess()
    {
        $request = new ServerRequest();
        $response = new Response();
        $handler = $this->prophesize(RequestHandlerInterface::class);
        $handler->handle($request)->willReturn($response);

        $this->object->process($request, $handler->reveal());

        $log = $this->root->getChild('home/static.log')->getContent();
        $this->assertNotFalse(strpos($log, 'Request: GET /'));
        $this->assertNotFalse(strpos($log, 'Response: 200 OK'));
    }
}
