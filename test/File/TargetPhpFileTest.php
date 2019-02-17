<?php

namespace De\Idrinth\TestGenerator\Test\File;

use De\Idrinth\TestGenerator\File\TargetPhpFile;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use SplFileInfo;

class TargetPhpFileTest extends TestCase
{
    /**
     * @return string
     */
    private function getFileName()
    {
        return vfsStream::url('root/test/ClassFile.php');
    }

    /**
     * @return array
     */
    public function provideWrite()
    {
        return array(
            array('replace', true, false),
            array('skip', false, false),
            array('move', false, true)
        );
    }

    /**
     * @test
     * @dataProvider provideWrite
     * @expectedException RuntimeException
     * @expectedExceptionRegExp "Writing to .+ is not allowed."
     */
    public function testUnwriteableWrite()
    {
        $root = vfsStream::setup();
        $file = new TargetPhpFile(new SplFileInfo($this->getFileName()), 'skip');
        $this->assertTrue($file->mayWrite());
        $this->assertTrue($file->write('1st'));
        $this->checkFile($this->getFileName(), '1st');
        sleep(1);
        $file2 = new TargetPhpFile(new SplFileInfo($this->getFileName()), 'skip');
        $this->assertFalse($file2->mayWrite());
        $file2->write('2nd');
        unset($root);
    }

    /**
     * @test
     * @dataProvider provideWrite
     * @param string $mode
     * @param boolean $willChange
     * @param boolean $willMove
     */
    public function testWrite($mode, $willChange, $willMove)
    {
        $root = vfsStream::setup();
        $modified = $willChange||$willMove;
        $file = new TargetPhpFile(new SplFileInfo($this->getFileName()), $mode);
        $this->assertTrue($file->mayWrite());
        $this->assertTrue($file->write('1st'));
        $created = $this->checkFile($this->getFileName(), '1st');
        sleep(1);
        $file2 = new TargetPhpFile(new SplFileInfo($this->getFileName()), $mode);
        $this->assertEquals($modified, $file2->mayWrite());
        if ($modified) {
            $this->assertEquals($modified, $file2->write('2nd'));
        }
        $this->checkFile($this->getFileName().'.'.date('YmdHi').'.old', '1st', $willMove);
        $recreated = $this->checkFile($this->getFileName(), $modified?'2nd':'1st');
        $this->assertEquals($modified, $created !== $recreated, "$created $recreated");
        unset($root);
    }

    /**
     * check existence and return last modified time
     * @param string $path
     * @param string $text
     * @param string $exists
     * @return int
     */
    private function checkFile($path, $text, $exists = true)
    {
        clearstatcache();
        $this->assertEquals($exists, is_file($path));
        if ($exists) {
            $this->assertEquals($text, file_get_contents($path));
            return filemtime($path);
        }
        return 0;
    }
}
