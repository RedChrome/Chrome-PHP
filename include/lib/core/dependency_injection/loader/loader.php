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

use Psr\Log\LoggerInterface;

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

class ClassIterator implements Loader_Interface, \Chrome\Logger\Loggable_Interface
{
    protected $_iterator = null;

    protected $_logger = null;

    public function __construct(\Iterator $iterator)
    {
        $this->_iterator = $iterator;
    }

    public function load(\Chrome\DI\Container_Interface $diContainer)
    {
        foreach ($this->_iterator as $element) {
            try {
                if (is_string($element) && class_exists($element, false)) {
                    $obj = new $element();
                    $obj->load($diContainer);
                } else {
                    throw new \Chrome\Exception('Class does not exist or is not valid');
                }
            } catch (\Chrome\Exception $e) {
                if ($this->_logger !== null) {
                    $this->_logger->warning('Could not load dependency definition from class {class} with exception message {message}', array(
                        'class' => $element,
                        'message' => $e->getMessage()
                    ));
                } else {
                    throw e;
                }
            }
        }
    }

    public function setLogger(LoggerInterface $logger)
    {
        $this->_logger = $logger;
    }

    public function getLogger()
    {
        return $this->_logger;
    }
}

class StructuredDirectory implements Loader_Interface, \Chrome\Logger\Loggable_Interface
{
    protected $_dir = null;

    protected $_logger = null;

    public function __construct(\Chrome\Directory_Interface $structuredDirectory)
    {
        $this->_dir = $structuredDirectory;
    }

    public function load(\Chrome\DI\Container_Interface $diContainer)
    {
        $fileNameIterator = $this->_dir->getFileIterator();

        $fileIterator = new \ArrayIterator();
        $classIterator = new \ArrayIterator();

        foreach ($fileNameIterator as $fileName) {
            try {

                $file = $this->_dir->file($fileName, false);

                $file->requireOnce();

                $classIterator->append($this->_fileToClass(basename($fileName)));
            } catch (\Chrome\Exception $e) {
                if ($this->_logger !== null) {
                    $this->_logger->warning('Could not load file {file} with error message {message}', array(
                        'file' => $fileName,
                        'message' => $e->getMessage()
                    ));
                } else {
                    throw $e;
                }
            }
        }

        return $classIterator;
        /*
        $loaderClassIterator = new ClassIterator($classIterator);

        if ($this->_logger !== null) {
            $loaderClassIterator->setLogger($this->_logger);
        }

        $loaderClassIterator->load($diContainer);
        */
    }

    protected function _fileToClass($file)
    {
        $matches = array();

        if (preg_match('~([0-9]{1,})_(\w{1,}).php~i', $file, $matches) > 0) {
            return ucfirst('\\Chrome\\DI\\Loader\\' . $matches[2]);
        } else {
            throw new \Chrome\Exception('File "' . $file . '" does not have the correct format');
        }
    }

    public function setLogger(LoggerInterface $logger)
    {
        $this->_logger = $logger;
    }

    public function getLogger()
    {
        return $this->_logger;
    }
}