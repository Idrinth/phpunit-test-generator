<?php

namespace De\Idrinth\TestGenerator\Test\Implementations;

use De\Idrinth\TestGenerator\Implementations\Renderer;
use De\Idrinth\TestGenerator\Interfaces\Composer;
use PHPUnit\Framework\TestCase;
use SplFileInfo;

class RendererTest extends TestCase
{
    /**
     * @return array
     */
    public function provideRender()
    {
        return [
            [
                'class',
                "<?php\n\nnamespace ;\n\nuse  as TestCaseImplementation;\nuse \;\n\n/**\n"
                . " * this is an automatically generated skeleton for testing \n "
                . "* @todo actually test\n **/\nclass Test extends TestCaseImplementation\n{"
                . "\n    /**\n     * @return \n     * @todo make sure the construction works"
                . " as expected\n     **/\n    protected function getInstance()\n    {"
                . "\n        return new ();\n    }\n    }\n",
                $this->getMockedComposer('')
            ],
            ['function', '()', $this->getMockedComposer('')],
            ['test-functions', "", $this->getMockedComposer('')],
            [
                'type-tests',
                "\n\$this->assertInternalType(\n    '',\n    ,"
                . "\n    'Return didn\'t match expected type '\n);",
                $this->getMockedComposer('')
            ],
            ['type2value', 'null/* @todo unknown, please check */', $this->getMockedComposer('')],
            [
                'class',
                "<?php\n\nnamespace ;\n\nuse Example as TestCaseImplementation;\nuse \;\n\n/**\n"
                . " * this is an automatically generated skeleton for testing \n "
                . "* @todo actually test\n **/\nclass Test extends TestCaseImplementation\n{"
                . "\n    /**\n     * @return \n     * @todo make sure the construction works"
                . " as expected\n     **/\n    protected function getInstance()\n    {"
                . "\n        return new ();\n    }\n    }\n",
                $this->getMockedComposer('Example')
            ],
            ['function', '()', $this->getMockedComposer('Example')],
            ['test-functions', "", $this->getMockedComposer('Example')],
            [
                'type-tests',
                "\n\$this->assertInternalType(\n    '',\n    ,"
                . "\n    'Return didn\'t match expected type '\n);",
                $this->getMockedComposer('Example')
            ],
            ['type2value', 'null/* @todo unknown, please check */', $this->getMockedComposer('Example')],
        ];
    }

    /**
     * @return Composer
     */
    private function getMockedComposer($class)
    {
        $environment = $this->getMockBuilder('De\Idrinth\TestGenerator\Interfaces\Composer')
            ->getMock();
        $environment->expects($this->exactly(1))
            ->method('getTestClass')
            ->willReturn($class);
        return $environment;
    }

    /**
     * @param string $template
     * @param string $expected
     * @param Composer $composer
     * @test
     * @dataProvider provideRender
     */
    public function testRender($template, $expected, Composer $composer)
    {
        $renderer = new Renderer(
            new SplFileInfo(dirname(dirname(__DIR__)).DIRECTORY_SEPARATOR.'templates'),
            $composer
        );
        $this->assertEquals(
            $expected,
            $renderer->render($template.'.twig')
        );
    }
}
