<?php

namespace De\Idrinth\TestGenerator\Interfaces;

interface MethodDescriptor
{
    public function getName();
    public function getParams();
    public function getReturn();
    public function getReturnClass();
    public function getExceptions();
}
