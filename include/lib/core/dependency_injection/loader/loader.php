<?php

/**
 * CHROME-PHP CMS
 *
 * LICENSE
 *
 * This source file is subject to the Creative Commons license that is bundled
 * with this package in the file LICENSE.
 * It is also available through the world-wide-web at this URL:
 * http://creativecommons.org/licenses/by-nc-sa/3.0/
 * If you did not receive a copy of the license AND are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@chrome-php.de so we can send you a copy immediately.
 *
 * @package CHROME-PHP
 * @subpackage Chrome.DependencyInjection
 */
namespace Chrome\DI\Loader;

use Chrome\Logger\LoggableTrait;
use Chrome\Utils\Iterator\Mapper\MapToGraphIterator;
use Chrome\Utils\Iterator\Mapper\StructuredPhpFileToClassIterator;

/**
 * Interface to load dependency injection definitions
 *
 * @package CHROME-PHP
 * @subpackage Chrome.DependencyInjection
 */
interface Loader_Interface
{

    /**
     * Loads dependency injection definitions into $diContainer
     *
     * @param \Chrome\DI\Container_Interface $diContainer
     * @return void
     */
    public function load(\Chrome\DI\Container_Interface $diContainer);
}

class Composite implements Loader_Interface
{
    protected $_loaders = array();

    public function add(Loader_Interface $loader)
    {
        $this->_loaders[] = $loader;
    }

    public function load(\Chrome\DI\Container_Interface $diContainer)
    {
        foreach ($this->_loaders as $loader) {
            $loader->load($diContainer);
        }
    }
}

class StructuredFileIteratorLoader implements Loader_Interface, \Chrome\Logger\Loggable_Interface
{
    use LoggableTrait;

    protected $_iterator = null;

    protected $_dir = null;

    public function __construct(\Iterator $fileIterator, \Chrome\Directory_Interface $commonParentDirectory)
    {
        $this->_iterator = $fileIterator;
        $this->_dir = $commonParentDirectory;
    }

    public function load(\Chrome\DI\Container_Interface $diContainer)
    {
        $iterator = new MapToGraphIterator(new StructuredPhpFileToClassIterator($this->_iterator, '\\Chrome\\DI\\Loader\\'));

        foreach ($iterator as $fileAndClass) {
            try {
                $file = $fileAndClass[0];
                $class = $fileAndClass[1];

                $fileObj = $this->_dir->file($file, true);
                $fileObj->requireOnce();

                if (is_string($class) && class_exists($class, false)) {
                    $obj = new $class();
                    $obj->load($diContainer);
                } else {
                    throw new \Chrome\Exception('Class "'.$class.'" does not exist');
                }
            } catch (\Chrome\Exception $e) {
                if ($this->_logger !== null) {
                    $this->_logger->warning('Could not load dependency definition from class "{class}" with exception message "{message}"', array(
                        'class' => $class,
                        'message' => $e->getMessage()
                    ));
                } else {
                    throw $e;
                }
            }
        }
    }
}

class StructuredDirectoryLoader implements Loader_Interface, \Chrome\Logger\Loggable_Interface
{
    use LoggableTrait;

    protected $_dir = null;

    public function __construct(\Chrome\Directory_Interface $directory)
    {
        $this->_dir = $directory;
    }

    public function load(\Chrome\DI\Container_Interface $diContainer)
    {
        $loader = new StructuredFileIteratorLoader($this->_dir->getFileIterator(false), $this->_dir);

        if($this->_logger !== null) {
            $loader->setLogger($this->_logger);
        }

        $loader->load($diContainer);
    }
}