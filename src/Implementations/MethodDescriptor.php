<?php

namespace De\Idrinth\TestGenerator\Implementations;

use phpDocumentor\Reflection\TypeResolver;

class MethodDescriptor implements \De\Idrinth\TestGenerator\Interfaces\MethodDescriptor
{
    private $name;
    private $params = array();
    private $return;
    private $returnClass = null;
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
        'int' => 'integer',
        'double' => 'float'
    );
    public function __construct($name, $params, $return)
    {
        $typeResolver = new TypeResolver();
        $this->name = $name;
        $this->params = $this->processTypeList($typeResolver, $params);
        try {
            $this->return = $this->process($typeResolver, $return);
        } catch (RuntimeException $ex) {
            $this->return = 'null';
        }
        if($this->return === 'object' && !strpos($return,'|')) {
            $this->returnClass = $return;
        }
    }
    private function processTypeList(TypeResolver $typeResolver, array $types)
    {
        $results = array();
        foreach($types as $type) {
            try {
                $results[] = $this->process($typeResolver, $type);
            } catch (RuntimeException $ex) {
                $results[] = 'mixed';
            }
        }
        return $results;
    }

    private function process(TypeResolver $typeResolver, $type)
    {
        if(is_null($type)) {
            return 'null';
        }
        if(strpos($type, '|')) {
            $types = $this->simplifyTypeList($this->processTypeList($typeResolver, explode('|', $type)));
            return count($types)==1?$types[0]:'mixed';
        }
        $class = strtolower(trim(str_replace('phpDocumentor\Reflection\Types\\','',get_class($typeResolver->resolve($type))),'_'));
        return isset(self::$replacements[$class]) ? self::$replacements[$class] : $class;
    }
    private function simplifyTypeList($list)
    {
        $types = array_unique($list);
        if(in_array('integer', $types) && in_array('float', $types)) {
            unset($types[array_search('integer', $types)]);
        }
        return array_values($types);
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