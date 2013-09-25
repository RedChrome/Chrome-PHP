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
 * @package    CHROME-PHP
 * @subpackage Chrome.Require
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [14.07.2013 19:03:03] --> $
 * @author     Alexander Book
 */

namespace Chrome\Classloader;

use \Chrome\Logger\Loggable_Interface;
use \Psr\Log\LoggerInterface;

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Classloader.Autoloader
 */
interface Autoloader_Interface extends \Chrome_Exception_Processable_Interface, Loggable_Interface
{
    public function appendAutoloader(Classloader_Interface $classloader);

    public function prependAutoloader(Classloader_Interface $classloader);

    public function getAutoloaders();

    public function setAutoloaders(array $autoloaders);

    public function loadClass($className);

    public function loadClassByFile($className, $fileName);

    public function isClassLoaded($className);
}

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Classloader
 */
interface Classloader_Interface
{
    /**
     * Tries to load a file containing the class $className
     *
     * Returns false, if loader could not detect the file.
     * Returns string (file name), if loader could detect the file
     *
     * @return mixed
     */

    public function loadClass($className);

    /**
     * This should get used to load required files for a plugin or modules or anything else
     *
     * Put here your logic to load files.
     *
     * @param Autoloader_Interface $autoloader
     * @return void
     */

    public function init(Autoloader_Interface $autoloader);
}

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Require.Autoloader
 */
abstract class Classloader_Abstract implements Classloader_Interface
{

    public function init(Autoloader_Interface $autoloader)
    {
        // do nothing
    }
}

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Classloader.Autoloader
 */
class Autoloader implements Autoloader_Interface
{
    /**
     * @var Chrome_Exception_Handler_Interface
     */
    protected $_exceptionHandler = null;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $_logger = null;

    protected $_classloaders = array();

    protected $_loadedClasses = array();

    public function __construct()
    {
        spl_autoload_register(array($this, 'loadClass'));
    }

    public function appendAutoloader(Classloader_Interface $classloader)
    {
        $classloader->init($this);
        $this->_classloaders[] = $classloader;
    }

    public function prependAutoloader(Classloader_Interface $classloader)
    {
        $classloader->init($this);
        array_unshift($this->_classloaders, $classloader);
    }

    public function getAutoloaders()
    {
        return $this->_classloaders;
    }

    public function setAutoloaders(array $autoloaders)
    {
        $this->_classloaders = array();

        foreach($autoloaders as $autoloader) {
            if(!($autoloader instanceof Chrome_Require_Loader_Interface)) {
                throw new Chrome_InvalidArgumentException(
                        'An element of the array was not a subclass of interface Chrome_Require_Loader_Interface, given: '.get_class($autoloader));
            }

            array_push($this->_classloaders, $autoloader);
        }
    }

    public function _loadClass($className)
    {
        if(in_array($className, $this->_loadedClasses)) {
            return true;
        }

        foreach($this->_classloaders as $autoloader) {

            if(($file = $autoloader->loadClass($className)) !== false) {
                $this->_loadFile($file);
                return true;
            }
        }

        return false;
    }

    /**
     * Loads a file using require_once
     * If file does not exist an exception is thrown
     *
     * @param string $file file to include
     */

    protected function _loadFile($file)
    {
        if(_isFile($file)) {
            require_once $file;
        } else {
            throw new Chrome_Exception('Could not load file "'.$file.'"');
        }
    }

    public function loadClassByFile($className, $fileName)
    {
        try {
            if($this->isClassLoaded($className) === true) {
                return true;
            }

            $this->_loadFile($fileName);
            $this->_addClass($className);

        } catch(Chrome_Exception $e) {
            $this->_exceptionHandler->exception($e);
        }
    }

    /**
     * Loads the file containing the corresponding class
     *
     * @param string $className name of the class
     * @return boolean true on success
     */

    public function loadClass($className)
    {
        try {
            if($this->isClassLoaded($className) === true) {
                return true;
            }

            if($this->_loadClass($className) === true) {
                $this->_addClass($className);
            } else {
                $this->_logger->error('Could not load class {classname}', array('classname' => $className, 'bla' => 'blub'));
            }
        } catch(Chrome_Exception $e) {
            $this->_exceptionHandler->exception($e);
        }
    }

    public function isClassLoaded($className)
    {
        return in_array($className, $this->_loadedClasses);
    }

    protected function _addClass($className)
    {
        $this->_loadedClasses[] = $className;
    }

    public function setExceptionHandler(\Chrome_Exception_Handler_Interface $handler)
    {
        $this->_exceptionHandler = $handler;
    }

    public function getExceptionHandler()
    {
        return $this->_exceptionHandler;
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
