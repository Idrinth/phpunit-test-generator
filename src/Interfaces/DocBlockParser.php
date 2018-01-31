<?php

namespace De\Idrinth\TestGenerator\Interfaces;

interface DocBlockParser
{
    public function getReturn($rawDocBlock);
    public function getParams($rawDocBlock);
    public function getExceptions($rawDocBlock);
}
