<?php
namespace LosLogTests\Log;

use LosLog\Log\SqlLogger;

class SqlLoggerTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     * @var LosLog\Log\SqlLogger
     */
    protected $logger;

    public function setUp()
    {
        parent::setUp();
        $this->logger = new SqlLogger('sql.log', 'data/logs');
    }

    public function testStartQuery()
    {
        //$this->markTestIncomplete('This test has not been implemented yet.');

        $this->logger->startQuery('testSqlLogger',['param1'=>'param2'],['type1'=>'type2']);

        $log = file_get_contents('data/logs/sql.log');

        $this->assertContains('testSqlLogger', $log);
    }

    public function testAddLoggerTo()
    {
        $em= $this->getMockBuilder('EntityManager')
            ->setMethods(array('getConfiguration'))
            ->getMock();

        $sqlLogger = $this->getMockBuilder('EntityManagerConfig')
            ->setMethods(array('getSQLLogger','setSQLLogger'))
            ->getMock();

        $sqlLogger->expects($this->once())
            ->method('getSQLLogger')
            ->willReturn(null);

        $sqlLogger->expects($this->once())
            ->method('setSQLLogger')
            ->willReturn(true);

        $em->expects($this->exactly(2))
            ->method('getConfiguration')
            ->willReturn($sqlLogger);

        $this->logger->addLoggerTo($em);
    }
}
