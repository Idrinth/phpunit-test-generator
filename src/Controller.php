<?php
namespace De\Idrinth\TestGenerator;

use De\Idrinth\TestGenerator\Interfaces\ClassReader;
use De\Idrinth\TestGenerator\Interfaces\ClassWriter;
use De\Idrinth\TestGenerator\Implementations\ClassReader as ClassReader2;
use De\Idrinth\TestGenerator\Implementations\ClassWriter as ClassWriter2;
use De\Idrinth\TestGenerator\Implementations\NamespacePathMapper as NamespacePathMapper2;
use Symfony\Component\Finder\Finder;
use SplFileInfo;

class Controller
{
    private $finder;
    private $reader;
    private $rootDir;
    private $replace;
    private $writer;
    public function __construct(Finder $finder, ClassReader $reader, ClassWriter $writer, SplFileInfo $rootDir, $replace)
    {
        $this->finder = $finder;
        $this->reader = $reader;
        $this->rootDir = $rootDir;
        $this->replace = (bool) $replace;
        $this->writer = $writer;
    }
    public static function init()
    {
        $opts = getopt('', array('dir:','replace'));
        $dir = new SplFileInfo(isset($opts['dir']) && $opts['dir'] ? rtrim($opts['dir'], DIRECTORY_SEPARATOR) : getcwd());
        return new self(
            new Finder(),
            new ClassReader2(),
            new ClassWriter2(new NamespacePathMapper2(new SplFileInfo($dir.DIRECTORY_SEPARATOR.'composer.json'))),
            $dir,
            isset($opts['replace'])
        );
    }
    public function run() {
        foreach ($this->finder
            ->files()
            ->name('/\\.php[0-9]?$/i')
            ->in($this->rootDir.DIRECTORY_SEPARATOR.'src') as $file) {
            $this->reader->parse($file);
        }
        foreach ($this->reader->getResults() as $result) {
            $this->writer->write($result, $this->reader->getResults(), $this->replace);
        }
    }
}