<?php

namespace LosLogTests\Log;

use LosLog\Log\ErrorLogger;

class ErrorLoggerTest extends \PHPUnit_Framework_TestCase
{
    /*
     * @expectedException PHPUnit_Framework_Error_Warning
     */
    public function testErrorLog()
    {
        if (file_exists('data/logs/error.log')) {
            unlink('data/logs/error.log');
        }
        ErrorLogger::registerHandlers();
        $arq = file('testeErro.txt');

        $log = file_get_contents('data/logs/error.log');
        $this->assertContains('testeErro', $log);
        unlink('data/logs/error.log');
    }
}
