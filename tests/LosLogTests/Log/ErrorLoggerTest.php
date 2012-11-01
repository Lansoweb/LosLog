<?php

namespace LosLogTests\Log;

use LosLog\Log\ErrorLogger;

class ErrorLoggerTest extends \PHPUnit_Framework_TestCase
{
    public function testErrorLog()
    {
        ErrorLogger::registerHandlers();
        $arq = file('testeErro.txt');

        $log = file_get_contents('data/logs/error.log');
        $this->assertContains('testeErro', $log);
    }
}
