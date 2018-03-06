<?php

namespace De\Idrinth\TestGenerator\Interfaces;

interface Type
{
    /**
     * Returns a simple type, that can be used for is_{type} checks
     * @return string
     */
    public function getType();

    /**
     * returns true if this is either
     * - an array with a defined type
     * - an objject with defined class
     * @return boolean
     */
    public function isComplex();

    /**
     * The Item's type
     * @return Type
     */
    public function getItemType();

    /**
     * The FQN of the class
     * @return string
     */
    public function getClassName();
}
