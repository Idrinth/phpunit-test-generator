<?php
namespace De\Idrinth\TestGenerator\Implementations;

use PhpParser\Lexer;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Interface_;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Use_;
use PhpParser\Parser;
use SplFileInfo;

class ClassReader implements \De\Idrinth\TestGenerator\Interfaces\ClassReader
{
    private $parser;
    private $classes = array();
    private $doc;
    public function __construct()
    {
        $this->doc = new DocBlockParser();
        $this->parser = new Parser(new Lexer(), array('throwOnError'=>true));
    }

    /**
     * @param SplFileInfo $file
     * @return \De\Idrinth\TestGenerator\Interfaces\ClassDescriptor
     */
    public function parse(SplFileInfo $file)
    {
        $result = $this->parser->parse(file_get_contents($file->getPathname()));
        foreach ($result as $node) {
            if ($node instanceof Namespace_) {
                $this->handleNamespaceTree($node);
            } elseif ($node instanceof Class_ ||
                $node instanceof Interface_
            ) {
                $this->handleNamespaceTree(new Namespace_(new Name(array()), array($node)));
            }
        }
    }

    /**
     * @param Namespace_ $namespace
     */
    private function handleNamespaceTree(Namespace_ $namespace)
    {
        $uses = array();
        foreach ($namespace->stmts as $node) {
            if ($node instanceof Use_) {
                if ($node->type == Use_::TYPE_NORMAL) {
                    foreach ($node->uses as $use) {
                        $uses[$use->alias] = $use->name->toString();
                    }
                }
            } elseif ($node instanceof Class_) {
                $methods = array();
                $constructor = new MethodDescriptor('__construct', array(), 'void');
                foreach ($node->stmts as $iNode) {
                    if ($iNode instanceof ClassMethod && $iNode->isPublic()) {
                        if ($iNode->name == '__construct' || $iNode->name == $node->name) {
                            $constructor = $this->buildFunctionDescriptor($namespace, $iNode, $uses);
                        } else {
                            $methods[] = $this->buildFunctionDescriptor($namespace, $iNode, $uses);
                        }
                    }
                }
                $this->classes[] = new ClassDescriptor(
                    $node->name,
                    implode("\\", $namespace->name->parts),
                    $methods,
                    $constructor
                );
            }
        }
    }

    /**
     * @param Namespace_ $namespace
     * @param ClassMethod $method
     * @param array $uses
     * @return FunctionDescriptor
     */
    private function buildFunctionDescriptor(Namespace_ $namespace, ClassMethod $method, array $uses)
    {
        $params = array();
        $doc = $method->getDocComment();
        $docParams = $this->doc->getParams($doc);
        $docThrows = $this->doc->getExceptions($doc);
        foreach ($method->params as $pos => $param) {
            $params[] = $this->typeToTypeString(
                $param->type,
                isset($docParams[$pos])?$docParams[$pos]:null,
                $uses,
                $namespace
            );
        }
        foreach ($docThrows as &$throws) {
            $throws = $this->typeToTypeString(
                '',
                $throws,
                $uses,
                $namespace
            );
        }
        return new MethodDescriptor(
            $method->name,
            $params,
            $this->typeToTypeString(
                $method->returnType,
                $this->doc->getReturn($doc),
                $uses,
                $namespace
            ),
            $docThrows
        );
    }
    private function typeToTypeString($type, $doc, $uses, Namespace_ $namespace)
    {
        if (!$type) {
            if ($doc) {
                return $this->docStringToType($doc, $uses, $namespace);
            }
            return 'mixed';
        }
        if ($type instanceof Name) {
            return $this->nameToTypeString($type, $uses, $namespace);
        }
        return $type;
    }

    private function docStringToType($docString, $uses, Namespace_ $namespace)
    {
        return strtolower($docString)==$docString?
            $docString :
            $this->nameToTypeString(
                $docString{0}==='\\'?new FullyQualified($docString):new Name($docString),
                $uses,
                $namespace
            );
    }
    private function nameToTypeString(Name $name, $uses, Namespace_ $namespace)
    {
        if ($name->isFullyQualified()) {
            return $name->toString();
        }
        if (isset($uses[$name->toString()])) {
            return $uses[$name->toString()];
        }
        $name->prepend($namespace->name);
        return $name->toString();
    }

    /**
     * @return \De\Idrinth\TestGenerator\Interfaces\ClassDescriptor[]
     */
    public function getResults()
    {
        return $this->classes;
    }
}
