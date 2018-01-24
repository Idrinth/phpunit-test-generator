<?php
namespace De\Idrinth\TestGenerator\Implementations;

use Twig_Environment;
use Twig_Loader_Filesystem;

class ClassWriter implements \De\Idrinth\TestGenerator\Interfaces\ClassWriter
{
    private $env;
    private $namespaces;
    public function __construct(\De\Idrinth\TestGenerator\Interfaces\NamespacePathMapper $namespaces)
    {
        $this->namespaces = $namespaces;
        $this->env = new Twig_Environment(new Twig_Loader_Filesystem(dirname(dirname(__DIR__)).DIRECTORY_SEPARATOR.'templates'));
        $this->env->addFilter(new \Twig_SimpleFilter('toUpperCamelCase', function($string) {
            $parts = explode('_', preg_replace('/[^_A-Za-z0-9]+/', '_', $string));
            $result = '';
            foreach($parts as $part) {
                $result.= strtoupper($part{0});
                if(strlen($part)>1) {
                    $result.= substr($part, 1);
                }
            }
            return $result;
        }));
    }
    public function write(\De\Idrinth\TestGenerator\Interfaces\ClassDescriptor $class)
    {
        echo $this->env->render(
            'class.twig',
            array('class' => $class, 'config' => array('testcase' => 'PHPUnit\Framework\TestCase'))
        );
    }
}