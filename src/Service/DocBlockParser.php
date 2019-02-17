<?php

namespace De\Idrinth\TestGenerator\Service;

class DocBlockParser
{
    /**
     * @var string
     */
    private static $endPattern = "[ ]*(?:@|\r\n|\n)";

    /**
     * @param string $key
     * @param string $rawDocBlock
     * @param string[] $matches
     * @return boolean
     */
    private function pregMatchAll($key, $rawDocBlock, &$matches)
    {
        return preg_match_all(
            "/@".preg_quote($key)." (.*)".self::$endPattern."/U",
            $rawDocBlock,
            $matches
        );
    }

    /**
     * @param string $key
     * @param string $rawDocBlock
     * @return string[]
     */
    private function getParameter($key, $rawDocBlock)
    {
        $this->pregMatchAll($key, $rawDocBlock, $matches);
        $parameters = array();
        foreach ($matches[1] as $elem) {
            $parameters[] = $this->parseValue($elem);
        }
        return $parameters;
    }

    /**
     * @param string $name
     * @param string $rawDocBlock
     * @return string[]
     */
    private function getVariableType($name, $rawDocBlock)
    {
        $declarations = array();
        foreach ($this->getParameter($name, $rawDocBlock) as $declaration) {
            $declarations[] = $this->parseVariableType($declaration);
        }
        return $declarations;
    }

    /**
     * @param string $declaration
     * @return string
     */
    private function parseVariableType($declaration)
    {
        list($type) = explode(" ", $declaration);
        return isset($type) && $type && $type{0} !== '$' ? $type : 'string';
    }

    /**
     * @param string $originalValue
     * @return string
     */
    private function parseValue($originalValue)
    {
        if (strlen($originalValue) === 0 || $originalValue === 'null') {
            return 'null';
        }
        return $originalValue;
    }

    /**
     * @param string $rawDocBlock
     * @return string
     */
    public function getReturn($rawDocBlock)
    {
        if (strlen($rawDocBlock) === 0) {
            return 'null';
        }
        $return = $this->getVariableType('return', $rawDocBlock);
        return count($return) ? implode('|', $return) : 'null';
    }

    /**
     * @param string $rawDocBlock
     * @return string[]
     */
    public function getParams($rawDocBlock)
    {
        if (strlen($rawDocBlock) === 0) {
            return array();
        }
        return $this->getVariableType('param', $rawDocBlock);
    }

    /**
     * @param string $rawDocBlock
     * @return string[]
     */
    public function getExceptions($rawDocBlock)
    {
        if (strlen($rawDocBlock) === 0) {
            return array();
        }
        return $this->getVariableType('throws', $rawDocBlock);
    }
}
