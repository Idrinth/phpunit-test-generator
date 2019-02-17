<?php

namespace De\Idrinth\TestGenerator\Test\Factory;

use PHPUnit\Framework\TestCase as TestCaseImplementation;
use De\Idrinth\TestGenerator\Factory\ClassDescriptorFactory;

/**
 * this is an automatically generated skeleton for testing ClassDescriptorFactory
 * @todo actually test
 **/
class ClassDescriptorFactoryTest extends TestCaseImplementation
{
    /**
     * @return ClassDescriptorFactory
     * @todo make sure the construction works as expected
     **/
    protected function getInstance()
    {
        return new ClassDescriptorFactory(
            $this->getMockBuilder('De\Idrinth\TestGenerator\Factory\MethodFactory')->getMock()
        );
    }
    
    /**
     * From ClassDescriptorFactory
     * @test
     * @todo replace with actual tests
     * @return void
     **/
    public function testCreate()
    {
        $instance = $this->getInstance();
        $return = $instance->create(
            $this->getMockBuilder('PhpParser\Node\Stmt\Class_')->getMock(),
            $this->getMockBuilder('De\Idrinth\TestGenerator\Service\TypeResolver')->getMock()
        );

        $this->assertInternalType(
            'object',
            $return,
            'Return didn\'t match expected type object'
        );
        $this->assertInstanceOf(
            'De\Idrinth\TestGenerator\Model\ClassDescriptor',
            $return,
            'Return didn\'t match expected instance De\Idrinth\TestGenerator\Model\ClassDescriptor'
        );
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }
}
