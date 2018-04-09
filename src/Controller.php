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
     * @var ClassWriter
     */
    private $writer;

    /**
     * @param Finder $finder
     * @param ClassReader $reader
     * @param ClassWriter $writer
     * @param Composer $composer
     */
    public function __construct(
        Finder $finder,
        ClassReader $reader,
        ClassWriter $writer,
        Composer $composer
    ) {
        $this->finder = $finder;
        $this->reader = $reader;
        $this->composer = $composer;
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
            ->addValue('De\Idrinth\TestGenerator\Interfaces\ClassWriter.replace', isset($opts['replace']))
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
            $this->writer->write($result, $this->reader->getResults());
        }
    }
}
