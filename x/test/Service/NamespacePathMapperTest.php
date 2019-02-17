<?php

namespace De\Idrinth\TestGenerator\Test\Service;

use PHPUnit\Framework\TestCase as TestCaseImplementation;
use De\Idrinth\TestGenerator\Service\NamespacePathMapper;

/**
 * this is an automatically generated skeleton for testing NamespacePathMapper
 * @todo actually test
 **/
class NamespacePathMapperTest extends TestCaseImplementation
{
    /**
     * @return NamespacePathMapper
     * @todo make sure the construction works as expected
     **/
    protected function getInstance()
    {
        return new NamespacePathMapper(
            $this->getMockBuilder('De\Idrinth\TestGenerator\Model\Composer')->getMock(),
            ''
        );
    }
    
    /**
     * From NamespacePathMapper
     * @test
     * @todo replace with actual tests
     * @return void
     **/
    public function testGetTestNamespaceForNamespace()
    {
        $instance = $this->getInstance();
        $return = $instance->getTestNamespaceForNamespace(
            ''
        );

        $this->assertInternalType(
            'string',
            $return,
            'Return didn\'t match expected type string'
        );
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * From NamespacePathMapper
     * @test
     * @todo replace with actual tests
     * @return void
     **/
    public function testGetTestFileForNamespacedClass()
    {
        $instance = $this->getInstance();
        $return = $instance->getTestFileForNamespacedClass(
            ''
        );

        $this->assertInternalType(
            'object',
            $return,
            'Return didn\'t match expected type object'
        );
        $this->assertInstanceOf(
            'De\Idrinth\TestGenerator\File\TargetPhpFile',
            $return,
            'Return didn\'t match expected instance De\Idrinth\TestGenerator\File\TargetPhpFile'
        );
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }
}
