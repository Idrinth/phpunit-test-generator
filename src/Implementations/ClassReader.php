<?php
namespace De\Idrinth\TestGenerator\Implementations;

use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Use_;
use PhpParser\Parser;
use SplFileInfo;
use De\Idrinth\TestGenerator\Implementations\Type\ClassType;

class ClassReader implements \De\Idrinth\TestGenerator\Interfaces\ClassReader
{
    /**
     * @var Parser
     */
    private $parser;

    /**
     * @var ClassDescriptor[]
     */
    private $classes = array();

    /**
     * @var DocBlockParser
     */
    private $doc;

    /**
     * @param \De\Idrinth\TestGenerator\Interfaces\DocBlockParser $docblock
     * @param Parser $parser
     */
    public function __construct(
        \De\Idrinth\TestGenerator\Interfaces\DocBlockParser $docblock,
        Parser $parser
    ) {
        $this->doc = $docblock;
        $this->parser = $parser;
    }

    /**
     * @param SplFileInfo $file
     * @return void
     */
    public function parse(SplFileInfo $file)
    {
        $result = $this->parser->parse(file_get_contents($file->getPathname()));
        foreach ($result as $node) {
            if ($node instanceof Namespace_) {
                $this->handleNamespaceTree($node);
            } else {
                $this->handleNamespaceTree(new Namespace_(new Name(array()), array($node)));
            }
        }
    }

    /**
     * @param Namespace_ $namespace
     */
    private function handleNamespaceTree(Namespace_ $namespace)
    {
        $resolver = new TypeResolver($namespace);
        foreach ($namespace->stmts as $node) {
            if ($node instanceof Use_) {
                $resolver->addUse($node);
            } elseif ($node instanceof Class_) {
                $this->handleClassDefinition($node, $resolver);
            }
        }
    }

    /**
     * @param Class_ $class
     * @param TypeResolver $resolver
     */
    private function handleClassDefinition(Class_ $class, TypeResolver $resolver)
    {
        $methods = array();
        $constructor = new MethodDescriptor(
            '__construct',
            array(),
            new ClassType(trim($resolver->getNamespace().'\\'.$class->name, '\\'))
        );
        foreach ($class->stmts as $iNode) {
            if ($iNode instanceof ClassMethod
                && $iNode->isPublic()
                && !$iNode->isAbstract()
            ) {
                $this->addMethod(
                    $constructor,
                    $methods,
                    $this->buildFunctionDescriptor($resolver, $iNode),
                    $class->name
                );
            }
        }
        $this->classes[trim($resolver->getNamespace().'\\'.$class->name, '\\')] = new ClassDescriptor(
            $class->name,
            $resolver->getNamespace(),
            $methods,
            $constructor,
            $class->isAbstract(),
            $class->extends ? $resolver->toType($class->extends, '')->getClassName() : null
        );
    }

    /**
     * @param \De\Idrinth\TestGenerator\Implementations\MethodDescriptor $constructor by reference
     * @param \De\Idrinth\TestGenerator\Implementations\MethodDescriptor[] $methods by reference
     * @param \De\Idrinth\TestGenerator\Implementations\MethodDescriptor $function
     * @param string $className
     * @return void
     */
    private function addMethod(&$constructor, &$methods, MethodDescriptor $function, $className)
    {
        if ($function->getName() == '__construct' || $function->getName() == $className) {
            $constructor = $function;
            return;
        }
        $methods[] = $function;
    }

    /**
     * @param TypeResolver $resolver
     * @param ClassMethod $method
     * @return MethodDescriptor
     */
    private function buildFunctionDescriptor(TypeResolver $resolver, ClassMethod $method)
    {
        $params = array();
        $doc = $method->getDocComment();
        $docParams = $this->doc->getParams($doc);
        $docThrows = $this->doc->getExceptions($doc);
        foreach ($method->params as $pos => $param) {
            $params[] = $resolver->toType(
                $param->type,
                isset($docParams[$pos])?$docParams[$pos]:null
            );
        }
        foreach ($docThrows as &$throws) {
            $throws = $resolver->toType(
                '',
                $throws
            );
        }
        return new MethodDescriptor(
            $method->name,
            $params,
            $resolver->toType(
                $method->returnType,
                $this->doc->getReturn($doc)
            ),
            $docThrows
        );
    }

    /**
     * @return \De\Idrinth\TestGenerator\Interfaces\ClassDescriptor[]
     */
    public function getResults()
    {
        return $this->classes;
    }
}
