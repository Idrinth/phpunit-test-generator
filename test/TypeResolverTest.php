<?php

namespace De\Idrinth\TestGenerator\Test;

use De\Idrinth\TestGenerator\Implementations\TypeResolver;
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
    public function testToType()
    {
        $object = new TypeResolver(new Namespace_());
        $this->assertEquals('array', $object->toType(null, 'array')->getType());
        $this->assertEquals('array', $object->toType(null, 'string[]')->getType());
        $this->assertEquals('array', $object->toType(null, 'MyClass[]|int[]')->getType());
        $this->assertEquals('integer', $object->toType(null, 'int')->getType());
        $this->assertEquals('array', $object->toType(null, 'MyClass[]')->getType());
        $this->assertInstanceOf(
            'De\Idrinth\TestGenerator\Implementations\Type\ArrayType',
            $object->toType(null, 'MyClass[]')
        );
        $this->assertEquals(
            'object',
            $object->toType(null, 'MyClass[]')->getItemType()->getType()
        );
        $this->assertEquals(
            'MyClass',
            $object->toType(null, 'MyClass[]')->getItemType()->getClassName()
        );
        $object2 = new TypeResolver(new Namespace_(new Name('Base')));
        $this->assertEquals(
            'Base\MyClass',
            $object2->toType(null, 'MyClass[]')->getItemType()->getClassName()
        );
        $object3 = new TypeResolver(new Namespace_(new Name('Base')));
        $object3->addUse(new Use_(array(new UseUse(new Name('NoBase\MyClass')))));
        $this->assertEquals(
            'NoBase\MyClass',
            $object3->toType(null, 'MyClass[]')->getItemType()->getClassName()
       );
    }
}
