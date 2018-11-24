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
use ReflectionClass;

class TypeResolverTest extends TestCase
{
    /**
     * @return array
     */
    public function provideSimpleArrayType()
    {
        $object = new TypeResolver(new Namespace_(new Name('Example')));
        return [
            [$object, null, 'string[]', 'string'],
            [$object, null, 'integer[]|int[]', 'integer'],
            [$object, 'array', 'integer[]|int[]', 'integer'],
            [$object, 'array', 'AClass[]|BClass[]', 'object']
        ];
    }

    /**
     * @return array
     */
    public function provideSimpleType()
    {
        $object = new TypeResolver(new Namespace_(new Name('Example')));
        return [
            [$object, null, 'int', 'integer'],
            [$object, 'float', '', 'float'],
            [$object, '', '', 'unknown'],
            [$object, null, 'array', 'array'],
            [$object, null, 'MyClass[]|int[]', 'array'],
            [$object, null, 'self', 'object'],
            [$object, null, 'float|int', 'unknown'],
            [$object, null, 'integer|int', 'integer'],
            [$object, null, 'self|static|$this', 'object'],
            [$object, 'string', 'string', 'string'],
            [$object, new Name('string'), 'string', 'string'],
            [$object, new Name('bool'), 'boolean', 'boolean']
        ];
    }

    /**
     * @return array
     */
    public function provideClassType()
    {
        $object1 = new TypeResolver(new Namespace_(new Name('Base')));
        $object2 = new TypeResolver(new Namespace_(new Name('Base')));
        $object2->addUse(new Use_([new UseUse(new Name('NoBase\MyClass'))]));
        return [
            [$object1, null, 'MyClass', 'Base\MyClass'],
            [$object1, null, '\MyClass', 'MyClass'],
            [$object1, new Name('Any\\MyClass'), 'MyClass', 'Base\\Any\\MyClass'],
            [$object2, new Name('MyClass'), 'MyClass', 'NoBase\\MyClass'],
            [$object1, new FullyQualified('MyClass'), 'MyClass', 'MyClass'],
            [$object2, null, 'MyClass', 'NoBase\MyClass'],
            [$object2, null, '\MyClass', 'MyClass'],
            [$object2, new Name('Any\\MyClass'), 'MyClass', 'Base\\Any\\MyClass'],
        ];
    }

    /**
     * @return array
     */
    public function provideClassArrayType()
    {
        $object1 = new TypeResolver(new Namespace_(new Name('Base')));
        $object2 = new TypeResolver(new Namespace_(new Name('Base')));
        $object2->addUse(new Use_([new UseUse(new Name('NoBase\MyClass'))]));
        return [
            [$object1, null, 'YourClass[]', 'Base\YourClass'],
            [$object2, null, 'YourClass[]', 'Base\YourClass'],
            [$object1, null, 'MyClass[]', 'Base\MyClass'],
            [$object2, null, 'MyClass[]', 'NoBase\MyClass']
        ];
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

    /**
     * @test
     */
    public function testEmptyListReduction()
    {
        $instance = new TypeResolver(
            $this
                ->getMockBuilder('PhpParser\Node\Stmt\Namespace_')
                ->disableOriginalConstructor()
                ->getMock()
        );
        $class = new ReflectionClass($instance);
        $method = $class->getMethod('typeListToType');
        $method->setAccessible(true);
        $this->assertInstanceOf(
            'De\Idrinth\TestGenerator\Implementations\Type\UnknownType',
            $method->invoke($instance, [])
        );
    }

    /**
     * @test
     */
    public function testWeirdTypeToType()
    {
        $instance = new TypeResolver(
            $this
                ->getMockBuilder('PhpParser\Node\Stmt\Namespace_')
                ->disableOriginalConstructor()
                ->getMock()
        );
        $class = new ReflectionClass($instance);
        $method = $class->getMethod('typeToType');
        $method->setAccessible(true);
        $this->assertNull($method->invoke($instance, '_'));
    }
}
