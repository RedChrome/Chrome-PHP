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
 * @subpackage Chrome.Controller
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [15.09.2011 22:06:26] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true)
    die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Controller
 */
interface Chrome_Controller_Interface
{
    /**
     * getRequest()
     * 
     * @return Chrome_Request_Interface
     */ 
    public function getRequest();

    /**
     * getResponse()
     * 
     * @return Chrome_Response_Interface
     */ 
    public function getResponse();
    
    /**
     * execute()
     * 
     * @return void
     */ 
    public function execute();
}

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Controller
 */
abstract class Chrome_Controller_Abstract implements Chrome_Controller_Interface, Chrome_Exception_Processable_Interface
{
    
    /**
     * specific ACE for this controller
     * 
     * 
     * @deprecated
     * @var Chrome_Authorisation_Resource_Interface
     */ 
    protected $ACE = null;

    /**
     * @deprecated
     * @var array
     */ 
    protected $errorHandler = array();

    /**
     * exceptionHandler class which takes care of thrown exceptions
     * <code>
     * ...
     * catch(Chrome_Exception $e) {
     *      $this->_exceptionHandler->exception($e);
     * }
     * </code>
     * 
     * @var Chrome_Exception_Handler_Interface
     */ 
    protected $exceptionHandler = null;

    /**
     * Requires all classes/files
     * 
     * structure:
     * array('class' => array('class1', 'class2'), 'file' => array('include/lib/file.php'))
     * 
     * @var array
     */ 
    protected $require = array();

    /**
     * this var contains all unescaped parameter($_GET, $_POST, etc..)
     * 
     * structure:
     * array('GET' => $_GET, 'POST' => $_POST, etc...)
     * 
     * @var array
     */ 
    protected $data = array();

    /**
     * @deprecated
     * @var Chrome_Router_Interface
     */ 
    protected $router = false;

    /**
     * contains a Chrome_Request_Interface object
     * 
     * @var Chrome_Request_Interface
     */ 
    protected $request = null;

    /**
     * contains an instance of Chrome_Responde_Interface
     * 
     * @var Chrome_Response_Interface
     */ 
    protected $response = null;

    /**
     * contains an instance of Chrome_Model_Abstract
     * 
     * @var Chrome_Model_Abstract
     */ 
    protected $model = array();

    /**
     * 
     * @var Chrome_View_Interface
     */ 
    protected $view = null;

    /**
     * array of filters
     * 
     * structure: 
     * array('filterChainName' => array($filterObj), $filterChainObj => array($filterObj2, $filterObj3))
     * 
     * @var array
     */ 
    protected $filter = null;

    /**
     * 
     * @var Chrome_Form_Interface
     */ 
    protected $form = null;

    /**
     * 
     * @var Chrome_Design_Composite_Interface
     */ 
    protected $design = null;

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
    
    /**
     * __construct()
     * 
     * @return Chrome_Controller_Abstract
     */ 
    abstract public function __construct();
    
    /**
     * singletone pattern
     */ 
    final private function __clone()
    {
    }

    /**
     * _authorize()
     * 
     * @return void
     */ 
    protected function _authorize()
    {
        if($this->ACE === null OR $this->ACE === false) {
            return true;
        }
        
        return Chrome_Authorisation::getInstance()->isAllowed($this->ACE);
    }

    /**
     * _require()
     * 
     * @return void
     */ 
    protected function _require()
    {
        if(isset($this->require['file'])) {
            foreach($this->require['file'] AS $file) {
                if(_isFile($file)) {
                    require_once $file;
                } else {
                    throw new Chrome_Exception('Could not require file '.$file.'! The file does not exist in Chrome_Controller_Abstract::_require()!');
                }
            }
        }

        if(isset($this->require['class'])) {
            foreach($this->require['class'] AS $class) {
                classLoad($class);                
            }
        }
    }

    /**
     * _validate()
     * 
     * @return void
     */ 
    protected function _validate()
    {
        $this->data = &$this->getRequest()->getParameters();
    }

    /**
     * _setFilter()
     * 
     * @return void
     */ 
    protected function _setFilter()
    {
        if(!is_array($this->filter)) {
            return;
        }

        $registry = Chrome_Registry::getInstance();

        foreach($this->filter AS $filterChain => $filters) {
            if(is_string($filterChain)) {
                $_filterChain = $registry->get('Chrome_Filter_Chain', 'Chrome_Filter_Chain_'.ucfirst($filterChain));
            } elseif($filterChain instanceof Chrome_Filter_Chain_Abstract) {
                $_filterChain = $filterChain;
            }

            if(!($_filterChain instanceof Chrome_Filter_Chain_Abstract)) {
                throw new Chrome_Exception('Cannot add a filter to a non-existing filter chain in Chrome_Controller_Abstract::_filter()!');
            }

            foreach($filters AS $filter) {
                $_filterChain->addFilter($filter);
            }
        }
    }

    protected function write($string)
    {
        $this->response->write($string);
    }

    public function getModel()
    {
        return $this->model;
    }

    public function getView()
    {
        return $this->view;
    }

    public function getForm()
    {
        return $this->form;
    }

    public function getACE()
    {
        return $this->ACE;
    }

    public function getRequest()
    {
        if($this->request === null) {
            $this->request = Chrome_Request::getInstance();
        }

        return $this->request;
    }

    public function getResponse()
    {
        if($this->response === null) {
            $this->response = Chrome_Response::getInstance();
        }

        return $this->response;
    }

    public function setDesign(Chrome_Design_Composite_Interface $obj)
    {
        $this->design = $obj;
    }

    public function getDesign()
    {
        return $this->design;
    }

    public function setExceptionHandler(Chrome_Exception_Handler_Interface $obj)
    {
        $this->exceptionHandler = $obj;
    }

    public function getExceptionHandler()
    {
        return $this->exceptionHandler;
    }
}