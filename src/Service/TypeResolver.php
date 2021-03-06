<?php

namespace De\Idrinth\TestGenerator\Service;

use De\Idrinth\TestGenerator\Model\Type\ArrayType;
use De\Idrinth\TestGenerator\Model\Type\ClassType;
use De\Idrinth\TestGenerator\Model\Type\SimpleType;
use De\Idrinth\TestGenerator\Model\Type\UnknownType;
use De\Idrinth\TestGenerator\Model\Type;
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
        if (!$doc) {
            return null;
        }
        if (isset(self::$primitives[$doc])) {
            return new SimpleType(self::$primitives[$doc]);
        }
        if (isset(self::$keywords[$doc])) {
            return new SimpleType(self::$keywords[$doc]);
        }
        return $this->typeListToType(explode('|', $doc));
    }

    /**
     * @param Name|string $type
     * @return Type|null
     */
    private function typeToType($type)
    {
        if (!$type) {
            return null;
        }
        if (isset(self::$primitives[$type.''])) {
            return new SimpleType(self::$primitives[$type.'']);
        }
        if ($type instanceof Name) {
            return new ClassType($this->nameToFQString($type));
        }
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
            $this->simplifyListItem($type, $simples, $arrayTypes, $isObject, $isArray);
        }
    }

    /**
     * @param string $type
     * @param string[] $simples
     * @param string[] $arrayTypes
     * @param boolean $isObject
     * @param boolean $isArray
     * @return void
     */
    private function simplifyListItem($type, array &$simples, array &$arrayTypes, &$isObject, &$isArray)
    {
        if (isset(self::$keywords[$type])) {
            return $this->addTypeToSimpleList(self::$keywords[$type], $simples, $isObject, $isArray);
        }
        if (isset(self::$primitives[$type])) {
            return $this->addTypeToSimpleList(self::$primitives[$type], $simples, $isObject, $isArray);
        }
        if (preg_match('/\\[\\]$/', $type)) {
            $arrayTypes[] = substr($type, 0, strlen($type) -2);
            return $this->addTypeToSimpleList('array', $simples, $isObject, $isArray);
        }
        $this->addTypeToSimpleList('object', $simples, $isObject, $isArray);
    }
    
    /**
     * @param string $simpleType
     * @param string[] $simples
     * @param boolean $isObject
     * @param boolean $isArray
     * @return void
     */
    private function addTypeToSimpleList($simpleType, array &$simples, &$isObject, &$isArray)
    {
        $simples[] = $simpleType;
        $isArray = $isArray && $simpleType === 'array';
        $isObject = $isObject && $simpleType === 'object';
    }

    /**
     * @param Name $name
     * @return string
     */
    private function nameToFQString(Name $name)
    {
        if ($name->isFullyQualified()) {
            return $name.'';
        }
        if (!$name->isQualified() && isset($this->uses[$name.''])) {
            return $this->uses[$name.''];
        }
        $new = clone $name;
        $new->prepend($this->namespace->name);
        return $new.'';
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
