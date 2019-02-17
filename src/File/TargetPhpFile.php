<?php

namespace De\Idrinth\TestGenerator\File;

use SplFileInfo;

class TargetPhpFile 
{
    /**
     * @var string
     */
    private $file;

    /**
     * @var boolean
     */
    private $allowed;

    /**
     * @param SplFileInfo|null $file
     * @param string $mode
     */
    public function __construct($file, $mode)
    {
        $this->file = $file->getPathname();
        $this->allowed = $this->isAllowed($file, $mode);
    }

    /**
     * @param SplFileInfo $file
     * @param string $mode
     * @return boolean
     */
    private function isAllowed($file, $mode)
    {
        if (!$file instanceof SplFileInfo) {
            return false;
        }
        if ($file->isDir()) {
            return false;
        }
        if (!is_dir($file->getPath()) && !mkdir($file->getPath(), 0777, true)) {
            return false;
        }
        if (!$file->isFile()) {
            return true;
        }
        if ($mode === 'skip') {
            return false;
        }
        if ($mode === 'replace') {
            return true;
        }
        return rename($file->getPathname(), $file->getPathname().date('.YmdHi').'.old');
    }

    /**
     * @return boolean
     */
    public function mayWrite()
    {
        return $this->allowed;
    }

    /**
     * @param string $content
     * @return boolean
     * @throws RuntimeException
     */
    public function write($content)
    {
        if (!$this->allowed) {
            throw new \RuntimeException("Writing to {$this->file} is not allowed.");
        }
        return false !== file_put_contents($this->file, $content);
    }
}
