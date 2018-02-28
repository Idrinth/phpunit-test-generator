<?php

namespace De\Idrinth\TestGenerator\Interfaces;

interface Composer
{
    /**
     * @return string[] namespace => folder
     */
    public function getProductionNamespacesToFolders();

    /**
     * @return string[] namespace => folder
     */
    public function getDevelopmentNamespacesToFolders();
}
