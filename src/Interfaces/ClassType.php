<?php

namespace De\Idrinth\TestGenerator\Interfaces;

interface ClassType extends Type
{
    /**
     * The FQN of a class
     * @return string
     */
    public function getClassName();
}
