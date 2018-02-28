<?php
namespace De\Idrinth\TestGenerator;

use De\Idrinth\TestGenerator\Test\Mock\GetCwd;

function getcwd() {
    echo "ho";
    return GetCwd::getcwd();
}