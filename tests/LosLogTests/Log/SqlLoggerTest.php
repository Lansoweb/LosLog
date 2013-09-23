<?php

namespace LosLogTests\Log;

use LosLog\Log\SqlLogger;

class SqlLoggerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var LosLog\Log\SqlLogger
     */
    protected $logger;

    public function setUp()
    {
        parent::setUp();
        $this->logger = new SqlLogger('sql.log');
    }

    public function testSQLLogger()
    {
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );

        $log = file_get_contents('data/logs/sql.log');

        $this->assertContains('testeSqlLogger', $log);
    }
}
