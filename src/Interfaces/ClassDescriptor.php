<?php

namespace De\Idrinth\TestGenerator\Interfaces;

interface ClassDescriptor
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getNamespace();

    /**
     * @return MethodDescriptor[]
     */
    public function getMethods();

    /**
     * @return MethodDescriptor
     */
    public function getConstructor();

    /**
     * @return boolean
     */
    public function isAbstract();

    /**
     * @return string
     */
    public function getExtends();
}
