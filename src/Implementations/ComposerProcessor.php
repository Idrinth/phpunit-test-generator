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
    private $output = '';

    /**
     * @param TCDI $decider
     * @param string $output
     */
    public function __construct(TCDI $decider, $output = '')
    {
        $this->decider = $decider;
        if (is_string($output) && strlen($output) > 0) {
            $this->output = DIRECTORY_SEPARATOR . $output;
        }
    }

    /**
     * @param array $composer
     * @param string $path
     * @return array [$autoloadProd, $autoloadDev, $testClass]
     * @throws InvalidArgumentException if file is unusable
     */
    public function process(array $composer, $path)
    {
        foreach (['require-dev', 'require'] as $require) {
            if (isset($composer[$require]) && isset($composer[$require]['phpunit/phpunit'])) {
                return [
                    $this->handleKey($composer, 'autoload', $path),
                    $this->handleKey($composer, 'autoload-dev', $path.$this->output),
                    $this->decider->get($composer[$require]['phpunit/phpunit'])
                ];
            }
        }
        throw new InvalidArgumentException("No possibility to determine PHPunit TestCase class found");
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
            return [];
        }
        $autoloaders = $data[$key];
        return array_merge(
            $this->processPsrAutoloadKey($autoloaders, 'psr-0', $rootDir),
            $this->processPsrAutoloadKey($autoloaders, 'psr-4', $rootDir)
        );
    }

    /**
     * @param array $autoloaders
     * @param string $method
     * @param string $rootDir
     * @return string[]
     */
    private function processPsrAutoloadKey($autoloaders, $method, $rootDir)
    {
        if (!isset($autoloaders[$method])) {
            return [];
        }
        $folders = [];
        foreach ($autoloaders[$method] as $namespace => $folder) {
            $folders[trim($namespace, '\\')] = $rootDir.DIRECTORY_SEPARATOR.$folder;
        }
        return $folders;
    }
}
