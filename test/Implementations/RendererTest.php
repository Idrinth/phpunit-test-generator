<?php

namespace De\Idrinth\TestGenerator\Test\Implementations;

use De\Idrinth\TestGenerator\Implementations\Renderer;
use PHPUnit\Framework\TestCase;
use SplFileInfo;

class RendererTest extends TestCase
{
    /**
     * @return array
     */
    public function provideRender()
    {
        return array(
            array(
                'class',
                "<?php\n\nnamespace ;\n\nuse  as TestCaseImplementation;\nuse \;\n\n/**\n"
                . " * this is an automatically generated skeleton for testing \n "
                . "* @todo actually test\n **/\nclass Test extends TestCaseImplementation\n{"
                . "\n    /**\n     * @return \n     * @todo make sure the construction works"
                . " as expected\n     **/\n    protected function getInstance()\n    {"
                . "\n        return new ();\n    }\n}\n"
            ),
            array('function', '()'),
            array('test-functions', ""),
            array(
                'type-tests',
                "\n\$this->assertInternalType(\n    '',\n    ,"
                . "\n    'Return didn\'t match expected type '\n);\n"
            ),
            array('type2value', 'null')
        );
    }

    /**
     * @param string $template
     * @param string $expected
     * @test
     * @dataProvider provideRender
     */
    public function testRender($template, $expected)
    {
        $renderer = new Renderer(new SplFileInfo(dirname(dirname(__DIR__)).DIRECTORY_SEPARATOR.'templates'));
        $this->assertEquals($expected, $renderer->render($template.'.twig'));
    }
}
