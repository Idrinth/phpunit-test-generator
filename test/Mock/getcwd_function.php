<?php
namespace De\Idrinth\TestGenerator;

use De\Idrinth\TestGenerator\Test\Mock\GetCwd;

/**
 * faked work dir returned
 * @return string
 */
function getcwd()
{
    return GetCwd::getcwd();
}
