<?php
namespace De\Idrinth\TestGenerator\Test\Mock;

class GetCwd
{
    private static $value = null;
    public function __construct($value)
    {
        self::$value = $value;
    }
    public function __destruct()
    {
        self::$value = null;
    }
    public static function getcwd()
    {
        return self::$value?:\getcwd();
    }
}
