<?php

/**
 * Tests for ModuleOptions.
 *
 * @author     Leandro Silva <lansoweb@hotmail.com>
 * @copyright  2011-2012 Leandro Silva
 */
namespace LosLogTest\Options;

use LosLog\Options\ModuleOptions;

/**
 * ModuleOptions test case.
 */
class ModuleOptionsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ModuleOptions
     */
    private $ModuleOptions;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();

        $this->ModuleOptions = new ModuleOptions(
                [
                        'log_dir' => 'data/logs',
                        'use_entity_logger' => true,
                ]);
        @mkdir('data/logs');
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        $this->ModuleOptions = null;

        parent::tearDown();
    }

    public function testDefaultOptions()
    {
        $options = new ModuleOptions();
        $this->assertEquals('data/logs', $options->getLogDir());
        $this->assertFalse($options->getUseEntityLogger());
    }

    public function testGetLogDir()
    {
        $this->assertEquals('data/logs', $this->ModuleOptions->getLogDir());
    }

    public function testSetLogDir()
    {
        $this->ModuleOptions->setLogDir('data/logs');
        $this->assertEquals('data/logs', $this->ModuleOptions->getLogDir());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidLogDir()
    {
        $dir = sys_get_temp_dir().uniqid();
        $this->ModuleOptions->setLogDir($dir);
    }

    public function testGetUseEntityLogger()
    {
        $this->assertTrue($this->ModuleOptions->getUseEntityLogger());
    }

    public function testSetEntityLogDebug()
    {
        $this->ModuleOptions->setUseEntityLogger(false);
        $this->assertFalse($this->ModuleOptions->getUseEntityLogger());
    }

    public function testGetUseSqlLogger()
    {
        $this->assertFalse($this->ModuleOptions->getUseSqlLogger());
    }

    public function testSetSqlLogDebug()
    {
        $this->ModuleOptions->setUseSqlLogger(false);
        $this->assertFalse($this->ModuleOptions->getUseSqlLogger());
    }

    public function testGetUseErrorLogger()
    {
        $this->assertFalse($this->ModuleOptions->getUseErrorLogger());
    }

    public function testSetErrorLogDebug()
    {
        $this->ModuleOptions->setUseErrorLogger(false);
        $this->assertFalse($this->ModuleOptions->getUseErrorLogger());
    }

    public function testGetEntityLoggerFile()
    {
        $this->assertSame('entity.log', $this->ModuleOptions->getEntityLoggerFile());
    }

    public function testSetEntityLoggerFile()
    {
        $this->ModuleOptions->setEntityLoggerFile('new.log');
        $this->assertSame('new.log', $this->ModuleOptions->getEntityLoggerFile());
    }

    public function testGetSqlLoggerFile()
    {
        $this->assertSame('sql.log', $this->ModuleOptions->getSqlLoggerFile());
    }

    public function testSetSqlLoggerFile()
    {
        $this->ModuleOptions->setSqlLoggerFile('new.log');
        $this->assertSame('new.log', $this->ModuleOptions->getSqlLoggerFile());
    }

    public function testGetErrorLoggerFile()
    {
        $this->assertSame('error.log', $this->ModuleOptions->getErrorLoggerFile());
    }

    public function testSetErrorLoggerFile()
    {
        $this->ModuleOptions->setErrorLoggerFile('new.log');
        $this->assertSame('new.log', $this->ModuleOptions->getErrorLoggerFile());
    }

    public function testGetStaticLoggerFile()
    {
        $this->assertSame('static.log', $this->ModuleOptions->getStaticLoggerFile());
    }

    public function testSetStaticLoggerFile()
    {
        $this->ModuleOptions->setStaticLoggerFile('new.log');
        $this->assertSame('new.log', $this->ModuleOptions->getStaticLoggerFile());
    }
}
