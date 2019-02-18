<?php

namespace De\Idrinth\TestGenerator\Model;

use InvalidArgumentException;
use De\Idrinth\TestGenerator\File\JsonFile;
use De\Idrinth\TestGenerator\Service\ComposerProcessor;

class Composer
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
     * @param JsonFile $file
     * @param ComposerProcessor $processor
     * @throws InvalidArgumentException if file is unusable
     */
    public function __construct(JsonFile $file, ComposerProcessor $processor)
    {
        list($this->autoloadProd, $this->autoloadDev, $this->testClass) = $processor->process(
            $file->getContent(),
            $file->getPath()
        );
    }

    /**
     * @return string
     */
    public function getTestClass()
    {
        return $this->testClass;
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
