<?php

use De\Idrinth\TestGenerator\Implementations\ClassReader;
use De\Idrinth\TestGenerator\Implementations\ClassWriter;
use De\Idrinth\TestGenerator\Implementations\NamespacePathMapper;
use Symfony\Component\Finder\Finder;

require_once dirname(__DIR__).DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';

$finder = new Finder();
$finder
    ->files()
    ->name('/\\.php$/i')
    ->in(dirname(__DIR__).DIRECTORY_SEPARATOR.'src');

$writer = new ClassWriter(new NamespacePathMapper(new SplFileInfo(getcwd().DIRECTORY_SEPARATOR.'composer.json')));
$reader = new ClassReader();
foreach($finder as $file) {
    $reader->parse($file);
}
foreach ($reader->getResults() as $result) {
    $writer->write($result);
}