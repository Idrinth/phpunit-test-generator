<?php

namespace De\Idrinth\TestGenerator\Test;

use De\Idrinth\TestGenerator\Controller;
use De\Idrinth\TestGenerator\Test\Mock\GetCwd;
use PHPUnit\Framework\TestCase as TestCaseImplementation;
use Symfony\Component\Finder\Finder;

require_once(__DIR__.DIRECTORY_SEPARATOR.'Mock'.DIRECTORY_SEPARATOR.'getcwd_function.php');

class ControllerTest extends TestCaseImplementation
{
    /**
     * @return Controller
     * @todo make sure the construction works as expected
     **/
    protected function getInstance()
    {
        $composer = $this->getMockBuilder('De\Idrinth\TestGenerator\Interfaces\Composer')->getMock();
        $composer->expects($this->once())->method('getProductionNamespacesToFolders')->willReturn(array(__DIR__));
        $reader = $this->getMockBuilder('De\Idrinth\TestGenerator\Interfaces\ClassReader')->getMock();
        $reader->expects($this->once())->method('getResults')->willReturn(array());
        return new Controller(
            new Finder(),
            $reader,
            $this->getMockBuilder('De\Idrinth\TestGenerator\Interfaces\ClassWriter')->getMock(),
            $composer,
            false
        );
    }

    /**
     * From Controller
     * @test
     * @expectedException \InvalidArgumentException
     **/
    public function testInitThrowsInvalidArgumentException()
    {
        $cwd = new GetCwd(__DIR__);
        Controller::init();
        unset($cwd);
    }

    /**
     * From Controller
     * @test
     **/
    public function testInit()
    {
        $cwd = new GetCwd(dirname(__DIR__));
        $this->assertInstanceOf(
            'De\Idrinth\TestGenerator\Controller',
            Controller::init(),
            'Return didn\'t match expected Controller'
        );
        unset($cwd);
    }

    /**
     * From Controller
     * @test
     **/
    public function testRun()
    {
        $instance = $this->getInstance();
        $this->assertInternalType(
            'null',
            $instance->run(),
            'Return didn\'t match expected type null'
        );
    }
}
