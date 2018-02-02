<?php

use De\Idrinth\TestGenerator\Implementations\ClassReader;
use De\Idrinth\TestGenerator\Implementations\ClassWriter;
use De\Idrinth\TestGenerator\Implementations\NamespacePathMapper;
use Symfony\Component\Finder\Finder;

require_once dirname(__DIR__).DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';

$opts = getopt('', array('dir:','replace'));
$dir = isset($opts['dir']) && $opts['dir'] ? rtrim($opts['dir'], DIRECTORY_SEPARATOR) : getcwd();

$finder = new Finder();
$finder
    ->files()
    ->name('/\\.php$/i')
    ->in($dir.DIRECTORY_SEPARATOR.'src');

$writer = new ClassWriter(new NamespacePathMapper(new SplFileInfo($dir.DIRECTORY_SEPARATOR.'composer.json')));
$reader = new ClassReader();
foreach ($finder as $file) {
    $reader->parse($file);
}
foreach ($reader->getResults() as $result) {
    $writer->write($result, $reader->getResults(), isset($opts['replace']));
}
