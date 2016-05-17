<?php

namespace Test\Chrome\Authentication;

use Mockery as M;

class FileTest extends \PHPUnit_Framework_TestCase
{
    protected $_existingFileName = 'tests/include/lib/core/file/testFile.test';

    protected $_notExistingFileName = 'test/include/lib/core/file/notTestFile.test';

    protected $_wrongFileName = 'tests/include/lib/core/~file#`´^!"§$%&/()=)';

    protected function _getExistingFile()
    {
        return new \Chrome\File($this->_existingFileName);
    }

    protected function _getNotExistingFile()
    {
        return new \Chrome\File($this->_notExistingFileName);
    }

    protected function _getWrongFile()
    {
        return new \Chrome\File($this->_wrongFileName);
    }

    public function testFileName()
    {
        $existingFile = $this->_getExistingFile();
        $this->assertEquals($this->_existingFileName, $existingFile->getFileName());

        $notExistingFile = $this->_getNotExistingFile();
        $this->assertEquals($this->_notExistingFileName, $notExistingFile->getFileName());
    }

    public function testExists()
    {
        $existingFile = $this->_getExistingFile();

        $this->assertTrue($existingFile->exists());
        // should not change...
        $this->assertTrue($existingFile->exists());

        $notExistingFile = $this->_getNotExistingFile();
        $this->assertFalse($notExistingFile->exists());

        $wrongFile = $this->_getWrongFile();
        $this->assertFalse($wrongFile->exists());
    }

    public function testExceptionOnWrongFileName()
    {
        $this->setExpectedException('Chrome\Exception');

        $file = new \Chrome\File($this->_notExistingFileName.'\\subdir.test');
    }

    /**
     * @depends testExists
     */
    public function testFileHandle()
    {
        $existingFile = $this->_getExistingFile();
        $this->assertNotTrue(is_resource($existingFile->getFileHandle()));
        $this->assertFalse($existingFile->isOpen());

        // opening twice should work fine..
        $existingFile->open(\Chrome\File_Interface::FILE_OPEN_BEGINNING_READ);
        $existingFile->open(\Chrome\File_Interface::FILE_OPEN_BEGINNING_READ);
        $this->assertInternalType('resource', $existingFile->getFileHandle());
        $this->assertTrue($existingFile->isOpen());

        $existingFile->close();
        $this->assertFalse($existingFile->isOpen());
        $this->assertNotTrue(is_resource($existingFile->getFileHandle()));

        $notExistingFile = $this->_getNotExistingFile();
        $this->assertNotTrue(is_resource($notExistingFile->getFileHandle()));

        try {
            $notExistingFile->open(\Chrome\File_Interface::FILE_OPEN_BEGINNING_READ);
        } catch(\Chrome\FileException $e) {
            $this->assertNotNull($e);
        }

        $this->assertFalse($notExistingFile->exists());
        $this->assertFalse($notExistingFile->isOpen());
        $this->assertNull($notExistingFile->getFileHandle());
    }

    public function testOpenMode()
    {
        $notExistingFile = $this->_getNotExistingFile();

        $modes = array(\Chrome\File_Interface::FILE_OPEN_BEGINNING_READ, \Chrome\File_Interface::FILE_OPEN_TRUNCATE_WRITE_ONLY, \Chrome\File_Interface::FILE_OPEN_ENDING_WRITE_ONLY);
        $mode = array_rand($modes, 1);

        try {
            $notExistingFile->open($mode);
        } catch(\Chrome\FileException $e) {
            // do nothing
        }

        $this->assertEquals($mode, $notExistingFile->getOpenMode());
    }

    public function testGetExtension()
    {
        $file = 'anyFile';
        $extension = 'ext';

        $file = new \Chrome\File($file.'.'.$extension);

        $this->assertEquals($extension, $file->getExtension());

        $this->assertTrue($file->hasExtension($extension));
        $this->assertTrue($file->hasExtension(strtoupper($extension)));

        $file = new \Chrome\File('anyFileName');
        $this->assertEquals('', $file->getExtension());
        $this->assertTrue($file->hasExtension(''));
    }

    public function testToString()
    {
        $existingFile = $this->_getExistingFile();

        $this->assertContains($this->_existingFileName, $existingFile->__toString());
    }

}