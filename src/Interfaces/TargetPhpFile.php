<?php

namespace De\Idrinth\TestGenerator\Interfaces;

use RuntimeException;

interface TargetPhpFile
{
    /**
     * @return boolean
     */
    public function mayWrite();

    /**
     * @param string $content
     * @return boolean
     * @throws RuntimeException
     */
    public function write($content);
}
