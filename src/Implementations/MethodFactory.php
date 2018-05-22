<?php

namespace De\Idrinth\TestGenerator\Implementations;

use De\Idrinth\TestGenerator\Interfaces\MethodFactory as MFI;
use PhpParser\Node\Stmt\ClassMethod;
use \De\Idrinth\TestGenerator\Interfaces\TypeResolver as TRI;
use De\Idrinth\TestGenerator\Interfaces\DocBlockParser as DBPI;
use De\Idrinth\TestGenerator\Interfaces\MethodDescriptor as MDI;

class MethodFactory implements MFI
{
    /**
     * @var DBPI
     */
    private $doc;

    /**
     * @param DBPI $doc
     */
    public function __construct(DBPI $doc)
    {
        $this->doc = $doc;
    }

    /**
     * @param TRI $resolver
     * @param ClassMethod $method
     * @return MDI
     */
    public function create(TRI $resolver, ClassMethod $method)
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
     * @param TRI $resolver
     * @param string $doc
     * @return Type[]
     */
    private function getThrows(TRI $resolver, $doc)
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
     * @param TRIE $resolver
     * @param ClassMethod $method
     * @param string $doc
     * @return Type[]
     */
    private function getParams(TRI $resolver, ClassMethod $method, $doc)
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
