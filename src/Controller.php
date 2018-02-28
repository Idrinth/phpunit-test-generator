<?php
namespace De\Idrinth\TestGenerator;

use De\Idrinth\TestGenerator\Interfaces\ClassReader;
use De\Idrinth\TestGenerator\Interfaces\ClassWriter;
use De\Idrinth\TestGenerator\Interfaces\Composer;
use De\Idrinth\TestGenerator\Implementations\ClassReader as ClassReader2;
use De\Idrinth\TestGenerator\Implementations\ClassWriter as ClassWriter2;
use De\Idrinth\TestGenerator\Implementations\Composer as Composer2;
use De\Idrinth\TestGenerator\Implementations\NamespacePathMapper as NamespacePathMapper2;
use Symfony\Component\Finder\Finder;
use SplFileInfo;

class Controller
{
    private $finder;
    private $reader;
    private $composer;
    private $replace;
    private $writer;
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
    public static function init()
    {
        $opts = getopt('', array('dir:','replace'));
        $composer = new Composer2(
            new SplFileInfo((
                isset($opts['dir']) && $opts['dir'] ?
                    rtrim($opts['dir'], DIRECTORY_SEPARATOR) :
                    getcwd()
                ).DIRECTORY_SEPARATOR.'composer.json'
            )
        );
        return new self(
            new Finder(),
            new ClassReader2(),
            new ClassWriter2(new NamespacePathMapper2($composer)),
            $composer,
            isset($opts['replace'])
        );
    }
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
