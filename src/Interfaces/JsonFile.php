<?php

namespace De\Idrinth\TestGenerator\Interfaces;

interface JsonFile
{
    /**
     * @return string
     */
    public function getPath();

    /**
     * @return array
     */
    public function getContent();
}
