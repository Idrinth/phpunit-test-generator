<?php

namespace De\Idrinth\TestGenerator\Implementations;

use SplFileInfo;
use De\Idrinth\TestGenerator\Interfaces\Composer as ComposerInterface;

class NamespacePathMapper implements \De\Idrinth\TestGenerator\Interfaces\NamespacePathMapper
{
    /**
     * Mapping Namespace => Folder
     * @var string[]
     */
    private $folders = array();

    /**
     * @var string
     */
    private $mode;

    /**
     * @param ComposerInterface $composer
     * @param string $mode
     */
    public function __construct(ComposerInterface $composer, $mode)
    {
        $this->mode = $mode;
        foreach ($composer->getDevelopmentNamespacesToFolders() as $namespace => $folder) {
            $this->folders[$namespace] = $folder;
        }
        foreach ($composer->getProductionNamespacesToFolders() as $namespace => $folder) {
            $this->folders[$namespace] = $folder;
        }
    }

    /**
     * Builds a Namespace by inserting a new part
     * @param string[] $parts
     * @param string $part
     * @param int $position
     * @return string
     */
    private function toNamespace($parts, $part, $position)
    {
        $original = array();
        array_splice($original, 0, 0, $parts);
        array_splice($original, $position, 0, array($part));
        return implode("\\", $original);
    }

    /**
     * @param string $namespace
     * @return string[] known part, unknown part
     */
    private function splitIntoMain($namespace)
    {
        $matches = array();
        foreach (array_keys($this->folders) as $nsKey) {
            if (preg_match('/^'.preg_quote($nsKey).'/', $namespace)) {
                $matches[] = $nsKey;
            }
        }
        if (!count($matches)) {
            throw new \UnexpectedValueException("$namespace can't be found.");
        }
        usort($matches, function ($first, $second) {
            return strlen($second)-strlen($first);
        });
        return array(
            trim($matches[0], '\\'),
            trim(str_replace($matches[0], '', $namespace), '\\')
        );
    }

    /**
     * @param string $namespace
     * @return string
     */
    public function getTestNamespaceForNamespace($namespace)
    {
        list($prepend, $append) = $this->splitIntoMain($namespace);
        $parts = explode("\\", trim($prepend, '\\'));
        $tests = array('Test','test','Tests','tests');
        for ($counter = count($parts); $counter >= 0; $counter--) {
            foreach ($tests as $test) {
                $ns1 = $this->toNamespace($parts, $test, $counter);
                if (isset($this->folders[$ns1])) {
                    return trim($ns1.'\\'.$append, '\\');
                }
            }
        }
        return $namespace;
    }

    /**
     * @param string $class
     * @return TargetPhpFile
     */
    public function getTestFileForNamespacedClass($class)
    {
        list($namespace, $append) = $this->splitIntoMain($this->getTestNamespaceForNamespace($class));
        return new TargetPhpFile(
            new SplFileInfo(
                $this->folders[$namespace].DIRECTORY_SEPARATOR
                .str_replace('\\', DIRECTORY_SEPARATOR, trim($append, '\\'))
                .'Test.php'
            ),
            $this->mode
        );
    }
}
