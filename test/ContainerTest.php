<?php

namespace De\Idrinth\TestGenerator\Test;

use De\Idrinth\TestGenerator\Container;
use PHPUnit\Framework\TestCase as TestCaseImplementation;

class ContainerTest extends TestCaseImplementation
{
    /**
     * @return array [input, output] for classes
     */
    public function provideGet()
    {
        return [
            [
                'De\Idrinth\TestGenerator\Interfaces\Composer',
                'De\Idrinth\TestGenerator\Implementations\Composer',
            ]
        ];
    }

    /**
     * @dataProvider provideGet
     * @param string $identifier
     * @param string $result
     */
    public function testGet($identifier, $result)
    {
        $key = 'De\Idrinth\TestGenerator\Interfaces\JsonFile.file';
        $container = Container::create()->addValue($key, dirname(__DIR__).DIRECTORY_SEPARATOR.'composer.json');
        $this->assertTrue($container->has($key));
        $this->assertFalse($container->has($identifier));
        $this->assertInstanceOf($result, $container->get($identifier));
        $this->assertTrue($container->has($result));
        $this->assertTrue($container->has($identifier));
    }
}
