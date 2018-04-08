<?php

use De\Idrinth\TestGenerator\GenerateTests;

require_once implode(
    DIRECTORY_SEPARATOR,
    array(
        is_dir(dirname(__DIR__).DIRECTORY_SEPARATOR.'vendor') ? dirname(__DIR__) : dirname(dirname(dirname(__DIR__))),
        'vendor',
        'autoload.php'
    )
);

new GenerateTests();


