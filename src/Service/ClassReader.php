<?php
namespace De\Idrinth\TestGenerator\Service;

use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Use_;
use PhpParser\Parser;
use SplFileInfo;
use De\Idrinth\TestGenerator\Model\ClassDescriptor as CDI;
use De\Idrinth\TestGenerator\Factory\ClassDescriptorFactory as CDFI;

class ClassReader
{
    /**
     * @var Parser
     */
    private $parser;

    /**
     * @var CDI[]
     */
    private $classes = array();

    /**
     * @var ClassDescriptorFactor
     */
    private $class;

    /**
     * @param CDFI $class
     * @param Parser $parser
     */
    public function __construct(CDFI $class, Parser $parser)
    {
        $this->class = $class;
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
            if (!$node instanceof Namespace_) {
                $node = new Namespace_(new Name(array()), array($node));
            }
            $this->handleNamespaceTree($node);
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
                $this->classes[trim($resolver->getNamespace().'\\'.$node->name, '\\')] = $this->class->create(
                    $node,
                    $resolver
                );
            }
        }
    }

    /**
     * @return CDI[]
     */
    public function getResults()
    {
        return $this->classes;
    }
}
