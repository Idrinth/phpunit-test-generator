<?php

namespace De\Idrinth\TestGenerator\Factory;

use PhpParser\Node\Stmt\ClassMethod;
use \De\Idrinth\TestGenerator\Service\TypeResolver;
use De\Idrinth\TestGenerator\Service\DocBlockParser;
use De\Idrinth\TestGenerator\Model\MethodDescriptor;

class MethodFactory
{
    /**
     * @var DocBlockParser
     */
    private $doc;

    /**
     * @param DocBlockParser $doc
     */
    public function __construct(DocBlockParser $doc)
    {
        $this->doc = $doc;
    }

    /**
     * @param TypeResolver $resolver
     * @param ClassMethod $method
     * @return MethodDescriptor
     */
    public function create(TypeResolver $resolver, ClassMethod $method)
    {
        $doc = $method->getDocComment();
        return new MethodDescriptor(
            $method->name,
            $this->getParams($resolver, $method, $doc),
            $resolver->toType(
                $method->returnType,
                $this->doc->getReturn($doc)
            ),
            $method->isStatic(),
            $this->getThrows($resolver, $doc)
        );
    }

    /**
     * @param TypeResolver $resolver
     * @param string $doc
     * @return Type[]
     */
    private function getThrows(TypeResolver $resolver, $doc)
    {
        $throws = array();
        foreach ($this->doc->getExceptions($doc) as $throw) {
            $throws[] = $resolver->toType(
                '',
                $throw
            );
        }
        return $throws;
    }

    /**
     * @param TypeResolver $resolver
     * @param ClassMethod $method
     * @param string $doc
     * @return Type[]
     */
    private function getParams(TypeResolver $resolver, ClassMethod $method, $doc)
    {
        $params = array();
        $docParams = $this->doc->getParams($doc);
        foreach ($method->params as $pos => $param) {
            $params[] = $resolver->toType(
                $param->type,
                isset($docParams[$pos])?$docParams[$pos]:null
            );
        }
        return $params;
    }
}
