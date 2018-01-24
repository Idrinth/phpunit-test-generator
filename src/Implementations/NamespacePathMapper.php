<?php

namespace De\Idrinth\TestGenerator\Implementations;

use SplFileInfo;

class NamespacePathMapper implements \De\Idrinth\TestGenerator\Interfaces\NamespacePathMapper
{
    private $folders = array();
    public function __construct(SplFileInfo $composer)
    {
        $data = json_decode(file_get_contents($composer->getPathname()), true);
        $this->handleKey($data, 'autoload');
        $this->handleKey($data, 'autoload-dev');
    }
    private function handleKey(array $data, $key) {
        if(!isset($data[$key]))
        {
            return;
        }
        $autoloaders = $data[$key];
        foreach(array('psr-0', 'psr-4') as $method) {
            if(isset($autoloaders[$method]))
            {
                foreach ($autoloaders[$method] as $namespace => $folder) {
                    $this->folders[trim($namespace, '\\')] = getcwd().DIRECTORY_SEPARATOR.$folder;
                }
            }
        }
    }
    private function toNamespace($parts, $part, $position) {
        $original = array();
        array_splice( $original, 0, 0, $parts );
        array_splice( $original, $position, 0, array($part) );
        return implode("\\", $original);
    }
    private function splitIntoMain($namespace)
    {
        return array();
    }

    public function getTestNamespaceForNamespace($namespace)
    {
        $parts = explode("\\", trim($namespace, '\\'));
        for($i = count($parts); $i >= 0; $i--) {
            $ns1 = $this->toNamespace($parts, 'Test', $i);
            if(isset($this->folders[$ns1])) {
                return $ns1;
            }
            $ns2 = $this->toNamespace($parts, 'Tests', $i);
            if(isset($this->folders[$ns2])) {
                return $ns2;
            }
        }
        return $namespace;
    }
    public function getFileForNamespacedClass($class)
    {
        
        return '.php';
    }
}