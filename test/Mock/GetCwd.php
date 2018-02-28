<?php
namespace De\Idrinth\TestGenerator\Test\Mock;

class GetCwd
{
    private static $value = null;
    public function __construct($value)
    {
        self::$value = $value;
        echo "c";
    }
    public function __destruct()
    {
        echo "d";
        self::$value = null;
    }
    public static function getcwd() {
        echo "hi";
        return self::$value?:\getcwd();
    }
}