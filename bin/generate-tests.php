<?php

use De\Idrinth\TestGenerator\Implementations\ClassReader;
use De\Idrinth\TestGenerator\Implementations\ClassWriter;
use De\Idrinth\TestGenerator\Implementations\NamespacePathMapper;
use Symfony\Component\Finder\Finder;

require_once dirname(__DIR__).DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';

$replacements = new NamespacePathMapper(new SplFileInfo(dirname(__DIR__).DIRECTORY_SEPARATOR.'composer.json'));

$finder = new Finder();
$finder
    ->files()
    ->name('/\\.php$/i')
    ->in(dirname(__DIR__).DIRECTORY_SEPARATOR.'src');

$writer = new ClassWriter($replacements);
$reader = new ClassReader();
foreach($finder as $file) {
    $reader->parse($file);
}
foreach ($reader->getResults() as $result) {
    $writer->write($result);
}

var_dump($replacements->getTestNamespaceForNamespace('De\Idrinth\TestGenerator\\'));