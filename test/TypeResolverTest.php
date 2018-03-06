<?php

namespace De\Idrinth\TestGenerator\Test;

use De\Idrinth\TestGenerator\Implementations\TypeResolver;
use De\Idrinth\TestGenerator\Interfaces\Type;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Use_;
use PhpParser\Node\Stmt\UseUse;
use PHPUnit\Framework\TestCase;

class TypeResolverTest extends TestCase
{
    /**
     * @test
     */
    public function testToTypeSimple()
    {
        $object = new TypeResolver(new Namespace_());
        $this->assertSimpleType($object->toType(null, 'int'), 'integer');
        $this->assertSimpleType($object->toType('float', ''), 'float');
        $this->assertSimpleType($object->toType('', ''), 'unknown');
        $this->assertSimpleType($object->toType(null, 'array'), 'array');
        $this->assertSimpleType($object->toType(null, 'MyClass[]|int[]'), 'array');
        $this->assertSimpleType($object->toType(null, 'self'), 'object');
        $this->assertSimpleType($object->toType(null, 'float|int'), 'unknown');
        $this->assertSimpleType($object->toType(null, 'integer|int'), 'integer');
        $this->assertSimpleArrayType($object->toType(null, 'string[]'), 'string');
        $this->assertSimpleArrayType($object->toType(null, 'integer[]|int[]'), 'integer');
        $this->assertSimpleArrayType($object->toType('array', 'integer[]|int[]'), 'integer');
    }

    /**
     * @test
     */
    public function testToTypeClass()
    {
        $object = new TypeResolver(new Namespace_(new Name('Base')));
        $this->assertClassType($object->toType(null, 'MyClass'), 'Base\MyClass');
        $this->assertClassType($object->toType(null, '\MyClass'), 'MyClass');
        $this->assertClassArrayType($object->toType(null, 'YourClass[]'), 'Base\YourClass');
        $object->addUse(new Use_(array(new UseUse(new Name('NoBase\MyClass')))));
        $this->assertClassType($object->toType(null, 'MyClass'), 'NoBase\MyClass');
        $this->assertClassType($object->toType(null, '\MyClass'), 'MyClass');
        $this->assertClassArrayType($object->toType(null, 'YourClass[]'), 'NoBase\YourClass');
    }

    /**
     * @param Type $type
     * @param string $simple
     * @return void
     */
    private function assertSimpleType(Type $type, $simple)
    {
        $this->assertEquals($simple, $type->getType());
        $this->assertFalse($type->isComplex());
    }

    /**
     * @param Type $type
     * @param string $class
     * @return void
     */
    private function assertClassType(Type $type, $class)
    {
        $this->assertEquals('object', $type->getType());
        $this->assertTrue($type->isComplex());
        $this->assertEquals($class, $type->getClassName());
    }

    /**
     * @param Type $type
     * @param string $class
     * @return void
     */
    private function assertClassArrayType(Type $type, $class)
    {
        $this->assertEquals('array', $type->getType());
        $this->assertTrue($type->isComplex());
        $this->assertClassType($type->getItemType(), $class);
    }

    /**
     * @param Type $type
     * @param string $simple
     * @return void
     */
    private function assertSimpleArrayType(Type $type, $simple)
    {
        $this->assertEquals('array', $type->getType());
        $this->assertTrue($type->isComplex());
        $this->assertSimpleType($type->getItemType(), $simple);
    }
}
