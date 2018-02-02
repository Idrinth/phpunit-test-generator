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
     * @todo remove Initialization
     */
    public function __construct()
    {
        $this->doc = new DocBlockParser();
        $this->parser = new Parser(new Lexer(), array('throwOnError'=>true));
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
                $this->addUsesToList($node, $uses);
            } elseif ($node instanceof Class_) {
                $this->handleClassDefinition($node, $namespace, $uses);
            }
        }
    }

    /**
     * @param Class_ $class
     * @param Namespace_ $namespace
     * @param string[] $uses
     */
    private function handleClassDefinition(Class_ $class, Namespace_ $namespace, $uses)
    {
        $methods = array();
        $constructor = new MethodDescriptor('__construct', array(), 'void');
        foreach ($class->stmts as $iNode) {
            if (
                $iNode instanceof ClassMethod 
                && $iNode->isPublic() 
                && !$iNode->isAbstract()
            ) {
                $this->addMethod(
                    $constructor,
                    $methods,
                    $this->buildFunctionDescriptor($namespace, $iNode, $uses),
                    $uses
                );
            }
        }
        $this->classes[trim(implode("\\", $namespace->name->parts).'\\'.$class->name, '\\')] = new ClassDescriptor(
            $class->name,
            implode("\\", $namespace->name->parts),
            $methods,
            $constructor,
            $class->isAbstract(),
            $class->extends ? $this->nameToTypeString($class->extends, $uses, $namespace) : null
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
     * @param Use_ $uses
     * @param string[] $list by reference
     */
    private function addUsesToList(Use_ $uses, &$list)
    {
        if ($uses->type == Use_::TYPE_NORMAL) {
            foreach ($uses->uses as $use) {
                $list[$use->alias] = $use->name->toString();
            }
        }
    }

    /**
     * @param Namespace_ $namespace
     * @param ClassMethod $method
     * @param array $uses
     * @return MethodDescriptor
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

    /**
     * @param Name|string $type
     * @param string $doc
     * @param string[] $uses
     * @param Namespace_ $namespace
     * @return string
     */
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

    /**
     * @param string $docString
     * @param string[] $uses
     * @param Namespace_ $namespace
     * @return string
     */
    private function docStringToType($docString, $uses, Namespace_ $namespace)
    {
        return strtolower($docString)==$docString && false===strpos('\\', $docString)?
            $docString :
            $this->nameToTypeString(
                $docString{0}==='\\'?new FullyQualified($docString):new Name($docString),
                $uses,
                $namespace
            );
    }

    /**
     * @param Name $name
     * @param string[] $uses
     * @param Namespace_ $namespace
     * @return string
     */
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
