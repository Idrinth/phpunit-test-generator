<?php

namespace De\Idrinth\TestGenerator\Implementations;

use De\Idrinth\TestGenerator\Interfaces\Composer as ComposerInterface;
use InvalidArgumentException;
use De\Idrinth\TestGenerator\Interfaces\JsonFile as JFI;
use De\Idrinth\TestGenerator\Interfaces\ComposerProcessor as CPI;

class Composer implements ComposerInterface
{
    /**
     * @var string[] namespace => folder
     */
    private $autoloadProd = [];

    /**
     * @var string[] namespace => folder
     */
    private $autoloadDev = [];

    /**
     * @var string
     */
    private $testClass = null;

    /**
     * @param JFI $file
     * @param CPI $processor
     * @throws InvalidArgumentException if file is unusable
     */
    public function __construct(JFI $file, CPI $processor)
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
