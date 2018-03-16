<?php

namespace De\Idrinth\TestGenerator\Test;

use De\Idrinth\TestGenerator\Implementations\ClassDescriptorFactory;
use De\Idrinth\TestGenerator\Interfaces\TypeResolver;
use PhpParser\Node\Stmt\Class_;
use PHPUnit\Framework\TestCase;

class ClassDescriptorFactoryTest extends TestCase
{
    /**
     * @return array
     */
    public function provideCreate()
    {
        return array(
            array(
                new ClassDescriptorFactory($this->getMockBuilder('De\Idrinth\TestGenerator\Interfaces\MethodFactory')->getMock()),
                new Class_('class'),
                'class',
                '',
                false,
                0
            )
        );
    }

    /**
     * @return TypeResolver
     */
    private function getTypeResolverInstance()
    {
        return $this->getMockBuilder('De\Idrinth\TestGenerator\Interfaces\TypeResolver')
            ->getMock();
    }

    /**
     * @test
     * @dataProvider provideCreate
     * @param ClassDescriptorFactory $factory
     * @param Class_ $class
     * @param string $name
     * @param string $namespace
     * @param boolean $isAbstract
     * @param int $methods
     */
    public function testCreate(ClassDescriptorFactory $factory, Class_ $class, $name, $namespace, $isAbstract, $methods)
    {
        $result = $factory->create($class, $this->getTypeResolverInstance());
        $this->assertEquals($name, $result->getName());
        $this->assertEquals($namespace, $result->getNamespace());
        $this->assertEquals($isAbstract, $result->isAbstract());
        $this->assertCount($methods, $result->getMethods());
        foreach($result->getMethods() as $method) {
            $this->assertInstanceOf('De\Idrinth\TestGenerator\Interfaces\MethodDescriptor', $method);
        }
    }
}