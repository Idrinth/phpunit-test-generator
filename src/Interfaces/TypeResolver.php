<?php

namespace De\Idrinth\TestGenerator\Interfaces;

use De\Idrinth\TestGenerator\Interfaces\Type;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Use_;

interface TypeResolver
{
    /**
     * @param Use_ $use
     */
    public function addUse(Use_ $use);

    /**
     * @param Name|string $type
     * @param string $doc
     * @return Type
     */
    public function toType($type, $doc);

    /**
     * @return string
     */
    public function getNamespace();
}
