<?php

namespace De\Idrinth\TestGenerator\Test;

use De\Idrinth\TestGenerator\Container;
use PHPUnit\Framework\TestCase as TestCaseImplementation;

class ContainerTest extends TestCaseImplementation
{
    /**
     * @return array [input, output] for classes
     */
    public function provideGet(): array
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
    public function testGet(string $identifier, string $result): void
    {
        $key = 'De\Idrinth\TestGenerator\Interfaces\JsonFile.file';
        $container = (new Container())->addValue($key, dirname(__DIR__).DIRECTORY_SEPARATOR.'composer.json');
        $this->assertTrue($container->has($key));
        $this->assertFalse($container->has($identifier));
        $this->assertInstanceOf($result, $container->get($identifier));
        $this->assertTrue($container->has($result));
        $this->assertTrue($container->has($identifier));
    }
}
