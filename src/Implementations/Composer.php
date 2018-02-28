<?php

namespace De\Idrinth\TestGenerator\Implementations;

use De\Idrinth\TestGenerator\Interfaces\Composer as ComposerInterface;
use InvalidArgumentException;
use SplFileInfo;

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
     * @param SplFileInfo $file
     * @throws InvalidArgumentException if file is unusable
     */
    public function __construct(SplFileInfo $file)
    {
        if (!$file->isFile() || !$file->isReadable()) {
            throw new InvalidArgumentException("File $file doesn't exist or isn't readable.");
        }
        $data = json_decode(file_get_contents($file->getPathname()), true);
        if (!$data) {
            throw new InvalidArgumentException("File $file couldn't be parsed as json.");
        }
        $this->autoloadProd = $this->handleKey($data, 'autoload', $file->getPath());
        $this->autoloadDev = $this->handleKey($data, 'autoload-dev', $file->getPath());
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
