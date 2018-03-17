<?php
namespace De\Idrinth\TestGenerator;

use De\Idrinth\TestGenerator\Implementations\ClassDescriptorFactory;
use De\Idrinth\TestGenerator\Implementations\ClassReader as ClassReader2;
use De\Idrinth\TestGenerator\Implementations\ClassWriter as ClassWriter2;
use De\Idrinth\TestGenerator\Implementations\Composer as Composer2;
use De\Idrinth\TestGenerator\Implementations\DocBlockParser;
use De\Idrinth\TestGenerator\Implementations\MethodFactory;
use De\Idrinth\TestGenerator\Implementations\NamespacePathMapper as NamespacePathMapper2;
use De\Idrinth\TestGenerator\Implementations\Renderer;
use De\Idrinth\TestGenerator\Interfaces\ClassReader;
use De\Idrinth\TestGenerator\Interfaces\ClassWriter;
use De\Idrinth\TestGenerator\Interfaces\Composer;
use PhpParser\Lexer;
use PhpParser\Parser;
use SplFileInfo;
use Symfony\Component\Finder\Finder;

class Controller
{
    /**
     * @var Finder
     */
    private $finder;

    /**
     * @var ClassReader
     */
    private $reader;

    /**
     * @var Composer
     */
    private $composer;

    /**
     * @var boolean
     */
    private $replace;

    /**
     * @var ClassWriter
     */
    private $writer;

    /**
     * @param Finder $finder
     * @param ClassReader $reader
     * @param ClassWriter $writer
     * @param Composer $composer
     * @param boolean $replace
     */
    public function __construct(
        Finder $finder,
        ClassReader $reader,
        ClassWriter $writer,
        Composer $composer,
        $replace
    ) {
        $this->finder = $finder;
        $this->reader = $reader;
        $this->composer = $composer;
        $this->replace = (bool) $replace;
        $this->writer = $writer;
    }

    /**
     * @return Controller
     */
    public static function init()
    {
        $opts = getopt('', array('dir:','replace'));
        $composer = new Composer2(
            new SplFileInfo((
                isset($opts['dir']) && $opts['dir'] ?
                    rtrim($opts['dir'], DIRECTORY_SEPARATOR) :
                    getcwd()
                ).DIRECTORY_SEPARATOR.'composer.json')
        );
        return new Controller(
            new Finder(),
            new ClassReader2(
                new ClassDescriptorFactory(new MethodFactory(new DocBlockParser())),
                new Parser(new Lexer(), array('throwOnError'=>true))
            ),
            new ClassWriter2(
                new NamespacePathMapper2($composer),
                new Renderer(new SplFileInfo(dirname(__DIR__).DIRECTORY_SEPARATOR.'templates'))
            ),
            $composer,
            isset($opts['replace'])
        );
    }

    /**
     * @return void
     */
    public function run()
    {
        foreach ($this->finder
            ->files()
            ->name('/\\.php[0-9]?$/i')
            ->in($this->composer->getProductionNamespacesToFolders()) as $file) {
            $this->reader->parse($file);
        }
        foreach ($this->reader->getResults() as $result) {
            $this->writer->write($result, $this->reader->getResults(), $this->replace);
        }
    }
}
