<?php
namespace De\Idrinth\TestGenerator\Test\Mock;

class GetCwd
{
    /**
     * @var string|null
     */
    private static $value = null;

    /**
     * set fake getcwd result
     * @param string $value
     */
    public function __construct($value)
    {
        self::$value = $value;
    }

    /**
     * unset faked value
     */
    public function __destruct()
    {
        self::$value = null;
    }

    /**
    * faked work dir returned
    * @return string
    */
    public static function getcwd()
    {
        return self::$value?:\getcwd();
    }
}
