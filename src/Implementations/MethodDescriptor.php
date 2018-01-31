<?php

namespace De\Idrinth\TestGenerator\Implementations;

class MethodDescriptor implements \De\Idrinth\TestGenerator\Interfaces\MethodDescriptor
{
    private $name;
    private $params = array();
    private $return;
    private $returnClass = null;
    private $exceptions = array();
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
    public function __construct($name, $params, $return, $exceptions=array())
    {
        $this->name = $name;
        $this->params = $this->processTypeList($params);
        $this->return = $this->process($return);
        if ($this->return === 'object' && !strpos($return, '|')) {
            $this->returnClass = $return;
        }
        $this->exceptions = $exceptions;
    }
    private function processTypeList(array $types)
    {
        $results = array();
        foreach ($types as $type) {
            $results[] = $this->process($type);
        }
        return $results;
    }

    private function process($type)
    {
        if (is_null($type)) {
            return 'null';
        }
        if (strpos($type, '|')) {
            $types = $this->simplifyTypeList($this->processTypeList(explode('|', $type)));
            return count($types)==1?$types[0]:'mixed';
        }
        if ($type{0} === strtoupper($type{0})) {
            return 'object';
        }
        return isset(self::$replacements[$type]) ? self::$replacements[$type] : $type;
    }
    private function simplifyTypeList($list)
    {
        $types = array_unique($list);
        if (in_array('integer', $types) && in_array('float', $types)) {
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

    public function getExceptions()
    {
        return $this->exceptions;
    }
}
