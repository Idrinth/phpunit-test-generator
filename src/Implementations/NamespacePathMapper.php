<?php

namespace De\Idrinth\TestGenerator\Implementations;

use SplFileInfo;

class NamespacePathMapper implements \De\Idrinth\TestGenerator\Interfaces\NamespacePathMapper
{
    private $folders = array();
    public function __construct(SplFileInfo $composer)
    {
        $data = json_decode(file_get_contents($composer->getPathname()), true);
        if (!$data) {
            throw new \InvalidArgumentException("A parseable composer.json wasn't found.");
        }
        $this->handleKey($data, 'autoload', $composer->getPath());
        $this->handleKey($data, 'autoload-dev', $composer->getPath());
    }
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
    private function toNamespace($parts, $part, $position)
    {
        $original = array();
        array_splice($original, 0, 0, $parts);
        array_splice($original, $position, 0, array($part));
        return implode("\\", $original);
    }
    private function splitIntoMain($namespace)
    {
        $matches = array();
        foreach ($this->folders as $ns => $folder) {
            if (preg_match('/^'.preg_quote($ns).'/', $namespace)) {
                $matches[] = $ns;
            }
        }
        usort($matches, function ($a, $b) {
            return strlen($b)-strlen($a);
        });
        return array(
            trim($matches[0], '\\'),
            trim(str_replace($matches[0], '', $namespace), '\\')
        );
    }

    public function getTestNamespaceForNamespace($namespace)
    {
        list($ns, $append) = $this->splitIntoMain($namespace);
        $parts = explode("\\", trim($ns, '\\'));
        for ($i = count($parts); $i >= 0; $i--) {
            $ns1 = $this->toNamespace($parts, 'Test', $i);
            if (isset($this->folders[$ns1])) {
                return trim($ns1.'\\'.$append, '\\');
            }
            $ns2 = $this->toNamespace($parts, 'Tests', $i);
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
        return new \SplFileInfo(
            $this->folders[$namespace].DIRECTORY_SEPARATOR
            .str_replace('\\', DIRECTORY_SEPARATOR, trim($append, '\\'))
            .'Test.php'
        );
    }
}
