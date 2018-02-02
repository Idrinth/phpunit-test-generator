<?php

namespace De\Idrinth\TestGenerator\Implementations;

class DocBlockParser implements \De\Idrinth\TestGenerator\Interfaces\DocBlockParser
{
    private static $endPattern = "[ ]*(?:@|\r\n|\n)";

    private function pregMatchAll($key, $rawDocBlock, &$matches)
    {
        return preg_match_all(
            "/@".preg_quote($key)." (.*)".self::$endPattern."/U",
            $rawDocBlock,
            $matches
        );
    }
    private function getParameter($key, $rawDocBlock)
    {
        $this->pregMatchAll($key, $rawDocBlock, $matches);
        $parameters = array();
        foreach ($matches[1] as $elem) {
            $parameters[] = $this->parseValue($elem);
        }
        return $parameters;
    }

    private function getVariableType($name, $rawDocBlock)
    {
        $declarations = array();
        foreach ($this->getParameter($name, $rawDocBlock) as $declaration) {
            $declarations[] = $this->parseVariableType($declaration);
        }
        return $declarations;
    }

    private function parseVariableType($declaration)
    {
        list($type) = explode(" ", $declaration);
        return isset($type) && $type && $type{0} !== '$' ? $type : 'string';
    }

    private function parseValue($originalValue)
    {
        if (!$originalValue || $originalValue === 'null') {
            return 'null';
        }
        return $originalValue;
    }

    public function getReturn($rawDocBlock)
    {
        if (!$rawDocBlock) {
            return 'null';
        }
        $return = $this->getVariableType('return', $rawDocBlock);
        return count($return) ? implode('|', $return) : 'null';
    }
    public function getParams($rawDocBlock)
    {
        if (!$rawDocBlock) {
            return array();
        }
        return $this->getVariableType('param', $rawDocBlock);
    }

    public function getExceptions($rawDocBlock)
    {
        if (!$rawDocBlock) {
            return array();
        }
        return $this->getVariableType('throws', $rawDocBlock);
    }
}
