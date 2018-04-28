<?php

namespace De\Idrinth\TestGenerator\Implementations;

use De\Idrinth\TestGenerator\Interfaces\Composer as ComposerInterface;
use InvalidArgumentException;
use De\Idrinth\TestGenerator\Interfaces\JsonFile as JFI;
use De\Idrinth\TestGenerator\Interfaces\TestClassDecider as TCDI;

class Composer implements ComposerInterface
{
    /**
     * @var string[] namespace => folder
     */
    private $autoloadProd = array();

    /**
     * @var string[] namespace => folder
     */
    private $autoloadDev = array();

    /**
     * @var string
     */
    private $testClass = null;

    /**
     * @param JFI $file
     * @param TCDI $decider
     * @param string $output
     * @throws InvalidArgumentException if file is unusable
     */
    public function __construct(JFI $file, TCDI $decider, $output = '')
    {
        $data = $file->getContent();
        $this->autoloadProd = $this->handleKey($data, 'autoload', $file->getPath());
        $this->autoloadDev = $this->handleKey(
            $data,
            'autoload-dev',
            $file->getPath().($output?DIRECTORY_SEPARATOR:'').$output
        );
        if (!isset($data['require-dev']) || !isset($data['require-dev']['phpunit/phpunit'])) {
            throw new InvalidArgumentException("No possibility to determine PHPunit TestCase class found");
        }
        $this->testClass = $decider->get($data['require-dev']['phpunit/phpunit']);
    }

    /**
     * @return string
     */
    public function getTestClass()
    {
        return $this->testClass;
    }

    /**
     * @param array $data
     * @param string $key
     * @param string $rootDir
     * @return string[]
     */
    private function handleKey(array $data, $key, $rootDir)
    {
        if (!isset($data[$key])) {
            return array();
        }
        $autoloaders = $data[$key];
        $folders = array();
        foreach (array('psr-0', 'psr-4') as $method) {
            if (isset($autoloaders[$method])) {
                foreach ($autoloaders[$method] as $namespace => $folder) {
                    $folders[trim($namespace, '\\')] = $rootDir.DIRECTORY_SEPARATOR.$folder;
                }
            }
        }
        return $folders;
    }

    /**
     * @return string[] namespace => folder
     */
    public function getProductionNamespacesToFolders()
    {
        return $this->autoloadProd;
    }

    /**
     * @return string[] namespace => folder
     */
    public function getDevelopmentNamespacesToFolders()
    {
        return $this->autoloadDev;
    }
}
