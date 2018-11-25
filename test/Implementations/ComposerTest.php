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
        $json->expects($this->once())
            ->method('getPath')
            ->with()
            ->willReturn('#base');
        $json->expects($this->once())
            ->method('getContent')
            ->with()
            ->willReturn($data);
        $processor = $this->getMockBuilder('De\Idrinth\TestGenerator\Interfaces\ComposerProcessor')->getMock();
        $processor->expects($this->once())
            ->method('process')
            ->with($data)
            ->willReturn([['prod' => 'a'], ['dev' => 'b'], 'test-class']);
        return new Composer($json, $processor);
    }

    /**
     * @return array
     */
    public function provideGetNamespacesToFolders()
    {
        return [
            [
                [
                    'require-dev'=>['phpunit/phpunit' => '^4.8']
                ]
            ],
            [
                [
                    'require-dev'=>['phpunit/phpunit' => '^4.8'],
                    'autoload'=>['psr-4'=>['MyTest' => 'abc']],
                    'autoload-dev'=>['psr-0'=>['MyTestTest' => 'abcd']]
                ]
            ],
        ];
    }

    /**
     * From Composer
     * @test
     * @dataProvider provideGetNamespacesToFolders
     * @param array $composer
     **/
    public function testGetNamespacesToFolders(array $composer)
    {
        $instance = $this->getInstance($composer);
        $this->assertInternalType(
            'array',
            $instance->getProductionNamespacesToFolders(),
            'Return didn\'t match expected type array'
        );
        $this->assertEquals(
            ['prod' => 'a'],
            $instance->getProductionNamespacesToFolders(),
            'Return didn\'t match expected array'
        );
        $this->assertEquals(
            ['dev' => 'b'],
            $instance->getDevelopmentNamespacesToFolders(),
            'Return didn\'t match expected array'
        );
        $this->assertEquals('test-class', $instance->getTestClass());
    }
}
