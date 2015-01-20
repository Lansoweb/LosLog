<?php
/**
 * Tests for ModuleOptions
 *
 * @package    LosLosTests\Options
 * @author     Leandro Silva <lansoweb@hotmail.com>
 * @copyright  2011-2012 Leandro Silva
 */
namespace LosLogTests\Options;

use LosLog\Options\ModuleOptions;

/**
 * ModuleOptions test case.
 */
class ModuleOptionsTest extends \PHPUnit_Framework_TestCase
{
    /**
     *
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
                        'log_dir' => '/tmp',
                        'use_entity_logger' => true
                ]);
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
        $this->assertEquals('/tmp', $this->ModuleOptions->getLogDir());
    }

    public function testSetLogDir()
    {
        $dir = sys_get_temp_dir();
        $this->ModuleOptions->setLogDir($dir);
        $this->assertEquals($dir, $this->ModuleOptions->getLogDir());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidLogDir()
    {
        $dir = sys_get_temp_dir() . uniqid();
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
}
