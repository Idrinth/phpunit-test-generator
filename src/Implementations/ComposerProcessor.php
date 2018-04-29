<?php

namespace De\Idrinth\TestGenerator\Implementations;

use De\Idrinth\TestGenerator\Interfaces\TestClassDecider as TCDI;
use De\Idrinth\TestGenerator\Interfaces\ComposerProcessor as CPI;
use InvalidArgumentException;

class ComposerProcessor implements CPI
{
    /**
     * @var TCDI
     */
    private $decider;

    /**
     * @var string
     */
    private $output;

    /**
     * @param TCDI $decider
     * @param string $output
     */
    public function __construct(TCDI $decider, $output = '')
    {
        $this->decider = $decider;
        $this->output = ($output?DIRECTORY_SEPARATOR:'').$output;
    }

    /**
     * @param array $composer
     * @param string $path
     * @return array [$autoloadProd, $autoloadDev, $testClass]
     * @throws InvalidArgumentException if file is unusable
     */
    public function process(array $composer, $path)
    {
        if (!isset($composer['require-dev']) || !isset($composer['require-dev']['phpunit/phpunit'])) {
            throw new InvalidArgumentException("No possibility to determine PHPunit TestCase class found");
        }
        return array(
            $this->handleKey($composer, 'autoload', $path),
            $this->handleKey($composer, 'autoload-dev', $path.$this->output),
            $this->decider->get($composer['require-dev']['phpunit/phpunit'])
        );
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
}
