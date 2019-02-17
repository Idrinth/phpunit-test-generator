<?php

namespace De\Idrinth\TestGenerator\Test\Model;

use PHPUnit\Framework\TestCase;
use De\Idrinth\TestGenerator\Model\Composer;

class ComposerTest extends TestCase
{
    /**
     * @param array $data
     * @return Composer
     **/
    protected function getInstance($data)
    {
        $json = $this
            ->getMockBuilder('De\Idrinth\TestGenerator\File\JsonFile')
            ->disableOriginalConstructor()
            ->getMock();
        $json->expects($this->once())
            ->method('getPath')
            ->with()
            ->willReturn('#base');
        $json->expects($this->once())
            ->method('getContent')
            ->with()
            ->willReturn($data);
        $processor = $this
            ->getMockBuilder('De\Idrinth\TestGenerator\Service\ComposerProcessor')
            ->disableOriginalConstructor()
            ->getMock();
        $processor->expects($this->once())
            ->method('process')
            ->with($data)
            ->willReturn(array(array('prod' => 'a'), array('dev' => 'b'), 'test-class'));
        return new Composer($json, $processor);
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
                )
            ),
            array(
                array(
                    'require-dev'=>array('phpunit/phpunit' => '^4.8'),
                    'autoload'=>array('psr-4'=>array('MyTest' => 'abc')),
                    'autoload-dev'=>array('psr-0'=>array('MyTestTest' => 'abcd'))
                )
            ),
        );
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
            array('prod' => 'a'),
            $instance->getProductionNamespacesToFolders(),
            'Return didn\'t match expected array'
        );
        $this->assertEquals(
            array('dev' => 'b'),
            $instance->getDevelopmentNamespacesToFolders(),
            'Return didn\'t match expected array'
        );
        $this->assertEquals('test-class', $instance->getTestClass());
    }
}
