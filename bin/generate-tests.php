<?php
use De\Idrinth\TestGenerator\Controller;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

require_once implode(
    DIRECTORY_SEPARATOR,
    array(
        is_dir(dirname(__DIR__).DIRECTORY_SEPARATOR.'vendor') ? dirname(__DIR__) : dirname(dirname(dirname(__DIR__))),
        'vendor',
        'autoload.php'
    )
);

(new Application('PHPUnit-Test-Generator', '1.0.0'))
    ->register('generate-tests')
    ->addOption('mode', 'm', InputArgument::OPTIONAL, "Replace | Skip | Move")
    ->addOption('dir', 'd', InputArgument::OPTIONAL, 'Directory which contains composer.json', null)
    ->addOption('output', 'o', InputArgument::OPTIONAL, 'Output-Directory for generated files', null)
    ->setCode(
        function(InputInterface $input, OutputInterface $output) {
        Controller::init()->run();
    })
    ->getApplication()
    ->setDefaultCommand('generate-tests', true) // Single command application
    ->run();

