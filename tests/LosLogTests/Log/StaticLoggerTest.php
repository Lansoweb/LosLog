<?php

namespace LosLogTests\Log;

use LosLog\Log\StaticLogger;

class StaticLoggerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var LosLog\Log\DevLogger
     */
    protected $logger;

    public function setUp()
    {
        parent::setUp();
    }

    public function testInstanceLog()
    {
        $logger = StaticLogger::getInstance();
        $logger->debug('testDevLogger');

        $log = file_get_contents('data/logs/static.log');

        $this->assertContains('testDevLogger', $log);
    }

    public function testStaticLog()
    {
        StaticLogger::save('testDev');

        $log = file_get_contents('data/logs/static.log');

        $this->assertContains('testDev', $log);
    }
}
