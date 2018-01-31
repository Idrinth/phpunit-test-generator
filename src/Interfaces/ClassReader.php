<?php
namespace De\Idrinth\TestGenerator\Interfaces;

use SplFileInfo;

interface ClassReader
{
    /**
     * @param SplFileInfo $file
     * @return void
     */
    public function parse(SplFileInfo $file);

    /**
     * @return ClassDescriptor[]
     */
    public function getResults();
}
