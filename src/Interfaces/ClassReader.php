<?php
namespace De\Idrinth\TestGenerator\Interfaces;

use SplFileInfo;

interface ClassReader
{
    public function parse(SplFileInfo $file);
    /**
     * @return ClassDescriptor[]
     */
    public function getResults();
}
