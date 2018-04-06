<?php

namespace De\Idrinth\TestGenerator\Twig;

use Twig\Token as TT;

class Token extends TT
{
    /**
     * @var int
     */
    private $column;

    /**
     * @param int $type
     * @param string $value
     * @param int $lineno
     * @param int $colnum
     */
    public function __construct($type, $value, $lineno, $colnum)
    {
        parent::__construct($type, $value, $lineno);
        $this->column = $colnum;
    }

    /**
     * @return type
     */
    public function getColumn()
    {
        return $this->column;
    }
}