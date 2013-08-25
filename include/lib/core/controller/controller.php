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
 * @copyright Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version $Id: 0.1 beta <!-- phpDesigner :: Timestamp [27.03.2013 15:55:42] --> $
 * @author Alexander Book
 */
if(CHROME_PHP !== true)
    die();

require_once 'factory.php';

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Controller
 */
interface Chrome_Controller_Interface extends Chrome_Exception_Processable_Interface
{

    /**
     * execute()
     *
     * @return void
     */
    public function execute();

    /**
     *
     * @param Chrome_Context_Application_Interface $appContext
     * @return Chrome_Controller_Interface
     */
    public function __construct(Chrome_Context_Application_Interface $appContext);

    /**
     * Sets the application context
     *
     * @param Chrome_Context_Application_Interface $appContext
     * @return void
     */
    public function setApplicationContext(Chrome_Context_Application_Interface $appContext);

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
abstract class Chrome_Controller_Abstract implements Chrome_Controller_Interface
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
     * catch(Chrome_Exception $e) {
     * $this->_exceptionHandler->exception($e);
     * }
     * </code>
     *
     * @var Chrome_Exception_Handler_Interface
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
     * contains an instance of Chrome_Model_Abstract
     *
     * @var Chrome_Model_Abstract
     */
    protected $_model = array();

    /**
     *
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
     * @var Chrome_Request_Handler_Interface
     */
    protected $_requestHandler = null;

    /**
     *
     * @var Chrome_Request_Data_Interface
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

    public function __construct(Chrome_Context_Application_Interface $appContext)
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
            foreach($this->_require['file'] as $file)
            {
                if(_isFile($file))
                {
                    require_once $file;
                } else
                {
                    throw new Chrome_Exception('Could not require file ' . $file . '! The file does not exist in Chrome_Controller_Abstract::_require()!');
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

    /**
     * _setFilter()
     *
     * @return void
     */
    protected function _setFilter()
    {
        if(!is_array($this->_filter))
        {
            return;
        }

        $registry = Chrome_Registry::getInstance();

        foreach($this->_filter as $filterChain => $filters)
        {
            if(is_string($filterChain))
            {
                $_filterChain = $registry->get('Chrome_Filter_Chain', 'Chrome_Filter_Chain_' . ucfirst($filterChain));
            } elseif($filterChain instanceof Chrome_Filter_Chain_Abstract)
            {
                $_filterChain = $filterChain;
            }

            if(!($_filterChain instanceof Chrome_Filter_Chain_Abstract))
            {
                throw new Chrome_Exception('Cannot add a filter to a non-existing filter chain in Chrome_Controller_Abstract::_filter()!');
            }

            foreach($filters as $filter)
            {
                $_filterChain->addFilter($filter);
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

    protected function _setRequestHandler(Chrome_Request_Handler_Interface $obj)
    {
        $this->_requestHandler = $obj;
        $this->_requestData = $obj->getRequestData();
    }

    public function setExceptionHandler(Chrome_Exception_Handler_Interface $obj)
    {
        $this->_exceptionHandler = $obj;
    }

    public function getExceptionHandler()
    {
        return $this->_exceptionHandler;
    }

    public function setApplicationContext(Chrome_Context_Application_Interface $appContext)
    {
        $this->_applicationContext = $appContext;
    }

    public function getApplicationContext()
    {
        return $this->_applicationContext;
    }
}
