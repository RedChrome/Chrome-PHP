<?php

/**
 * CHROME-PHP CMS
 *
 * PHP version 5
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
 * @subpackage Chrome.Application
 */

if(CHROME_PHP !== true)
    die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.FrontController
 */
interface Chrome_Application_Interface extends Chrome_Exception_Processable_Interface
{
    /**
     * getController()
     *
     * @return
     */
    public function getController();

    /**
     * init()
     *
     * @return void
     */
    public function init();

    /**
     * execute()
     *
     * @return void
     */
    public function execute();

    /**
     * Returns the current application context instance
     *
     * @return Chrome_Context_Application_Interface
     */
    public function getApplicationContext();

    /**
     * Returns the exception configuration for this application
     *
     * @return Chrome_Exception_Configuration_Interface
     */
    public function getExceptionConfiguration();
}

interface Chrome_Context_View_Interface
{
    public function setPluginFacade(Chrome_View_Plugin_Facade_Interface $pluginFacade);

    public function getPluginFacade();

    public function setFactory(Chrome_View_Factory_Interface $factory);

    public function getFactory();

    public function getLoggerRegistry();

    /**
     * There is no setConfig(), because this object contains only a reference of config from application_context
     * So to change $_config, you need to be in application_context scope and use setConfig there.
     */
    public function getConfig();

    /**
     * This method is needed to link the view context with the application context
     * E.g. this method links the config object from app context with view context.
     */
    public function linkApplicationContext(Chrome_Context_Application_Interface $app);
}

interface Chrome_Context_Model_Interface
{
    public function setCacheFactoryRegistry(\Chrome\Registry\Cache\Factory\Registry $cacheFactoryRegistry);

    public function getCacheFactoryRegistry();

    public function setDatabaseFactory(Chrome_Database_Factory_Interface $factory);

    public function getDatabaseFactory();

    public function getConfig();

    public function getLoggerRegistry();

    public function linkApplicationContext(Chrome_Context_Application_Interface $app);
}

interface Chrome_Context_Application_Interface
{
    const VARIABLE_CONFIG = 'config',
          VARIABLE_LOGGER_REGISTRY = 'loggerRegistry';

    /**
     * Returns the request variable as a reference.
     *
     * @param string $variable see VARIABLE_* as arguments
     * @return mixed
     */
    public function &getReference($variable);

    public function setRequestHandler(Chrome_Request_Handler_Interface $reqHandler);

    public function getRequestHandler();

    public function setAuthentication(Chrome_Authentication_Interface $auth);

    public function getAuthentication();

    public function setModelContext(Chrome_Context_Model_Interface $modelContext);

    public function getModelContext();

    public function setAuthorisation(Chrome_Authorisation_Interface $auth);

    public function getAuthorisation();

    public function setResponse(Chrome_Response_Interface $response);

    public function getResponse();

    public function setViewContext(Chrome_Context_View_Interface $viewContext);

    public function getViewContext();

    public function setConfig(Chrome_Config_Interface $config);

    public function getConfig();

    public function setLoggerRegistry(\Chrome\Registry\Logger\Registry_Interface $registry);

    public function getLoggerRegistry();

    public function setControllerFactoryRegistry(\Chrome\Registry\Controller\Factory\Registry_Interface $registry);

    public function getControllerFactoryRegistry();

    public function setDesign(Chrome_Design_Interface $design);

    public function getDesign();

    public function setConverter(Chrome_Converter_Delegator_Interface $converter);

    public function getConverter();
}

class Chrome_Context_Application implements Chrome_Context_Application_Interface
{
    protected $_requestHandler  = null;

    protected $_authentication  = null;

    protected $_authorisation   = null;

    protected $_response        = null;

    protected $_modelContext    = null;

    protected $_viewContext     = null;

    protected $_config          = null;

    protected $_converter       = null;

    protected $_loggerRegistry  = null;

    protected $_design          = null;

    protected $_controllerFactroyRegistry = null;

    public function &getReference($variable)
    {
        switch($variable)
        {
            case self::VARIABLE_CONFIG:
                {
                    return $this->_config;
                }

            case self::VARIABLE_LOGGER_REGISTRY:
                {
                    return $this->_loggerRegistry;
                }
        }
        /*
         * This works:
            $var = $this->getConfig();
            return $var;
            but this not:
            return $this->getConfig();
        */
    }

    public function setControllerFactoryRegistry(\Chrome\Registry\Controller\Factory\Registry_Interface $registry)
    {
        $this->_controllerFactroyRegistry = $registry;
    }

    public function getControllerFactoryRegistry()
    {
        return $this->_controllerFactroyRegistry;
    }

    public function setConfig(Chrome_Config_Interface $config)
    {
        $this->_config = $config;
    }

    public function getConfig()
    {
        return $this->_config;
    }

    public function setViewContext(Chrome_Context_View_Interface $viewContext)
    {
        $this->_viewContext = $viewContext;
        $viewContext->linkApplicationContext($this);
    }

    public function getViewContext()
    {
        return $this->_viewContext;
    }

    public function setModelContext(Chrome_Context_Model_Interface $modelContext)
    {
        $this->_modelContext = $modelContext;
        $modelContext->linkApplicationContext($this);
    }

    public function getModelContext()
    {
        return $this->_modelContext;
    }

    public function setRequestHandler(Chrome_Request_Handler_Interface $reqHandler)
    {
        $this->_requestHandler = $reqHandler;
    }

    public function getRequestHandler()
    {
        return $this->_requestHandler;
    }

    public function setAuthentication(Chrome_Authentication_Interface $auth)
    {
        $this->_authentication = $auth;
    }

    public function getAuthentication()
    {
        return $this->_authentication;
    }

    public function setAuthorisation(Chrome_Authorisation_Interface $auth)
    {
        $this->_authorisation = $auth;
    }

    public function getAuthorisation()
    {
        return $this->_authorisation;
    }

    public function setResponse(Chrome_Response_Interface $response)
    {
        $this->_response = $response;
    }

    public function getResponse()
    {
        return $this->_response;
    }

    public function setConverter(Chrome_Converter_Delegator_Interface $converter)
    {
        $this->_converter = $converter;
    }

    public function getConverter()
    {
        return $this->_converter;
    }

    public function setLoggerRegistry(\Chrome\Registry\Logger\Registry_Interface $registry)
    {
        $this->_loggerRegistry = $registry;
    }

    public function getLoggerRegistry()
    {
        return $this->_loggerRegistry;
    }

    public function setDesign(Chrome_Design_Interface $design)
    {
        $this->_design = $design;
    }

    public function getDesign()
    {
        return $this->_design;
    }
}

class Chrome_Context_Model implements Chrome_Context_Model_Interface
{
    protected $_databaseFactory = null;

    protected $_config          = null;

    protected $_loggerRegistry  = null;

    protected $_cacheFactoryRegistry = null;

    public function linkApplicationContext(Chrome_Context_Application_Interface $app)
    {
        $this->_config = &$app->getReference(Chrome_Context_Application_Interface::VARIABLE_CONFIG);
        $this->_loggerRegistry = &$app->getReference(Chrome_Context_Application_Interface::VARIABLE_LOGGER_REGISTRY);
    }

    public function setCacheFactoryRegistry(\Chrome\Registry\Cache\Factory\Registry $cachFactoryRegistry)
    {
        $this->_cacheFactoryRegistry = $cachFactoryRegistry;
    }

    public function getCacheFactoryRegistry()
    {
        return $this->_cacheFactoryRegistry;
    }

    public function setDatabaseFactory(Chrome_Database_Factory_Interface $factory)
    {
        $this->_databaseFactory = $factory;
    }

    public function getDatabaseFactory()
    {
        return $this->_databaseFactory;
    }

    public function getConfig()
    {
        return $this->_config;
    }

    public function getLoggerRegistry()
    {
        return $this->_loggerRegistry;
    }
}

class Chrome_Context_View implements Chrome_Context_View_Interface
{
    protected $_pluginFacade = null;

    protected $_factory      = null;

    protected $_loggerRegistry  = null;

    public function linkApplicationContext(Chrome_Context_Application_Interface $app)
    {
        $this->_config = &$app->getReference(Chrome_Context_Application_Interface::VARIABLE_CONFIG);
        $this->_loggerRegistry = &$app->getReference(Chrome_Context_Application_Interface::VARIABLE_LOGGER_REGISTRY);
    }

    public function getConfig()
    {
        return $this->_config;
    }

    public function setPluginFacade(Chrome_View_Plugin_Facade_Interface $pluginFacade)
    {
        $this->_pluginFacade = $pluginFacade;
    }

    public function getPluginFacade()
    {
        return $this->_pluginFacade;
    }

    public function setFactory(Chrome_View_Factory_Interface $factory)
    {
        $this->_factory = $factory;
    }

    public function getFactory()
    {
        return $this->_factory;
    }

    public function getLoggerRegistry()
    {
        return $this->_loggerRegistry;
    }
}