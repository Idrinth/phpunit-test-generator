<?php

namespace De\Idrinth\TestGenerator\Test\Implementations;

use PHPUnit\Framework\TestCase as TestCaseImplementation;
use De\Idrinth\TestGenerator\Implementations\Composer;

class ComposerTest extends TestCaseImplementation
{
    /**
     * @param array $data
     * @return Composer
     **/
    protected function getInstance($data)
    {
        $json = $this->getMockBuilder('De\Idrinth\TestGenerator\Interfaces\JsonFile')->getMock();
        $json->expects($this->exactly(2))
            ->method('getPath')
            ->willReturn('#base');
        $json->expects($this->once())
            ->method('getContent')
            ->willReturn($data);
        $decider = $this->getMockBuilder('De\Idrinth\TestGenerator\Interfaces\TestClassDecider')->getMock();
        $decider->expects($this->once())
            ->method('get')
            ->willReturn('test-class');
        return new Composer($json, $decider);
    }

    /**
     * @return array
     */
    public function provideGetNamespacesToFolders()
    {
        return array(
            array(
                array(
                    'require-dev'=>array('phpunit/phpunit' => '^4.8')
                ),
                array(),
                array(),
            ),
            array(
                array(
                    'require-dev'=>array('phpunit/phpunit' => '^4.8'),
                    'autoload'=>array('psr-4'=>array('MyTest' => 'abc')),
                    'autoload-dev'=>array('psr-0'=>array('MyTestTest' => 'abcd'))
                ),
                array('MyTest' => '#base'.DIRECTORY_SEPARATOR.'abc'),
                array('MyTestTest' => '#base'.DIRECTORY_SEPARATOR.'abcd'),
            ),
        );
    }

    /**
     * From Composer
     * @test
     * @dataProvider provideGetNamespacesToFolders
     **/
    public function testGetNamespacesToFolders($composer, $prod, $dev)
    {
        $instance = $this->getInstance($composer);
        $this->assertInternalType(
            'array',
            $instance->getProductionNamespacesToFolders(),
            'Return didn\'t match expected type array'
        );
        $this->assertEquals(
            $prod,
            $instance->getProductionNamespacesToFolders(),
            'Return didn\'t match expected array'
        );
        $this->assertEquals(
            $dev,
            $instance->getDevelopmentNamespacesToFolders(),
            'Return didn\'t match expected array'
        );
        $this->assertEquals('test-class', $instance->getTestClass());
    }
}
