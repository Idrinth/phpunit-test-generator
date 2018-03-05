<?php

namespace De\Idrinth\TestGenerator\Interfaces;

interface ArrayType extends Type
{
    /**
     * The Item's type
     * @return Type
     */
    public function getItemType();
}
