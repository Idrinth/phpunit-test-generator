<?php

namespace De\Idrinth\TestGenerator\Test\Twig;

use PHPUnit\Framework\TestCase as TestCaseImplementation;
use De\Idrinth\TestGenerator\Twig\Renderer;

/**
 * this is an automatically generated skeleton for testing Renderer
 * @todo actually test
 **/
class RendererTest extends TestCaseImplementation
{
    /**
     * @return Renderer
     * @todo make sure the construction works as expected
     **/
    protected function getInstance()
    {
        return new Renderer(
            $this->getMockBuilder('SplFileInfo')->getMock(),
            $this->getMockBuilder('De\Idrinth\TestGenerator\Model\Composer')->getMock()
        );
    }
    
    /**
     * From Renderer
     * @test
     * @todo replace with actual tests
     * @return void
     **/
    public function testRender()
    {
        $instance = $this->getInstance();
        $return = $instance->render(
            '',
            array()
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
}
