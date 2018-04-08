<?php
namespace De\Idrinth\TestGenerator;

use De\Idrinth\TestGenerator\Interfaces\ClassReader;
use De\Idrinth\TestGenerator\Interfaces\ClassWriter;
use De\Idrinth\TestGenerator\Interfaces\Composer;
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
        return Container::create()
            ->addValue(
                'SplFileInfo.file_name',
                dirname(__DIR__).DIRECTORY_SEPARATOR.'templates'
            )
            ->addValue(
                'PhpParser\Parser.options',
                array('throwOnError'=>true)
            )
            ->addValue(
                'De\Idrinth\TestGenerator\Interfaces\JsonFile.file',
                (isset($opts['dir']) && $opts['dir'] ?
                        rtrim($opts['dir'], DIRECTORY_SEPARATOR) :
                        getcwd()
                    ).DIRECTORY_SEPARATOR.'composer.json'
            )
            ->addValue(__CLASS__.'.replace', isset($opts['replace']))
            ->get(__CLASS__);
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
