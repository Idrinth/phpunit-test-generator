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
        return [
            [
                [
                    'require-dev'=>['phpunit/phpunit' => '^4.8']
                ],
                [],
                [],
                ''
            ],
            [
                [
                    'require-dev'=>['phpunit/phpunit' => '^4.8']
                ],
                [],
                [],
                'hello'
            ],
            [
                [
                    'require-dev'=>['phpunit/phpunit' => '^4.8'],
                    "autoload" => [],
                    "autoload-dev" => [],
                ],
                [],
                [],
                'hello'
            ],
            [
                [
                    'require'=>['phpunit/phpunit' => '^4.8'],
                    "autoload" => [],
                    "autoload-dev" => [],
                ],
                [],
                [],
                'hello'
            ],
            [
                [
                    'require-dev'=>['phpunit/phpunit' => '^4.8'],
                    'autoload'=>['psr-4'=>['MyTest' => 'abc']],
                    'autoload-dev'=>['psr-0'=>['MyTestTest' => 'abcd']]
                ],
                ['MyTest' => '#base'.DIRECTORY_SEPARATOR.'abc'],
                ['MyTestTest' => '#base'.DIRECTORY_SEPARATOR.'abcd'],
                ''
            ],
            [
                [
                    'require-dev'=>['phpunit/phpunit' => '^4.8'],
                    'autoload'=>['psr-4'=>['MyTest' => 'abc']],
                    'autoload-dev'=>['psr-0'=>['MyTestTest' => 'abcd']]
                ],
                ['MyTest' => '#base'.DIRECTORY_SEPARATOR.'abc'],
                ['MyTestTest' => '#base'.DIRECTORY_SEPARATOR.'hello'.DIRECTORY_SEPARATOR.'abcd'],
                'hello'
            ],
        ];
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
            [$prod, $dev, 'test-class'],
            $this->getInstance($output)->process($data, '#base')
        );
    }
}
