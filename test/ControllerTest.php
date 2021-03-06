<?php

namespace De\Idrinth\TestGenerator\Test;

use De\Idrinth\TestGenerator\Controller;
use De\Idrinth\TestGenerator\Test\Mock\GetCwd;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Finder\Finder;

class ControllerTest extends TestCase
{
    /**
     * @param int $returnNumber
     * @return Controller
     **/
    protected function getInstance($returnNumber)
    {
        $item = $this
            ->getMockBuilder('De\Idrinth\TestGenerator\Model\ClassDescriptor')
            ->disableOriginalConstructor()
            ->getMock();
        $list = $returnNumber?array_fill(0, $returnNumber, $item):array();
        $composer = $this
            ->getMockBuilder('De\Idrinth\TestGenerator\Model\Composer')
            ->disableOriginalConstructor()
            ->getMock();
        $composer->expects($this->once())
            ->method('getProductionNamespacesToFolders')
            ->with()
            ->willReturn(array(__DIR__));
        $reader = $this
            ->getMockBuilder('De\Idrinth\TestGenerator\Service\ClassReader')
            ->disableOriginalConstructor()
            ->getMock();
        $reader->expects($this->exactly(1+$returnNumber))->method('getResults')->with()->willReturn($list);
        $writer = $this
            ->getMockBuilder('De\Idrinth\TestGenerator\Service\ClassWriter')
            ->disableOriginalConstructor()
            ->getMock();
        $writer->expects($this->exactly($returnNumber))
            ->method('write')
            ->with($item);
        return new Controller(new Finder(), $reader, $writer, $composer);
    }

    /**
     * From Controller
     * @test
     * @expectedException InvalidArgumentException
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
     * @return array
     */
    public function provideRun()
    {
        return array(array(0), array(2), array(7));
    }

    /**
     * From Controller
     * @dataProvider provideRun
     * @test
     **/
    public function testRun($returnNumber)
    {
        $instance = $this->getInstance($returnNumber);
        $this->assertInternalType(
            'null',
            $instance->run(),
            'Return didn\'t match expected type null'
        );
    }
}
