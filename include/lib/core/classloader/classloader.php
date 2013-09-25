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
 * @subpackage Chrome.Classloader
 */
namespace Chrome\Classloader;

use \Chrome\Logger\Loggable_Interface;
use \Psr\Log\LoggerInterface;

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Classloader
 */
interface Autoloader_Interface
{

    public function setClassloader(Classloader_Interface $classloader);

    public function getClassloader();

    public function load($class);
}

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Classloader
 */
interface Classloader_Interface extends \Chrome_Exception_Processable_Interface, Loggable_Interface
{

    /**
     * Tries to load a file containing the class $className
     *
     * Returns true if file could get loaded, false else
     *
     * @return boolean
     */
    public function load($class);

    public function loadByFile($class, $file);

    public function appendResolver(Resolver_Interface $resolver);

    public function prependResolver(Resolver_Interface $resolver);

    public function getResolvers();

    public function setResolvers(array $resolvers);

    public function isLoaded($class);
}

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Classloader
 */
interface Resolver_Interface
{

    /**
     * Tries to locate a file which contains the class $class
     *
     * Returns false if no file could get found, or a file containig the $class.
     *
     * @param string $class
     * @return string boolean
     */
    public function resolve($class);

    /**
     * This should get used to load required files for a plugin or modules or anything else
     *
     * Put here your logic to load files.
     *
     * @param Classloader_Interface $classloader
     * @return void
     */
    public function init(Classloader_Interface $classloader);
}

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Classloader
 */
abstract class Resolver_Abstract implements Resolver_Interface
{

    public function init(Classloader_Interface $classloader)
    {
        // do nothing
    }
}

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Classloader
 */
class Autoloader implements Autoloader_Interface
{
    /**
     *
     * @var Classloader_Interface
     */
    protected $_classloader = null;

    /**
     * Constructor
     *
     * Creates an autoloader class, using a classloader
     *
     * @param Classloader_Interface $classloader
     *        a classloader, used to load classes in load()
     */
    public function __construct(Classloader_Interface $classloader)
    {
        $this->setClassloader($classloader);
        spl_autoload_register(array($this, 'load'));
    }

    /**
     *
     * @see \Chrome\Classloader\Autoloader_Interface::getClassloader()
     */
    public function getClassloader()
    {
        return $this->_classloader;
    }

    /**
     *
     * @see \Chrome\Classloader\Autoloader_Interface::setClassloader()
     */
    public function setClassloader(Classloader_Interface $classloader)
    {
        $this->_classloader = $classloader;
    }

    /**
     *
     * @see \Chrome\Classloader\Autoloader_Interface::load()
     */
    public function load($class)
    {
        $this->_classloader->load($class);
    }
}

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Classloader
 */
class Classloader implements Classloader_Interface
{
    /**
     *
     * @var Chrome_Exception_Handler_Interface
     */
    protected $_exceptionHandler = null;

    /**
     *
     * @var \Psr\Log\LoggerInterface
     */
    protected $_logger = null;

    /**
     *
     * @var array
     */
    protected $_resolvers = array();

    /**
     *
     * @var array
     */
    protected $_loadedClasses = array();

    /**
     * loades $class by loading $fileName
     *
     * @param string $class
     * @param string $fileName
     */
    protected function _doLoadClassByFile($class, $fileName)
    {
        $this->_loadFile($fileName);

        $this->_addClass($class);
    }

    protected function _resolve($class)
    {
        foreach($this->_resolvers as $resolver)
        {
            if(($file = $resolver->resolve($class)) !== false)
            {
                return $file;
            }
        }

        return false;
    }

    /**
     * Loads a file using require_once
     * If file does not exist, an exception is thrown
     *
     * @param string $file
     *        file to include
     */
    protected function _loadFile($file)
    {
        if(_isFile($file))
        {
            require_once $file;
        } else
        {
            throw new \Chrome_Exception('Could not load file "' . $file . '"');
        }
    }

    /**
     *
     * @see \Chrome\Classloader\Classloader_Interface::load()
     */
    public function load($class)
    {
        try
        {
            if($this->isLoaded($class))
            {
                return true;
            }

            $fileName = $this->_resolve($class);

            if($fileName === false)
            {
                $this->_logger->error('Could not load class {classname}', array('classname' => $class));
                return false;
            }

            $this->_doLoadClassByFile($class, $fileName);

            return true;
        } catch(Chrome_Exception $e)
        {
            $this->_exceptionHandler->exception($e);
        }
    }

    public function isLoaded($class)
    {
        return in_array($class, $this->_loadedClasses) or class_exists($class, false);
    }

    protected function _addClass($className)
    {
        $this->_loadedClasses[] = $className;
    }

    /**
     *
     * @see \Chrome\Classloader\Classloader_Interface::loadByFile()
     */
    public function loadByFile($class, $fileName)
    {
        try
        {
            if($this->isLoaded($class) === true)
            {

                return true;
            }

            $this->_doLoadClassByFile($class, $fileName);
        } catch(Chrome_Exception $e)
        {
            $this->_exceptionHandler->exception($e);
        }
    }

    /**
     *
     * @see \Chrome\Classloader\Classloader_Interface::appendResolver()
     */
    public function appendResolver(Resolver_Interface $resolver)
    {
        $this->_resolvers[] = $resolver;
        $resolver->init($this);
    }

    /**
     *
     * @see \Chrome\Classloader\Classloader_Interface::prependResolver()
     */
    public function prependResolver(Resolver_Interface $resolver)
    {
        array_unshift($this->_resolvers, $resolver);
        $resolver->init($this);
    }

    /**
     *
     * @see \Chrome\Classloader\Classloader_Interface::getResolvers()
     */
    public function getResolvers()
    {
        return $this->_resolvers;
    }

    /**
     *
     * @see \Chrome\Classloader\Classloader_Interface::setResolvers()
     */
    public function setResolvers(array $resolvers)
    {
        $this->_resolvers = array();

        foreach($resolvers as $resolver)
        {
            if(!($resolver instanceof Resolver_Interface))
            {
                throw new \Chrome_InvalidArgumentException('The array $resolvers may only contain instances of Resolver_Interface');
            }

            $this->_resolvers[] = $resolver;
        }
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
