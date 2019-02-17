<?php

namespace De\Idrinth\TestGenerator\Test\Service;

use PHPUnit\Framework\TestCase as TestCaseImplementation;
use De\Idrinth\TestGenerator\Service\ClassWriter;

/**
 * this is an automatically generated skeleton for testing ClassWriter
 * @todo actually test
 **/
class ClassWriterTest extends TestCaseImplementation
{
    /**
     * @return ClassWriter
     * @todo make sure the construction works as expected
     **/
    protected function getInstance()
    {
        return new ClassWriter(
            $this->getMockBuilder('De\Idrinth\TestGenerator\Service\NamespacePathMapper')->getMock(),
            $this->getMockBuilder('De\Idrinth\TestGenerator\Twig\Renderer')->getMock()
        );
    }
    
    /**
     * From ClassWriter
     * @test
     * @todo replace with actual tests
     * @return void
     **/
    public function testWrite()
    {
        $instance = $this->getInstance();
        $return = $instance->write(
            $this->getMockBuilder('De\Idrinth\TestGenerator\Model\ClassDescriptor')->getMock(),
            array()
        );

        $this->assertInternalType(
            'boolean',
            $return,
            'Return didn\'t match expected type boolean'
        );
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }
}
