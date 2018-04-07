<?php

namespace De\Idrinth\TestGenerator\Twig;

use Twig\Token as TT;

class Token extends TT
{
    /**
     * @var int
     */
    private $prepend;

    /**
     * @param int $type
     * @param string $value
     * @param int $lineno
     * @param int $prepend
     */
    public function __construct($type, $value, $lineno, $prepend)
    {
        parent::__construct($type, $value, $lineno);
        $this->prepend = $prepend;
    }

    /**
     * Whitespace to prepend to new lines
     * @return int
     */
    public function getPrepend()
    {
        return $this->prepend;
    }
}
