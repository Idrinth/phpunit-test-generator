<?php declare (strict_types=1);

namespace De\Idrinth\TestGenerator\Twig;

use Twig\Token as TT;

final class Token extends TT
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
    public function __construct(int $type, string $value, int $lineno, int $prepend)
    {
        parent::__construct($type, $value, $lineno);
        $this->prepend = $prepend;
    }

    /**
     * Whitespace to prepend to new lines
     * @return int
     */
    public function getPrepend(): int
    {
        return $this->prepend;
    }
}
