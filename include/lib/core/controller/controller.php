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
 * @subpackage Chrome.Controller
 */

namespace Chrome\Controller;

use \Chrome\Exception\Processable_Interface;

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Controller
 */
interface Controller_Interface extends Processable_Interface
{
    /**
     * execute()
     *
     * @return void
     */
    public function execute();

    /**
     * Sets the application context
     *
     * @param Chrome_Context_Application_Interface $appContext
     * @return void
     */
    public function setApplicationContext(\Chrome_Context_Application_Interface $appContext);

    /**
     * Returns the application context
     *
     * @return Chrome_Context_Application_Interface
     */
    public function getApplicationContext();
}

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Controller
 */
abstract class ControllerAbstract implements Controller_Interface
{

    /**
     * Contains the context to the current application
     *
     * @var Chrome_Context_Application_Interface
     */
    protected $_applicationContext = null;

    /**
     * exceptionHandler class which takes care of thrown exceptions
     * <code>
     * ...
     * catch(\Chrome\Exception $e) {
     *  $this->_exceptionHandler->exception($e);
     * }
     * </code>
     *
     * @var \Chrome\Exception\Handler_Interface
     */
    protected $_exceptionHandler = null;

    /**
     * Requires all classes/files
     *
     * structure:
     * array('class' => array('class1', 'class2'), 'file' => array('include/lib/file.php'))
     *
     * @var array
     */
    protected $_require = array();

    /**
     * An instance of an interactor
     *
     * @var \Chrome\Interactor\Interactor_Interface
     */
    protected $_interactor = null;

    /**
     * contains an instance of Chrome_Model_Abstract
     *
     * @var Chrome_Model_Abstract
     */
    protected $_model = array();

    /**
     * @var Chrome_View_Interface
     */
    protected $_view = null;

    /**
     * array of filters
     *
     * structure:
     * array('filterChainName' => array($filterObj), $filterChainObj => array($filterObj2, $filterObj3))
     *
     * @var array
     */
    protected $_filter = null;

    /**
     *
     * @var Chrome_Form_Interface
     */
    protected $_form = null;

    /**
     *
     * @var \Chrome\Request\Handler_Interface
     */
    protected $_requestHandler = null;

    /**
     *
     * @var \Chrome\Request\Data_Interface
     */
    protected $_requestData = null;

    /**
     * _initialize()
     *
     * @return void
     */
    abstract protected function _initialize();

    /**
     * _execute()
     *
     * @return void
     */
    abstract protected function _execute();

    /**
     * _shutdown()
     *
     * @retrun void
     */
    abstract protected function _shutdown();

    public function __construct(\Chrome_Context_Application_Interface $appContext)
    {
        $this->setApplicationContext($appContext);

        $this->_setRequestHandler($appContext->getRequestHandler());
    }

    /**
     * _require()
     *
     * @return void
     */
    protected function _require()
    {
        if(isset($this->_require['file']))
        {
            foreach($this->_require['file'] as $fileName)
            {
                $file = new \Chrome\File($fileName);

                if($file->exists())
                {
                    require_once $file->getFileName();
                } else
                {
                    throw new \Chrome\Exception('Could not require file '.$file.'! The file does not exist in \Chrome\Controller\ControllerAbstract::_require()!');
                }
            }
        }

        if(isset($this->_require['class']))
        {
            foreach($this->_require['class'] as $class)
            {
                loadClass($class);
            }
        }
    }

    public function getModel()
    {
        return $this->_model;
    }

    public function getForm()
    {
        return $this->_form;
    }

    public function getView()
    {
        return $this->_view;
    }

    protected function _setRequestHandler(\Chrome\Request\Handler_Interface $obj)
    {
        $this->_requestHandler = $obj;
        $this->_requestData = $obj->getRequestData();
    }

    public function setExceptionHandler(\Chrome\Exception\Handler_Interface $obj)
    {
        $this->_exceptionHandler = $obj;
    }

    public function getExceptionHandler()
    {
        return $this->_exceptionHandler;
    }

    public function setApplicationContext(\Chrome_Context_Application_Interface $appContext)
    {
        $this->_applicationContext = $appContext;
    }

    public function getApplicationContext()
    {
        return $this->_applicationContext;
    }
}
