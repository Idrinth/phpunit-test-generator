<?php declare (strict_types=1);

namespace De\Idrinth\TestGenerator;

use De\Idrinth\TestGenerator\Interfaces\ClassReader;
use De\Idrinth\TestGenerator\Interfaces\ClassWriter;
use De\Idrinth\TestGenerator\Interfaces\Composer;
use Symfony\Component\Finder\Finder;

final class Controller
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
    public static function init(): Controller
    {
        list($composer, $mode, $output) = self::getParams();
        return (new Container())
            ->addValue('SplFileInfo.file_name', dirname(__DIR__).DIRECTORY_SEPARATOR.'templates')
            ->addValue('PhpParser\Parser.options', ['throwOnError' => true])
            ->addValue('De\Idrinth\TestGenerator\Interfaces\JsonFile.file', $composer)
            ->addValue('De\Idrinth\TestGenerator\Interfaces\NamespacePathMapper.mode', $mode)
            ->addValue('De\Idrinth\TestGenerator\Interfaces\ComposerProcessor.output', $output)
            ->get(__CLASS__);
    }

    /**
     * processes getopt
     * @return array [$composer, $mode, $output]
     */
    private static function getParams(): array
    {
        $opts = getopt('', ['dir:','replace','output:','mode:']);
        $dir = (isset($opts['dir']) && $opts['dir'] ? rtrim($opts['dir'], '\\/') : getcwd());
        $modes = ['replace', 'move', 'skip'];
        return [
            $dir.DIRECTORY_SEPARATOR.'composer.json',
            isset($opts['mode']) && in_array($opts['mode'], $modes) ?
                $opts['mode'] :
                isset($opts['replace']) ? 'replace' : 'move',
            isset($opts['output']) ? $opts['output'] : ''
        ];
    }

    /**
     * @return void
     */
    public function run(): void
    {
        echo "\nRead:\n";
        foreach ($this->finder
            ->files()
            ->name('/\\.php[0-9]?$/i')
            ->in($this->composer->getProductionNamespacesToFolders()) as $file) {
            $this->reader->parse($file);
            echo ".";
        }
        echo "\nWrite:\n";
        foreach ($this->reader->getResults() as $result) {
            $this->writer->write($result, $this->reader->getResults());
            echo ".";
        }
        echo "\n\n";
    }
}
