<?php

namespace De\Idrinth\TestGenerator\Test;

use De\Idrinth\TestGenerator\Implementations\TypeResolver;
use PhpParser\Node\Stmt\Namespace_;
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
    }
}
