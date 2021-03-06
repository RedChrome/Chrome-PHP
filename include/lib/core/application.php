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
 * @package CHROME-PHP
 * @subpackage Chrome.Application
 */

namespace Chrome\Application;

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Application
 */
interface Application_Interface extends \Chrome\Exception\Processable_Interface
{
    /**
     * getController()
     *
     * @return
     *
     */
    public function getController();

    /**
     * init()
     *
     *
     * @param Application_Interface $parentApplication [optional]
     * @return void
     */
    public function init(Application_Interface $parentApplication = null);

    /**
     * execute()
     *
     * @return void
     */
    public function execute();

    /**
     * Returns the current application context instance
     *
     * @return \Chrome\Context\Application_Interface
     */
    public function getApplicationContext();

    /**
     * Returns the exception configuration for this application
     *
     * @return \Chrome\Exception\Configuration_Interface
     */
    public function getExceptionConfiguration();

    /**
     * Returns the dependency injection container
     *
     * @return \Chrome\DI\Container_Interface
     */
    public function getDiContainer();
}

namespace Chrome\Context;

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Application.Context
 */
interface View_Interface
{
    /**
     * Sets the localization
     *
     * The localization is used to use the proper layout of representing e.g. currency/date/etc..
     *
     * @param \Chrome\Localization\Localization_Interface $localization
     */
    public function setLocalization(\Chrome\Localization\Localization_Interface $localization);

    /**
     * Returns the localization
     *
     * @return \Chrome\Localization\Localization_Interface
     */
    public function getLocalization();

    /**
     * Sets the plugin facade.
     *
     * The plugin facade is used to provide additional functionality for displaying views.
     *
     * @param \Chrome\View\Plugin\Facade_Interface $pluginFacade
     */
    public function setPluginFacade(\Chrome\View\Plugin\Facade_Interface $pluginFacade);

    /**
     * Returns the plugin facade, set via setPluginFacade
     *
     * @return \Chrome\View\Plugin\Facade_Interface
     */
    public function getPluginFacade();

    /**
     * Returns a linker
     *
     * @return \Chrome\Linker\Linker_Interface
     */
    public function getLinker();

    /**
     * Sets a linker
     *
     * @param \Chrome\Linker\Linker_Interface $linker
     */
    public function setLinker(\Chrome\Linker\Linker_Interface $linker);

    /**
     * Sets the view factory.
     *
     * A view factory is used to create views by only passing it's class name.
     *
     * @param \Chrome\View\Factory_Interface $factory
     */
    public function setFactory(\Chrome\View\Factory_Interface $factory);

    /**
     * Returns a view factory
     *
     * @return \Chrome\View\Factory_Interface
     */
    public function getFactory();

    /**
     * See {@link \Chrome\Context\View_Interface::getConfig()}, why there is no setLoggerRegistry
     *
     * @return Chrome\Registry\Logger\Registry_Interface
     */
    public function getLoggerRegistry();

    /**
     * There is no setConfig(), because this object contains only a reference of config from application_context
     * So to change $_config, you need to be in application_context scope and use setConfig there.
     *
     * @return \Chrome\Config\Config_Interface
     */
    public function getConfig();

    /**
     * This method is needed to link the view context with the application context
     * E.g.
     * this method links the config object from app context with view context.
     */
    public function linkApplicationContext(Application_Interface $app);
}

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Application.Context
 */
interface Model_Interface
{
    /**
     * Sets a database factory
     *
     * A database factory is able to create a new database interface. This database interface is able to
     * interact with the database. The required options are provided in buildInterface()
     *
     * @param \Chrome\Database\Factory\Factory_Interface $factory
     */
    public function setDatabaseFactory(\Chrome\Database\Factory\Factory_Interface $factory);

    /**
     * Returns a database factory
     *
     * @return \Chrome\Database\Factory\Factory_Interface
     */
    public function getDatabaseFactory();

    /**
     * Returns a config instance
     *
     * @return \Chrome\Config\Config_Interface
     */
    public function getConfig();

    /**
     * Returns a logger registry instance
     *
     * @return \Chrome\Registry\Logger\Registry_Interface
     */
    public function getLoggerRegistry();

    /**
     * Returns a converter instance
     *
     * @return Chrome_Converter_Delegator_Interface
     */
    public function getConverter();

    /**
     * Links objects, which need to be accessed from view/model context with the application context.
     * Thus the needed objects are the same (e.g. config, loggerRegistry, ..)
     *
     * @param \Chrome\Context\Application_Interface $app
     */
    public function linkApplicationContext(Application_Interface $app);
}

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Application.Context
 */
interface Application_Interface
{
    /**
     * available objects to link, via getReference()
     *
     * @var string
     */
    const VARIABLE_CONFIG = 'config', VARIABLE_LOGGER_REGISTRY = 'loggerRegistry', VARIABLE_CONVERTER = 'converter';

    /**
     * Returns the request variable as a reference.
     *
     * @param string $variable
     *        see VARIABLE_* as arguments
     * @return mixed
     */
    public function &getReference($variable);

    /**
     * Sets the Dependency injection container
     *
     * @param \Chrome\DI\Container_Interface $container
     */
    public function setDiContainer(\Chrome\DI\Container_Interface $container);

    /**
     * Returns the dependency injection container
     *
     * @return \Chrome\DI\Container_Interface
     */
    public function getDiContainer();

    /**
     * Sets the request context
     *
     * The request context contains all data of the current request from the client, including session
     * and cookie data
     *
     * @param \Chrome\Request\RequestContext_Interface
     */
    public function setRequestContext(\Chrome\Request\RequestContext_Interface $context);

    /**
     * Returns the request context
     *
     * @return \Chrome\Request\RequestContext_Interface
     */
    public function getRequestContext();

    /**
     * Sets the authentication service
     *
     * The authentication service is able to authenticate a client. Mostly this is done in the application
     *
     * @param \Chrome\Authentication\Authentication_Interface $auth
     */
    public function setAuthentication(\Chrome\Authentication\Authentication_Interface $auth);

    /**
     * Returns the authetication service
     *
     * @return \Chrome\Authentication\Authentication_Interface
     */
    public function getAuthentication();

    /**
     * Sets the model context
     *
     * The model context contains context information/objects for each Model object
     *
     * @param \Chrome\Context\Model_Interface $modelContext
     */
    public function setModelContext(\Chrome\Context\Model_Interface $modelContext);

    /**
     * Returns the model context
     *
     * @return \Chrome\Context\Model_Interface
     */
    public function getModelContext();

    /**
     * Sets the authorisation service
     *
     * The authorisation service is used to authorize a client. Which means: this class says you, whether the
     * client is allowed to do something or not.
     *
     * @param \Chrome\Authorisation\Authorisation_Interface $auth
     */
    public function setAuthorisation(\Chrome\Authorisation\Authorisation_Interface $auth);

    /**
     * Returns the authorisation service
     *
     * @return \Chrome\Authorisation\Authorisation_Interface
     */
    public function getAuthorisation();

    /**
     * Sets the response object
     *
     * The response object handles every content, which gets send to the client
     *
     * @param \Chrome\Response\Response_Interface $response
     */
    public function setResponse(\Chrome\Response\Response_Interface $response);

    /**
     * Returns the response object
     *
     * @return \Chrome\Response\Response_Interface
     */
    public function getResponse();

    /**
     * Sets the view context
     *
     * The view context contains all information/objects for each View object
     *
     * @param \Chrome\Context\View_Interface $viewContext
     */
    public function setViewContext(\Chrome\Context\View_Interface $viewContext);

    /**
     * Returns the view context
     *
     * @return \Chrome\Context\View_Interface
     */
    public function getViewContext();

    /**
     * Sets the config object
     *
     * The config object contains all configuration
     *
     * @param \Chrome\Config\Config_Interface $config
     */
    public function setConfig(\Chrome\Config\Config_Interface $config);

    /**
     * Returns the config object
     *
     * @return \Chrome\Config\Config_Interface
     */
    public function getConfig();

    /**
     * Sets the logger registry
     *
     * The logger registry contains a logger objects, to log something
     *
     * @param \Chrome\Registry\Logger\Registry_Interface $registry
     */
    public function setLoggerRegistry(\Chrome\Registry\Logger\Registry_Interface $registry);

    /**
     * Returns the logger registry
     *
     * @return \Chrome\Registry\Logger\Registry_Interface
     */
    public function getLoggerRegistry();

    /**
     * Sets a design
     *
     * A design is a wrapper for a view composition. Thus a design only contains the entry point to render all registered views.
     *
     * @param \Chrome\Design\Design_Interface $design
     */
    public function setDesign(\Chrome\Design\Design_Interface $design);

    /**
     * Returns a design object
     *
     * @return \Chrome\Design\Design_Interface
     */
    public function getDesign();

    /**
     * Sets a classloader
     *
     * The class loader loads classes...
     *
     * @param \Chrome\Classloader\Classloader_Interface $classloader
     */
    public function setClassloader(\Chrome\Classloader\Classloader_Interface $classloader);

    /**
     * Returns the class loader
     *
     * @return \Chrome\Classloader\Classloader_Interface
     */
    public function getClassloader();

    /**
     * Sets a converter instance
     *
     * @param \Chrome\Converter\Converter_Interface $converter
     */
    public function setConverter(\Chrome\Converter\Converter_Interface $converter);

    /**
     * Returns a converter instance
     *
     * @return \Chrome\Converter\Converter_Interface
     */
    public function getConverter();
}

class Application implements Application_Interface
{
    protected $_requestContext = null;
    protected $_authentication = null;
    protected $_authorisation = null;
    protected $_response = null;
    protected $_modelContext = null;
    protected $_viewContext = null;
    protected $_config = null;
    protected $_converter = null;
    protected $_loggerRegistry = null;
    protected $_design = null;
    protected $_classloader = null;
    protected $_diContainer = null;

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
            case self::VARIABLE_CONVERTER:
                {
                    return $this->_converter;
                }
        }
        /*
         * This works: $var = $this->getConfig(); return $var; but this not: return $this->getConfig();
         */
    }

    public function setDiContainer(\Chrome\DI\Container_Interface $container)
    {
        $this->_diContainer = $container;
    }

    public function getDiContainer()
    {
        return $this->_diContainer;
    }

    public function setConfig(\Chrome\Config\Config_Interface $config)
    {
        $this->_config = $config;
    }

    public function getConfig()
    {
        return $this->_config;
    }

    public function setViewContext(\Chrome\Context\View_Interface $viewContext)
    {
        $this->_viewContext = $viewContext;
        $viewContext->linkApplicationContext($this);
    }

    public function getViewContext()
    {
        return $this->_viewContext;
    }

    public function setModelContext(\Chrome\Context\Model_Interface $modelContext)
    {
        $this->_modelContext = $modelContext;
        $modelContext->linkApplicationContext($this);
    }

    public function getModelContext()
    {
        return $this->_modelContext;
    }

    public function setRequestContext(\Chrome\Request\RequestContext_Interface $context)
    {
        $this->_requestContext = $context;
    }

    public function getRequestContext()
    {
        return $this->_requestContext;
    }

    public function setAuthentication(\Chrome\Authentication\Authentication_Interface $auth)
    {
        $this->_authentication = $auth;
    }

    public function getAuthentication()
    {
        return $this->_authentication;
    }

    public function setAuthorisation(\Chrome\Authorisation\Authorisation_Interface $auth)
    {
        $this->_authorisation = $auth;
    }

    public function getAuthorisation()
    {
        return $this->_authorisation;
    }

    public function setResponse(\Chrome\Response\Response_Interface $response)
    {
        $this->_response = $response;
    }

    public function getResponse()
    {
        return $this->_response;
    }

    public function setConverter(\Chrome\Converter\Converter_Interface $converter)
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

    public function setDesign(\Chrome\Design\Design_Interface $design)
    {
        $this->_design = $design;
    }

    public function getDesign()
    {
        return $this->_design;
    }

    public function getClassloader()
    {
        return $this->_classloader;
    }

    public function setClassloader(\Chrome\Classloader\Classloader_Interface $classloader)
    {
        $this->_classloader = $classloader;
    }
}
class Model implements Model_Interface
{
    protected $_databaseFactory = null;
    protected $_config = null;
    protected $_loggerRegistry = null;
    protected $_converter = null;

    public function linkApplicationContext(Application_Interface $app)
    {
        $this->_config = &$app->getReference(Application_Interface::VARIABLE_CONFIG);
        $this->_loggerRegistry = &$app->getReference(Application_Interface::VARIABLE_LOGGER_REGISTRY);
        $this->_converter = &$app->getReference(Application_Interface::VARIABLE_CONVERTER);
    }

    public function setDatabaseFactory(\Chrome\Database\Factory\Factory_Interface $factory)
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

    public function getConverter()
    {
        return $this->_converter;
    }
}

class View implements \Chrome\Context\View_Interface
{
    protected $_pluginFacade = null;
    protected $_factory = null;
    protected $_loggerRegistry = null;

    protected $_localization = null;
    protected $_linker = null;

    public function getLinker()
    {
        return $this->_linker;
    }

    public function setLinker(\Chrome\Linker\Linker_Interface $linker)
    {
        $this->_linker = $linker;
    }

    public function setLocalization(\Chrome\Localization\Localization_Interface $localization)
    {
        $this->_localization = $localization;
    }

    public function getLocalization()
    {
        return $this->_localization;
    }

    public function linkApplicationContext(Application_Interface $app)
    {
        $this->_config = &$app->getReference(Application_Interface::VARIABLE_CONFIG);
        $this->_loggerRegistry = &$app->getReference(Application_Interface::VARIABLE_LOGGER_REGISTRY);
    }

    public function getConfig()
    {
        return $this->_config;
    }

    public function setPluginFacade(\Chrome\View\Plugin\Facade_Interface $pluginFacade)
    {
        $this->_pluginFacade = $pluginFacade;
    }

    public function getPluginFacade()
    {
        return $this->_pluginFacade;
    }

    public function setFactory(\Chrome\View\Factory_Interface $factory)
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