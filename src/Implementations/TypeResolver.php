<?php

namespace De\Idrinth\TestGenerator\Implementations;

use De\Idrinth\TestGenerator\Implementations\Type\ArrayType;
use De\Idrinth\TestGenerator\Implementations\Type\ClassType;
use De\Idrinth\TestGenerator\Implementations\Type\SimpleType;
use De\Idrinth\TestGenerator\Implementations\Type\UnknownType;
use De\Idrinth\TestGenerator\Interfaces\Type;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Use_;

class TypeResolver
{
    /**
     * @var string[]
     */
    private $uses = array();

    /**
     * @var Namespace_
     */
    private $namespace;

    /**
     * @var string[]
     */
    private static $primitives = array(
        'string' => 'string',
        'integer' => 'integer',
        'int' => 'integer',
        'float' => 'float',
        'bool' => 'boolean',
        'boolean' => 'boolean',
        'array' => 'array',
        'resource' => 'unknown',
        'null' => 'null',
        'callable' => 'unknown'
    );

    /**
     * @var string[]
     */
    private static $keywords = array(
        'mixed' => 'unknown',
        'void' => 'null',
        'object' => 'object',
        'false' => 'boolean',
        'true' => 'boolean',
        'self' => 'object',
        'static' => 'object',
        '$this' => 'object'
    );

    /**
     * @param Namespace_ $namespace
     */
    public function __construct(Namespace_ $namespace)
    {
        $this->namespace = $namespace;
    }

    /**
     * @param Use_ $use
     */
    public function addUse(Use_ $use)
    {
        foreach ($use->uses as $realUse) {
            $this->uses[$realUse->alias] = $realUse->name->toString();
        }
    }

    /**
     * @param Name|string $type
     * @param string $doc
     * @return Type
     */
    public function toType($type, $doc)
    {
        $parsedType = $this->typeToType($type);
        $parsedDoc = $this->docToType($doc);
        if (!$parsedDoc && !$parsedType) {
            return new UnknownType();
        }
        if (!$parsedType) {
            return $parsedDoc;
        }
        if ($parsedType->getType() === 'array' && $parsedDoc instanceof ArrayType) {
            return $parsedDoc;
        }
        return $parsedType;
    }

    /**
     * @param string $doc
     * @return Type
     */
    private function docToType($doc)
    {
        if ($doc) {
            if (isset(self::$primitives[$doc])) {
                return new SimpleType(self::$primitives[$doc]);
            }
            if (isset(self::$keywords[$doc])) {
                return new SimpleType(self::$keywords[$doc]);
            }
            return $this->typeListToType(explode('|', $doc));
        }
        return null;
    }

    /**
     * @param Name|string $type
     * @return Type|null
     */
    private function typeToType($type)
    {
        if ($type) {
            if ($type instanceof Name) {
                return new ClassType($this->nameToFQString($type));
            }
            if (isset(self::$primitives[$type])) {
                return new SimpleType(self::$primitives[$type]);
            }
            if (isset(self::$keywords[$type])) {
                return new SimpleType(self::$keywords[$type]);
            }
        }
        return null;
    }

    /**
     * @param string $types
     * @return Type
     */
    private function typeListToType(array $types)
    {
        if (count($types) === 0) {
            return new UnknownType();
        }
        $isArray = true;
        $isObject = true;
        $simples = array();
        $arrayTypes = array();
        $this->simplifyList($types, $simples, $arrayTypes, $isObject, $isArray);
        if ($isArray) {
            $inner = $this->typeListToType($arrayTypes);
            return $inner->getType()==='unknown'?new SimpleType('array'):new ArrayType($inner);
        }
        if ($isObject) {
            return count($types)===1?new ClassType($this->stringToFQString($types[0])):new SimpleType('object');
        }
        return count(array_unique($simples))>1?new UnknownType():new SimpleType($simples[0]);
    }

    /**
     * @param string[] $types
     * @param string[] $simples
     * @param string[] $arrayTypes
     * @param boolean $isObject
     * @param boolean $isArray
     * @return void
     */
    private function simplifyList(array $types, array &$simples, array &$arrayTypes, &$isObject, &$isArray)
    {
        foreach ($types as $type) {
            if (isset(self::$keywords[$type])) {
                $simples[] = self::$keywords[$type];
                $isArray = $isArray && self::$keywords[$type] === 'array';
                $isObject = $isObject && self::$keywords[$type] === 'object';
            } elseif (isset(self::$primitives[$type])) {
                $simples[] = self::$primitives[$type];
                $isArray = $isArray && self::$primitives[$type] === 'array';
                $isObject = $isObject && self::$primitives[$type] === 'object';
            } elseif (preg_match('/\\[\\]$/', $type)) {
                $simples[] = 'array';
                $arrayTypes[] = substr($type, 0, strlen($type) -2);
                $isObject = false;
            } else {
                $simples[] = 'object';
                $isArray = false;
            }
        }
    }

    /**
     * @param Name $name
     * @return string
     */
    private function nameToFQString(Name $name)
    {
        if ($name->isFullyQualified()) {
            return $name;
        }
        if (!$name->isQualified() && isset($this->uses[$name.''])) {
            return $this->uses[$name.''];
        }
        return $name->prepend($this->namespace->name).'';
    }

    /**
     * @param string $name
     * @return string
     */
    private function stringToFQString($name)
    {
        if ($name{0} === '\\') {
            return trim($name, '\\');
        }
        if (isset($this->uses[$name])) {
            return $this->uses[$name];
        }
        return trim($this->namespace->name.'\\'.$name, '\\');
    }

    /**
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace->name.'';
    }
}
