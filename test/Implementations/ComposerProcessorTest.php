<?php

namespace De\Idrinth\TestGenerator\Test\Implementations;

use De\Idrinth\TestGenerator\Implementations\ComposerProcessor;

class ComposerProcessorTest
{
    /**
     * @param string $output
     * @return ComposerProcessor
     **/
    protected function getInstance($output)
    {
        $decider = $this->getMockBuilder('De\Idrinth\TestGenerator\Interfaces\TestClassDecider')->getMock();
        $decider->expects($this->once())
            ->method('get')
            ->willReturn('test-class');
        return new ComposerProcessor($decider, $output);
    }

    /**
     * @return array
     */
    public function provideProcess()
    {
        return array(
            array(
                array(
                    'require-dev'=>array('phpunit/phpunit' => '^4.8')
                ),
                array(),
                array(),
                ''
            ),
            array(
                array(
                    'require-dev'=>array('phpunit/phpunit' => '^4.8')
                ),
                array(),
                array(),
                'hello'
            ),
            array(
                array(
                    'require-dev'=>array('phpunit/phpunit' => '^4.8'),
                    "autoload" => array(),
                    "autoload-dev" => array(),
                ),
                array(),
                array(),
                'hello'
            ),
            array(
                array(
                    'require'=>array('phpunit/phpunit' => '^4.8'),
                    "autoload" => array(),
                    "autoload-dev" => array(),
                ),
                array(),
                array(),
                'hello'
            ),
            array(
                array(
                    'require-dev'=>array('phpunit/phpunit' => '^4.8'),
                    'autoload'=>array('psr-4'=>array('MyTest' => 'abc')),
                    'autoload-dev'=>array('psr-0'=>array('MyTestTest' => 'abcd'))
                ),
                array('MyTest' => '#base'.DIRECTORY_SEPARATOR.'abc'),
                array('MyTestTest' => '#base'.DIRECTORY_SEPARATOR.'abcd'),
                ''
            ),
            array(
                array(
                    'require-dev'=>array('phpunit/phpunit' => '^4.8'),
                    'autoload'=>array('psr-4'=>array('MyTest' => 'abc')),
                    'autoload-dev'=>array('psr-0'=>array('MyTestTest' => 'abcd'))
                ),
                array('MyTest' => '#base'.DIRECTORY_SEPARATOR.'abc'),
                array('MyTestTest' => '#base'.DIRECTORY_SEPARATOR.'hello'.DIRECTORY_SEPARATOR.'abcd'),
                'hello'
            ),
        );
    }

    /**
     * @dataProvider provideProcess
     * @test
     * @param array $data
     * @param array $prod
     * @param array $dev
     * @param type $output
     */
    public function testProcess(array $data, array $prod, array $dev, $output)
    {
        $this->assertEquals(
            array($prod, $dev, 'test-class'),
            $this->getInstance($output)->process($data, '#base')
        );
    }
}
