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

/**
 * Interface for loading required files and loading classes
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Classloader
 */
interface Resolver_Model_Interface extends Resolver_Interface
{
    public function getClasses();

    public function getRequiredFiles();
}

/**
 * This class resolves classes which are defined in $_model
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Classloader
 */
class Resolver_Model implements Resolver_Model_Interface
{
    /**
     * Contains Chrome_Model_Abstract instance
     *
     * @var Chrome_Model_Abstract
     */
    protected $_model = null;

    /**
     * Contains required files
     *
     * @var array
     */
    protected $_require = array();

    /**
     * Contains dir to a class
     *
     * @var array
     */
    protected $_class = array();

    /**
     * Determines whether {@see loadRequiredFiles()} was called
     *
     * @var boolean
     */
    protected $_requiredFilesLoaded = false;

    public function __construct(\Chrome_Model_Interface $model)
    {
        $this->_model = $model;

        $this->_getClasses();
    }

    /**
     * Loads all required files
     *
     * @return void
     */
    public function init(Classloader_Interface $classloader)
    {
        // already loaded required files
        if($this->_requiredFilesLoaded === true)
        {
            return;
        }

        $this->_require = $this->_model->getRequirements();

        foreach($this->_require as $value)
        {
            $classloader->loadByFile($value['name'], BASEDIR . $value['path']);

            if($value['is_class_resolver'] == true)
            {
                $classloader->appendResolver(new $value['name']());
            }
        }

        $this->_requiredFilesLoaded = true;
    }

    /**
     * Gets saved classes from model
     *
     * @return void
     */
    protected function _getClasses()
    {
        $this->_class = $this->_model->getClasses();
    }

    /**
     * Get all classes saved in model
     * Structure:
     * array(array($class => $file), array(etc...), )
     *
     * @return array
     */
    public function getClasses()
    {
        return $this->_class;
    }

    public function resolve($className)
    {
        if(isset($this->_class[$className]))
        {
            return BASEDIR . $this->_class[$className];
        }

        return false;
    }

    public function getRequiredFiles()
    {
        return $this->_require;
    }
}
