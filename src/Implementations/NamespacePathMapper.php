<?php

namespace De\Idrinth\TestGenerator\Implementations;

use SplFileInfo;
use InvalidArgumentException;

class NamespacePathMapper implements \De\Idrinth\TestGenerator\Interfaces\NamespacePathMapper
{
    /**
     * Mapping Namespace => Folder
     * @var string[]
     */
    private $folders = array();

    /**
     * @param SplFileInfo $composer
     * @throws InvalidArgumentException
     */
    public function __construct(SplFileInfo $composer)
    {
        $data = json_decode(file_get_contents($composer->getPathname()), true);
        if (!$data) {
            throw new InvalidArgumentException("A parseable composer.json wasn't found.");
        }
        $this->handleKey($data, 'autoload', $composer->getPath());
        $this->handleKey($data, 'autoload-dev', $composer->getPath());
    }

    /**
     * @param array $data
     * @param string $key
     * @param string $rootDir
     * @return void
     */
    private function handleKey(array $data, $key, $rootDir)
    {
        if (!isset($data[$key])) {
            return;
        }
        $autoloaders = $data[$key];
        foreach (array('psr-0', 'psr-4') as $method) {
            if (isset($autoloaders[$method])) {
                foreach ($autoloaders[$method] as $namespace => $folder) {
                    $this->folders[trim($namespace, '\\')] = $rootDir.DIRECTORY_SEPARATOR.$folder;
                }
            }
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
        for ($counter = count($parts); $counter >= 0; $counter--) {
            $ns1 = $this->toNamespace($parts, 'Test', $counter);
            if (isset($this->folders[$ns1])) {
                return trim($ns1.'\\'.$append, '\\');
            }
            $ns2 = $this->toNamespace($parts, 'Tests', $counter);
            if (isset($this->folders[$ns2])) {
                return trim($ns2.'\\'.$append, '\\');
            }
        }
        return $namespace;
    }

    /**
     * @param string $class
     * @return SplFileInfo
     */
    public function getTestFileForNamespacedClass($class)
    {
        list($namespace, $append) = $this->splitIntoMain($this->getTestNamespaceForNamespace($class));
        return new SplFileInfo(
            $this->folders[$namespace].DIRECTORY_SEPARATOR
            .str_replace('\\', DIRECTORY_SEPARATOR, trim($append, '\\'))
            .'Test.php'
        );
    }
}
