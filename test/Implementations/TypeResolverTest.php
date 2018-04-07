<?php

namespace De\Idrinth\TestGenerator\Test\Implementations;

use De\Idrinth\TestGenerator\Implementations\TypeResolver;
use De\Idrinth\TestGenerator\Interfaces\Type;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Use_;
use PhpParser\Node\Stmt\UseUse;
use PHPUnit\Framework\TestCase;

class TypeResolverTest extends TestCase
{
    /**
     * @return array
     */
    public function provideSimpleArrayType()
    {
        $object = new TypeResolver(new Namespace_(new Name('Example')));
        return array(
            array($object, null, 'string[]', 'string'),
            array($object, null, 'integer[]|int[]', 'integer'),
            array($object, 'array', 'integer[]|int[]', 'integer'),
            array($object, 'array', 'AClass[]|BClass[]', 'object')
        );
    }

    /**
     * @return array
     */
    public function provideSimpleType()
    {
        $object = new TypeResolver(new Namespace_(new Name('Example')));
        return array(
            array($object, null, 'int', 'integer'),
            array($object, 'float', '', 'float'),
            array($object, '', '', 'unknown'),
            array($object, null, 'array', 'array'),
            array($object, null, 'MyClass[]|int[]', 'array'),
            array($object, null, 'self', 'object'),
            array($object, null, 'float|int', 'unknown'),
            array($object, null, 'integer|int', 'integer'),
            array($object, null, 'self|static|$this', 'object'),
            array($object, 'string', 'string', 'string'),
            array($object, new Name('string'), 'string', 'string'),
            array($object, new Name('bool'), 'boolean', 'boolean')
        );
    }

    /**
     * @return array
     */
    public function provideClassType()
    {
        $object1 = new TypeResolver(new Namespace_(new Name('Base')));
        $object2 = new TypeResolver(new Namespace_(new Name('Base')));
        $object2->addUse(new Use_(array(new UseUse(new Name('NoBase\MyClass')))));
        return array(
            array($object1, null, 'MyClass', 'Base\MyClass'),
            array($object1, null, '\MyClass', 'MyClass'),
            array($object1, new Name('Any\\MyClass'), 'MyClass', 'Base\\Any\\MyClass'),
            array($object2, new Name('MyClass'), 'MyClass', 'NoBase\\MyClass'),
            array($object1, new FullyQualified('MyClass'), 'MyClass', 'MyClass'),
            array($object2, null, 'MyClass', 'NoBase\MyClass'),
            array($object2, null, '\MyClass', 'MyClass'),
            array($object2, new Name('Any\\MyClass'), 'MyClass', 'Base\\Any\\MyClass'),
        );
    }

    /**
     * @return array
     */
    public function provideClassArrayType()
    {
        $object1 = new TypeResolver(new Namespace_(new Name('Base')));
        $object2 = new TypeResolver(new Namespace_(new Name('Base')));
        $object2->addUse(new Use_(array(new UseUse(new Name('NoBase\MyClass')))));
        return array(
            array($object1, null, 'YourClass[]', 'Base\YourClass'),
            array($object2, null, 'YourClass[]', 'Base\YourClass'),
            array($object1, null, 'MyClass[]', 'Base\MyClass'),
            array($object2, null, 'MyClass[]', 'NoBase\MyClass')
        );
    }

    /**
     * @dataProvider provideSimpleType
     * @param TypeResolver $instance
     * @param mixed $parsed
     * @param string $doc
     * @param string $simple
     */
    public function testSimpleType(TypeResolver $instance, $parsed, $doc, $simple)
    {
        $this->assertSimpleType($instance->toType($parsed, $doc), $simple);
    }

    /**
     * @dataProvider provideClassType
     * @param TypeResolver $instance
     * @param mixed $parsed
     * @param string $doc
     * @param string $class
     */
    public function testClassType(TypeResolver $instance, $parsed, $doc, $class)
    {
        $this->assertClassType($instance->toType($parsed, $doc), $class);
    }

    /**
     * @dataProvider provideSimpleArrayType
     * @param TypeResolver $instance
     * @param mixed $parsed
     * @param string $doc
     * @param string $simple
     */
    public function testSimpleArrayType(TypeResolver $instance, $parsed, $doc, $simple)
    {
        $this->assertSimpleArrayType($instance->toType($parsed, $doc), $simple);
    }

    /**
     * @dataProvider provideClassArrayType
     * @param TypeResolver $instance
     * @param mixed $parsed
     * @param string $doc
     * @param string $class
     */
    public function testClassArrayType(TypeResolver $instance, $parsed, $doc, $class)
    {
        $this->assertClassArrayType($instance->toType($parsed, $doc), $class);
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
