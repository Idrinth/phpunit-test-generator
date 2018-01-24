<?php

namespace De\Idrinth\TestGenerator\Implementations;

use phpDocumentor\Reflection\TypeResolver;

class MethodDescriptor implements \De\Idrinth\TestGenerator\Interfaces\MethodDescriptor
{
    private $name;
    private $params = array();
    private $return;
    private $returnClass;
    protected static $replacements = array(
        'void' => 'null',
        'compound' => 'mixed',
        'nullable' => 'null',
        'self' => 'object',
        'static' => 'object',
        'this' => 'object',
        'scalar' => 'object',
        'iterable' => 'mixed',
        'resource' => 'integer',
        'bool' => 'boolean',
        'int' => 'integer'
    );
    public function __construct($name, $params, $return)
    {
        $typeResolver = new TypeResolver();
        $this->name = $name;
        foreach($params as $param) {
            try {
                $this->params[] = $this->process($typeResolver, $param);
            } catch (RuntimeException $ex) {
                $this->params[] = 'mixed';
            }
        }
        try {
            $this->return = $this->process($typeResolver, $return);
        } catch (RuntimeException $ex) {
            $this->return = 'null';
        }
        $this->returnClass = $this->return == 'object' ? $return : null;
    }
    private function process(TypeResolver $typeResolver, $type)
    {
        if(is_null($type)) {echo __LINE__;
            return 'null';
        }
        if(strpos($type, '|')) {echo __LINE__;
            return 'mixed';
        }
        $class = strtolower(trim(str_replace('phpDocumentor\Reflection\Types\\','',get_class($typeResolver->resolve($type))),'_'));
        return isset(self::$replacements[$class]) ? self::$replacements[$class] : $class;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getParams()
    {
        return $this->params;
    }

    public function getReturn()
    {
        return $this->return;
    }

    public function getReturnClass()
    {
        return $this->returnClass;
    }
}