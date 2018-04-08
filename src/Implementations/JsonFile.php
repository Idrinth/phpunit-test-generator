<?php

namespace De\Idrinth\TestGenerator\Implementations;

use InvalidArgumentException;
use SplFileInfo;
use De\Idrinth\TestGenerator\Interfaces\JsonFile as JFI;

class JsonFile implements JFI
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var array
     */
    private $content;

    /**
     * @param SplFileInfo|string $file
     * @throws InvalidArgumentException if file is unusable
     */
    public function __construct($file)
    {
        if(!$file instanceof SplFileInfo) {
            $file = new SplFileInfo($file);
        }
        if (!$file->isFile() || !$file->isReadable()) {
            throw new InvalidArgumentException("File $file doesn't exist or isn't readable.");
        }
        $data = json_decode(file_get_contents($file->getPathname()), true);
        if (!$data) {
            throw new InvalidArgumentException("File $file couldn't be parsed as json.");
        }
        $this->content = $data;
        $this->path = $file->getPath();
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return array
     */
    public function getContent()
    {
        return $this->content;
    }
}
