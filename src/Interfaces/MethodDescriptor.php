<?php

namespace De\Idrinth\TestGenerator\Interfaces;

interface MethodDescriptor
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return Type[]
     */
    public function getParams();

    /**
     * @return Type
     */
    public function getReturn();

    /**
     * @return Type[]
     */
    public function getExceptions();
}
