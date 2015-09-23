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

use Chrome\Authorisation\Adapter\Simple;
use Recaptcher\Recaptcha;
use Chrome\View\Plugin\Facade;

/**
 * loads dependencies from composer
 */
require_once LIB . 'vendor/autoload.php';

/**
 * load chrome-php core
 */
require_once LIB . 'core/core.php';

/**
 * Application for web requests
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Application
 */
class DefaultApplication implements Application_Interface
{
    /**
     *
     * @var \Chrome\Registry\Logger\Registry_Interface
     */
    protected $_loggerRegistry = null;

    /**
     *
     * @var \Chrome\Context\Application_Interface
     */
    protected $_applicationContext = null;

    /**
     *
     * @var \Chrome\Context\Model_Interface
     */
    protected $_modelContext = null;

    /**
     *
     * @var \Chrome\Filter\Chain\Preprocessor
     */
    private $_preprocessor = null;

    /**
     *
     * @var \Chrome\Filter\Chain\Postprocessor
     */
    private $_postprocessor = null;

    /**
     *
     * @var \Chrome\Controller\Controller_Interface
     */
    private $_controller = null;

    /**
     *
     * @var \Chrome\Router\Router_Interface
     */
    private $_router = null;

    /**
     *
     * @var \Chrome\Exception\Handler_Interface
     */
    private $_exceptionHandler = null;

    /**
     *
     * @var \Chrome\Exception\Configuration_Interface
     */
    private $_exceptionConfiguration = null;

    /**
     *
     * @var \Chrome\Classloader\Autoloader_Interface
     */
    private $_classloader = null;

    /**
     *
     * @var \Chrome\DI\Container_Interface
     */
    protected $_diContainer = null;

    /**
     * Chrome_Front_Controller::getController()
     *
     * @return \Chrome\Controller\AbstractController
     */
    public function getController()
    {
        return $this->_controller;
    }

    /**
     *
     * @return Chrome_Front_Controller
     */
    public function __construct(\Chrome\Exception\Handler_Interface $exceptionHandler = null)
    {
        $this->_initLoggers();

        if($exceptionHandler === null)
        {
            require_once LIB . 'exception/handler/frontcontroller.php';
            $exceptionHandler = new \Chrome\Exception\Handler\HtmlStackTrace($this->_loggerRegistry->get('application'));
        }

        $this->_exceptionHandler = $exceptionHandler;
    }

    /**
     *
     * @return void
     */
    public function init()
    {
        try
        {
            $this->_init();
        } catch(\Chrome\Exception $e)
        {
            $this->_exceptionHandler->exception($e);
        }
    }

    /**
     * Set up all needed classes and dependencies
     *
     * @return void
     */
    protected function _init()
    {
        $this->_exceptionConfiguration = new \Chrome\Exception\Configuration();
        $this->_exceptionConfiguration->setExceptionHandler($this->_exceptionHandler);
        $this->_exceptionConfiguration->setErrorHandler(new \Chrome\Exception\Handler\DefaultErrorHandler());

        $viewContext = new \Chrome\Context\View();
        $this->_modelContext = new \Chrome\Context\Model();
        $this->_applicationContext = new \Chrome\Context\Application();

        $this->_applicationContext->setLoggerRegistry($this->_loggerRegistry);
        $this->_applicationContext->setViewContext($viewContext);
        $this->_applicationContext->setModelContext($this->_modelContext);

        $this->_initDiContainer();
        $closureHandler = $this->_diContainer->getHandler('closure');
        $registryHandler = $this->_diContainer->getHandler('registry');
        $registryHandler->add('\Chrome\Context\View_Interface', $viewContext);
        $registryHandler->add('\Chrome\Context\Model_Interface', $this->_modelContext);
        $registryHandler->add('\Chrome\Context\Application_Interface', $this->_applicationContext);

        $viewFactory = new \Chrome\View\Factory($this->_diContainer);
        $viewContext->setFactory($viewFactory);
        $registryHandler->add('\Chrome\View\Factory_Interface', $viewFactory);
        $registryHandler->add('\Chrome\Hash\Hash_Interface', new \Chrome\Hash\Hash());

        $this->_initClassloader();

        $this->_initDatabase();
        $registryHandler->add('Chrome\Database\Factory\Factory_Interface', $this->_modelContext->getDatabaseFactory());

        require_once LIB . 'core/classloader/model.php';
        require_once LIB.'core/database/facade/model.php';

        // init require-class, can be skipped if every class is defined
        $this->_classloader->prependResolver($this->_diContainer->get('\Chrome\Classloader\Resolver\Model_Interface'));

        $this->_initConfig();
        $registryHandler->add('\Chrome\Config\Config_Interface', $this->_applicationContext->getConfig());

        $this->_initRequestAndResponse();

        // startup filters
        $this->_preprocessor = new \Chrome\Filter\Chain\Preprocessor();
        $this->_postprocessor = new \Chrome\Filter\Chain\Postprocessor();

        $this->_initAuthenticationAndAuthorisation();
        $registryHandler->add('\Chrome\Authentication\Authentication_Interface', $this->_applicationContext->getAuthentication());

        $this->_initRouter();

        $pluginFacade = new \Chrome\View\Plugin\Facade();
	// TODO: remove pluginc facade
        $viewContext->setPluginFacade($pluginFacade);
        $viewContext->setLinker($this->_diContainer->get('\Chrome\Linker\Linker_Interface'));

        /**
         * @todo remove them from here
         */
        $pluginFacade->registerPlugin(new \Chrome\View\Plugin\Html($this->_applicationContext));
        $pluginFacade->registerPlugin(new \Chrome\View\Plugin\Decorator($this->_applicationContext));

        $this->_initConverter();
        $this->_initLocalization();
    }

    /**
     * Executes the controller
     *
     * @return void
     */
    public function execute()
    {
        try
        {
            // get the accessed resource by Router
            $resource = $this->_router->route(new \Chrome\URI\URI($this->_applicationContext->getRequestHandler()->getRequestData(), true), $this->_applicationContext->getRequestHandler()->getRequestData());

            $this->_classloader->load($resource->getClass());
            $this->_controller = $this->_diContainer->get($resource->getClass());

            $this->_controller->setExceptionHandler(new \Chrome\Exception\Handler\HtmlStackTrace());

            $this->_preprocessor->processFilters($this->_applicationContext->getRequestHandler()->getRequestData(), $this->_applicationContext->getResponse());

            $this->_controller->execute();

            $design = $this->_initDesign();

            $this->_applicationContext->getResponse()->write($design->render());

            $this->_postprocessor->processFilters($this->_applicationContext->getRequestHandler()->getRequestData(), $this->_applicationContext->getResponse());

            $this->_applicationContext->getResponse()->flush();
        } catch(\Chrome\Exception $e)
        {
            $this->_exceptionHandler->exception($e);
        }
    }

    protected function _initDesign()
    {
        // use the design from the controller, but only if he set one design
        if(($design = $this->_applicationContext->getDesign()) !== null) {
            return $design;
        }

        $design = new \Chrome\Design\Design();
        $this->_applicationContext->setDesign($design);

        $theme = $this->_diContainer->get('\Chrome\Design\Theme\ChromeOneSidebar');

        $theme->setDesign($design);
        $theme->setController($this->_controller);
        $theme->apply();

        return $design;
    }

    protected function _initDatabase()
    {
        $datbaseInitializer = new \Chrome\Database\Initializer\Initializer();
        $datbaseInitializer->initialize();

        $factory = $datbaseInitializer->getFactory();
        $factory->setLogger($this->_loggerRegistry->get('database'));

        $this->_modelContext->setDatabaseFactory($factory);
        /*
         * Testing...
              define('POSTGRESQL_HOST', 'localhost'); define('POSTGRESQL_USER', 'test'); define('POSTGRESQL_PASS', 'chrome');
              define('POSTGRESQL_DB', 'chrome_db'); define('POSTGRESQL_SCHEMA', 'chrome'); // 5433 -> 9.1, 5432 -> 9.2, 5434 -> 9.3
              define('POSTGRESQL_PORT', 5433); $dbRegistry = $factory->getConnectionRegistry();
              $postgresqlTestConnection = new \Chrome\Database\Connection\Postgresql();
              $postgresqlTestConnection->setConnectionOptions(POSTGRESQL_HOST, POSTGRESQL_USER, POSTGRESQL_PASS, POSTGRESQL_DB, POSTGRESQL_PORT, POSTGRESQL_SCHEMA);
              $postgresqlTestConnection->connect(); $dbRegistry->addConnection('postgresql_test', $postgresqlTestConnection);
              $dbRegistry->addConnection(Chrome\Database\Registry\Connection::DEFAULT_CONNECTION, $postgresqlTestConnection, true);
         */
    }

    protected function _initLocalization($locale = null)
    {
        if($locale === null)
        {
            $locale = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : CHROME_LOCALE_DEFAULT;
        }

        try
        {
            $locale = new \Chrome\Localization\Locale($locale);
            $localization = new \Chrome\Localization\Localization();
            $localization->setLocale($locale);

            // for testing
            if($locale->getPrimaryLanguage() == 'xx' AND $locale->getRegion() == 'XX') {
                require_once 'tests/dummies/localization/translate/test.php';
                $translate = new \Chrome\Localization\Translate_Test_XX($localization);
            } else {
                $translate = new \Chrome\Localization\Translate_Simple($localization);
            }

            // load default validate messages
            $translate->load('validate');
            #require_once 'tests/dummies/localization/translate/test.php';
            #   $translate = new \Chrome\Localization\Translate_Test_XX($localization);
            $localization->setTranslate($translate);
            $this->_applicationContext->getViewContext()->setLocalization($localization);
        } catch(\Chrome\Exception $e)
        {
            $this->_initLocalization(CHROME_LOCALE_DEFAULT);
        }
    }

    protected function _initLoggers()
    {
        $loggerDirectory = new \Chrome\Directory(TMP . CHROME_LOG_DIR);
        $loggerDirectory->create();

        $dateFormat = 'Y-m-d H:i:s:u';
        $output = '[%datetime%] %channel%.%level_name%: %message%. %context% %extra%' . PHP_EOL;

        $formatter = new \Monolog\Formatter\LineFormatter($output, $dateFormat);
        $processor = new \Chrome\Logger\Processor\Psr();
        $stream = new \Monolog\Handler\StreamHandler(TMP . CHROME_LOG_DIR . 'log.log');
        $streamDatabase = new \Monolog\Handler\StreamHandler(TMP . CHROME_LOG_DIR . 'database.log');
        $stream->setFormatter($formatter);
        $stream->pushProcessor($processor);
        $streamDatabase->setFormatter($formatter);
        $streamDatabase->pushProcessor($processor);

        $this->_loggerRegistry = new \Chrome\Registry\Logger\Registry();

        $loggers = array('application', 'router', 'autoloader');

        foreach($loggers as $loggerName)
        {
            $logger = new \Monolog\Logger($loggerName);
            $logger->pushHandler($stream);

            $this->_loggerRegistry->set($loggerName, $logger);
        }

        $databaseLogger = new \Monolog\Logger('database');
        $databaseLogger->pushHandler($streamDatabase);
        $this->_loggerRegistry->set('database', $databaseLogger);

        $this->_loggerRegistry->set(\Chrome\Registry\Logger\Registry::DEFAULT_LOGGER, $this->_loggerRegistry->get('application'));
    }

    protected function _initClassloader()
    {
        // autoloader
        $this->_classloader = new \Chrome\Classloader\Classloader(new \Chrome\Directory(BASEDIR));
        $this->_applicationContext->setClassloader($this->_classloader);

        // $this->_autoloader = new Chrome_Require_Autoloader();
        $this->_classloader->setLogger($this->_loggerRegistry->get('autoloader'));
        $this->_classloader->setExceptionHandler(new \Chrome\Exception\Handler\HtmlStackTrace());

        require_once PLUGIN . 'classloader/database.php';
        require_once PLUGIN . 'classloader/cache.php';
        require_once PLUGIN . 'classloader/model.php';

        $this->_classloader->appendResolver(new \Chrome\Classloader\Resolver\Database(new \Chrome\Directory('lib/core/database')));
        $this->_classloader->appendResolver(new \Chrome\Classloader\Resolver\Cache(new \Chrome\Directory('plugins/cache')));

        $autoloader = new \Chrome\Classloader\Autoloader($this->_classloader);
    }

    protected function _initAuthenticationAndAuthorisation()
    {
        $requestData = $this->_applicationContext->getRequestHandler()->getRequestData();
        $cookie = $requestData->getCookie();
        $session = $requestData->getSession();

        // setting up authentication, authorisation service
        $handler = new \Chrome\Exception\Handler\Authentication();

        $authentication = new \Chrome\Authentication\Authentication();
        $authentication->setExceptionHandler($handler);

        $dbAuth = new \Chrome\Authentication\Chain\DatabaseChain(new \Chrome\Model\Authentication\Database($this->_modelContext->getDatabaseFactory(), $this->_diContainer->get('\Chrome\Model\Database\Statement_Interface')));
        $cookieAuth = new \Chrome\Authentication\Chain\CookieChain(new \Chrome\Model\Authentication\Cookie($this->_modelContext->getDatabaseFactory(), $this->_diContainer->get('\Chrome\Model\Database\Statement_Interface')), $cookie, $this->_diContainer->get('\Chrome\Hash\Hash_Interface'));
        $sessionAuth = new \Chrome\Authentication\Chain\SessionChain($session);

        // set authentication chains in the right order
        // the first chain should be session, because its the fastest one
        // the last should be the slowest, thats the db
        $authentication->addChain($cookieAuth)->addChain($sessionAuth)->addChain($dbAuth);

        // first authentication
        // user gets authenticated if session or cookie is set
        // for db authentication use:
        //
        // $authentication->authenticate(new Chrome_Authentication_Resource_Database($userName, $password, $autoLogin));
        //
        // $authentication->authenticate(new Chrome_Authentication_Resource_Database('RedChrome', 'tiger', true));
        $authentication->authenticate();

        // set authorisation service
        // Chrome_Authorisation::setAuthorisationAdapter(Chrome_RBAC::getInstance(new Chrome_Model_RBAC_DB())); // better one, but not finished ;)
        $adapter = new \Chrome\Authorisation\Adapter\Simple($this->_diContainer->get('\Chrome\Model\Authorisation\Simple\Model_Interface'));
        $authorisation = new \Chrome\Authorisation\Authorisation($adapter, $authentication->getAuthenticationID());

        $this->_applicationContext->setAuthentication($authentication);
        $this->_applicationContext->setAuthorisation($authorisation);
    }

    protected function _initRequestAndResponse()
    {
        // distinct which request is sent
        $requestFactory = new \Chrome\Request\Factory();
        // set up the available request handler

        // watch out for the right order you add those handlers,
        // the more stricter handlers are the first, which get added, the less stricter are the last
        // the last one should _always_ return true in canHandleRequest
        // $request->addRequestObject();
        // this handler is always capable of handling a request, so it always returns true in canHandleRequest
        $hash = $this->_diContainer->get('\Chrome\Hash\Hash_Interface');
        $requestFactory->addRequestObject(new \Chrome\Request\Handler\HTTPHandler($hash, new \Chrome\Directory(TMP.CHROME_SESSION_SAVE_PATH)));
        #$requestFactory->addRequestObject(new \Chrome\Request\Handler\ConsoleHandler($hash));

        $reqHandler = $requestFactory->getRequest();
        $this->_applicationContext->setRequestHandler($requestFactory->getRequest());

        // distinct which response gets send
        $responseFactory = new \Chrome\Response\Factory();
        // set up the available response handlers

        $responseFactory->addResponseHandler(new \Chrome\Response\Handler\JSONHandler($reqHandler));
        $responseFactory->addResponseHandler(new \Chrome\Response\Handler\HTTPHandler($reqHandler));
        #$responseFactory->addResponseHandler(new \Chrome\Response\Handler\ConsoleHandler($reqHandler));

        $response = $responseFactory->getResponse();
        $this->_applicationContext->setResponse($response);
    }

    protected function _initConfig()
    {
        // configuration
        $closure = $this->_diContainer->getHandler('closure');

        $config = new \Chrome\Config\Config($this->_diContainer->get('\Chrome\Model\Config'));
        $this->_applicationContext->setConfig($config);
    }

    protected function _initRouter()
    {
        $this->_router = new \Chrome\Router\Router();
        $this->_router->setExceptionHandler(new \Chrome\Exception\Handler\HtmlStackTrace());
        // enable route matching

        $routerLogger = $this->_loggerRegistry->get('router');
        // import(array('\Chrome\Router\Route\StaticRoute', '\Chrome\Router\Route\DynamicRoute') );
        // matches static routes

        $this->_router->addRoute(new \Chrome\Router\Route\StaticRoute($this->_diContainer->get('\Chrome_Model_Route_Static_Interface'), $routerLogger));
        // matches dynamic created routes
        $this->_router->addRoute(new \Chrome\Router\Route\DynamicRoute($this->_diContainer->get('\Chrome_Model_Route_Dynamic_Interface'), $routerLogger));

        $this->_router->addRoute(new \Chrome\Router\Route\FallbackRoute($this->_diContainer->get('\Chrome\Config\Config_Interface')));
        // matches routes to administration site
        //$this->_router->addRoute(new Chrome_Route_Administration(new Chrome_Model_Route_Administration($this->_modelContext), $routerLogger));
    }

    protected function _initConverter()
    {
        $converter = new \Chrome\Converter\Converter();

        /**
         *
         * @todo remove them from here
         * @todo maybe delte autloader for converters? and create a factory?
         */
        $converter->addConverterDelegate(new \Chrome\Converter\Delegate\StringDelegate());
        $converter->addConverterDelegate(new \Chrome\Converter\Delegate\TypeCastingAndStrippingDelegate());

        $this->_applicationContext->setConverter($converter);
    }

    protected function _initDiContainer()
    {
        $this->_diContainer = new \Chrome\DI\Container();
        $this->_applicationContext->setDiContainer($this->_diContainer);
        require_once LIB . 'core/dependency_injection/closure.php';
        require_once LIB . 'core/dependency_injection/registry.php';
        require_once LIB . 'core/dependency_injection/controller.php';
        require_once LIB . 'core/dependency_injection/model.php';
        require_once LIB . 'core/dependency_injection/validator.php';
	    require_once LIB . 'core/dependency_injection/view.php';
        require_once LIB . 'core/dependency_injection/theme.php';

        $registry = new \Chrome\DI\Handler\Registry();
        $closure = new \Chrome\DI\Handler\Closure();
        $controller = new \Chrome\DI\Handler\Controller();
        $model = new \Chrome\DI\Handler\Model();
        $validator = new \Chrome\DI\Handler\Validator();
	    $view = new \Chrome\DI\Handler\View();
        $theme = new \Chrome\DI\Handler\Theme();


        $this->_diContainer->attachHandler('registry', $registry);
        $this->_diContainer->attachHandler('closure', $closure);
        $this->_diContainer->attachHandler('controller', $controller);
        $this->_diContainer->attachHandler('model', $model);
	    $this->_diContainer->attachHandler('view', $view);
	    $this->_diContainer->attachHandler('validator', $validator);
        $this->_diContainer->attachHandler('theme', $theme);

        $closure->add('\Chrome\Model\Config', function ($c) {

            $cacheOption = new \Chrome\Cache\Option\File\Serialization();
            $cacheOption->setCacheFile(new \Chrome\File(CACHE . '_config.cache'));
            $cache = new \Chrome\Cache\File\Serialization($cacheOption);

            return new \Chrome\Model\Config\Cache($c->get('\Chrome\Model\Config\Database'), $cache);
        }, true);

        $closure->add('\Chrome\Localization\Translate_Interface', function ($c) {
            return $c->get('\Chrome\Context\Application_Interface')->getViewContext()->getLocalization()->getTranslate();
        }, true);

        $registry->add('\Chrome\Localization\Localization_Interface', function ($c) {
            return $c->get('\Chrome\Context\Application_Interface')->getViewContext()->getLocalization();
        }, true);

        $closure->add('\Chrome\Model\Database\Statement_Interface', function ($c) {
            return new \Chrome\Model\Database\JsonStatement($c->get('\Chrome\Cache\Memory\DBStatement'), new \Chrome\Directory(RESOURCE . 'database'));
        });

        $closure->add('\Chrome_Model_Route_Static_Interface', function ($c) {
            $cacheOption = new \Chrome\Cache\Option\File\Serialization();
            $cacheOption->setCacheFile(new \Chrome\File(CACHE.'router/_static.cache'));
            $cache = new \Chrome\Cache\File\Serialization($cacheOption);

            return new \Chrome\Model\Route\StaticRoute\Cache($c->get('\Chrome_Model_Route_Static_Database'), $cache);
        }, true);

        $closure->add('\Chrome\Exception\Handler_Interface', function ($c) {
            return new \Chrome\Exception\Handler\HtmlStackTrace();
        });

        $closure->add('\Chrome_Model_Route_Static_Database', function ($c) {
            return $c->get('\Chrome\Model\Route\StaticRoute\Database');
        }, true);

        $closure->add('\Chrome\Design\Loader_Interface', function ($c) {
            //$viewFactory = $c->get('\Chrome_View_Factory_Interface');
            $model = $c->get('\Chrome\Model\Design\StaticLoader_Interface');
            return new \Chrome\Design\StaticLoader($c, $model);
        });

        $closure->add('\Chrome\Model\Design\StaticLoader_Interface', function ($c) {
            $cacheOption = new \Chrome\Cache\Option\File\Serialization();
            $cacheOption->setCacheFile(new \Chrome\File(CACHE.'_designLoaderStatic.cache'));
            $cache = new \Chrome\Cache\File\Serialization($cacheOption);

            return new \Chrome\Model\Design\StaticLoaderCache($c->get('\Chrome\Model\Design\StaticLoaderDatabase'), $cache);
        }, true);

        $closure->add('\Chrome\Classloader\Resolver\Model_Interface', function ($c) {
            return new \Chrome\Classloader\Resolver\Model($c->get('\Chrome_Model_Classloader_Model_Interface'), $c);
        });

        $closure->add('\Chrome\Classloader\Resolver\Filter', function ($c) {
           return new \Chrome\Classloader\Resolver\Filter(new \Chrome\Directory('plugins/filter'));
        });

        $closure->add('\Chrome\Classloader\Resolver\Exception', function ($c) {
            return new \Chrome\Classloader\Resolver\Exception(new \Chrome\Directory('lib/exception'));
        });

        $closure->add('\Chrome\Classloader\Resolver\Validator', function ($c) {
            return new \Chrome\Classloader\Resolver\Validator(new \Chrome\Directory('plugins/validate'));
        });

        $closure->add('\Chrome\Classloader\Resolver\Form', function ($c) {
            return new \Chrome\Classloader\Resolver\Form(new \Chrome\Directory('lib/core/form'), new \Chrome\Directory('plugins/view/form'));
        });

        $closure->add('\Chrome\Classloader\Resolver\Converter', function ($c) {
           return new \Chrome\Classloader\Resolver\Converter(new \Chrome\Directory('plugins/converter'));
        });

        $closure->add('\Chrome\Classloader\Resolver\Captcha', function ($c) {
            return new \Chrome\Classloader\Resolver\Captcha(new \Chrome\Directory('plugins/captcha'));
        });

        $closure->add('\Chrome\Classloader\Resolver\Theme', function ($c) {
           return new \Chrome\Classloader\Resolver\Theme(new \Chrome\Directory('themes'));
        });

        $closure->add('\Chrome_Model_Classloader_Model_Interface', function ($c) {

            $cacheOption = new \Chrome\Cache\Option\File\Serialization();
            $cacheOption->setCacheFile(new \Chrome\File(CACHE.'_require.cache'));
            $cache = new \Chrome\Cache\File\Serialization($cacheOption);

            return new \Chrome\Model\Classloader\Cache($c->get('\Chrome\Model\Classloader\Database'), $cache);
        }, true);

        $closure->add('\Chrome\Model\Authorisation\Simple\Model_Interface', function ($c) {
            $model = $c->get('\Chrome\Model\Authorisation\Adapter\Simple\Database');
            $model->setResourceModel($c->get('\Chrome\Resource\Model_Interface'));
            return $model;
        });

        $closure->add('\Chrome_Model_Route_Dynamic_Interface', function ($c) {
            $cacheOption = new \Chrome\Cache\Option\File\Serialization();
            $cacheOption->setCacheFile(new \Chrome\File(CACHE.'router/_dynamic.cache'));
            $cache = new \Chrome\Cache\File\Serialization($cacheOption);

            return new \Chrome\Model\Route\DynamicRoute\Cache($c->get('\Chrome\Model\Route\DynamicRoute\Database'), $cache);
        });

        $closure->add('\Chrome\Controller\User\Register', function ($c) {
            return new \Chrome\Controller\User\Register($c->get('\Chrome\Context\Application_Interface'), $c->get('\Chrome\Interactor\User\Registration_Interface'), new \Chrome\View\User\Register($c->get('\Chrome\Context\View_Interface')));
        });

        $closure->add('\Chrome\Interactor\User\Registration_Interface', function ($c) {
            require_once LIB.'modules/user/interactors/registration.php';

            $return = new \Chrome\Interactor\User\Registration($c->get('\Chrome\Config\Config_Interface'), $c->get('\Chrome\Model\User\Registration_Interface'), $c->get('\Chrome\Hash\Hash_Interface'));
            $emailValidator = $c->get('\Chrome\Validator\User\Registration\Email');
            $nameValidator = new \Chrome\Validator\User\NameValidator();
            $passwordValidator = new \Chrome\Validator\General\Password\PasswordValidator();
            $return->setValidators($emailValidator, $nameValidator, $passwordValidator);
            return $return;
        });

        $closure->add('\Chrome\Validator\User\Registration\Email', function ($c) {
            return new \Chrome\Validator\User\Registration\EmailValidator($c->get('\Chrome\Config\Config_Interface'), $c->get('\Chrome\Helper\User\Email_Interface'));
        });

        $closure->add('\Chrome\Helper\User\Email_Interface', function ($c) {
            require_once LIB.'modules/user/helpers/email.php';

            return new \Chrome\Helper\User\Email($c->get('\Chrome\Model\User\User_Interface'), $c->get('\Chrome\Model\User\Registration_Interface'));
        }, true);

        $closure->add('\Chrome\Model\User\User_Interface', function ($c) {
            require_once LIB.'modules/user/models/user.php';

            return $c->get('\Chrome\Model\User\User');
        });

        $closure->add('\Chrome\Model\User\Registration_Interface', function ($c) {
            require_once LIB.'modules/user/models/registration.php';

            return $c->get('\Chrome\Model\User\Registration');
        });

        $closure->add('\Chrome\Cache\Memory\DBStatement', function ($c) {
            // fix this cache, only one instance!
            return $c->get('\Chrome\Cache\Memory');
        }, true);

        $closure->add('\Chrome\Cache\Memory', function ($c) {
            return new \Chrome\Cache\Memory();
        });

        $closure->add('\Chrome\Redirection\Redirection_Interface', function ($c) {
            return new \Chrome\Redirection\Redirection($c->get('\Chrome\Context\Application_Interface'));
        });

        $closure->add('\Chrome\Resource\Model_Interface', function ($c) {
            return $c->get('\Chrome\Model\Resource\Database');
        }, true);

        $closure->add('\Chrome\Linker\HTTP\Helper\Model\Static_Interface', function ($c) {
            $model = $c->get('\Chrome_Model_Route_Static_Database');
            $model->setResourceModel($c->get('\Chrome\Resource\Model_Interface'));
            return $model;
        }, true);

        $closure->add('\Chrome\Linker\Linker_Interface', function ($c) {
            $linker = new \Chrome\Linker\HTTP\Linker(new \Chrome\URI\URI($c->get('\Chrome\Context\Application_Interface')->getRequestHandler()->getRequestData(), true), $c->get('\Chrome\Resource\Model_Interface'));

            require_once LIB.'core/linker/http/relative.php';
            require_once LIB.'core/linker/http/url.php';
            require_once LIB.'core/linker/http/static.php';

            $linker->addResourceHelper(new \Chrome\Linker\HTTP\RelativeHelper());
            $linker->addResourceHelper(new \Chrome\Linker\HTTP\StaticHelper($c->get('\Chrome\Linker\HTTP\Helper\Model\Static_Interface')));
            $linker->addResourceHelper(new \Chrome\Linker\HTTP\UrlHelper());

            return $linker;
        }, true);

        $closure->add('\Chrome\Interactor\User\Login_Interface', function ($c) {
            return new \Chrome\Interactor\User\Login($c->get('\Chrome\Authentication\Authentication_Interface'), $c->get('\Chrome\Helper\User\AuthenticationResolver_Interface'));
        });

        $closure->add('\Chrome\View\Form\Element\Factory\Default', function ($c) {

            $captchaFactory = new \Chrome\View\Form\Factory\Element\Captcha();
            $elementFactory = new \Chrome\View\Form\Factory\Element\Suffix('Html');

            $compositionFactory = new \Chrome\View\Form\Factory\Element\Composition($captchaFactory, $elementFactory);

            $defaultManipulateableDecorator = new \Chrome\View\Form\Factory\Element\DefaultManipulateableDecorator();
            $defaultAppenderDecorator = new \Chrome\View\Form\Factory\Element\DefaultAppenderDecorator($c->get('\Chrome\Localization\Translate_Interface'));

            $defaultDecoratorFactory = new \Chrome\View\Form\Factory\Element\Decorable($compositionFactory, $defaultManipulateableDecorator);
            return new \Chrome\View\Form\Factory\Element\Decorable($defaultDecoratorFactory, $defaultAppenderDecorator);
        });

        $closure->add('\Chrome\View\Form\Element\Factory\Yaml', function ($c) {

                $captchaFactory = new \Chrome\View\Form\Factory\Element\Captcha();
                $elementFactory = new \Chrome\View\Form\Factory\Element\Suffix('Html');

                $compositionFactory = new \Chrome\View\Form\Factory\Element\Composition($captchaFactory, $elementFactory);

                $defaultManipulateableDecorator = new \Chrome\View\Form\Factory\Element\DefaultManipulateableDecorator();
                $yamlDecorator = new \Chrome\View\Form\Factory\Element\YamlDecorator($c->get('\Chrome\Localization\Translate_Interface'));

                $defaultDecoratorFactory = new \Chrome\View\Form\Factory\Element\Decorable($compositionFactory, $defaultManipulateableDecorator);
                return new \Chrome\View\Form\Factory\Element\Decorable($defaultDecoratorFactory, $yamlDecorator);
        });

        $closure->add('\Chrome\View\Form\Factory\Option\Factory', function ($c) {
            return new \Chrome\View\Form\Factory\Option\Factory();
        });

        $closure->add('\Chrome\Controller\User\Login', function ($c) {
            $controller = new \Chrome\Controller\User\Login($c->get('\Chrome\Context\Application_Interface'), $c->get('\Chrome\Interactor\User\Login_Interface'));
            $controller->setExceptionHandler($c->get('\Chrome\Exception\Handler_Interface'));
            return $controller;
        });

        $closure->add('\Chrome\Helper\User\AuthenticationResolver_Interface', function ($c) {
            return new \Chrome\Helper\User\AuthenticationResolver\Email($c->get('\Chrome\Model\User\User_Interface'));
        });

        $closure->add('\Recaptcher\RecaptchaInterface', function ($c) {
            $config = $c->get('\Chrome\Config\Config_Interface');
            $privateKey = $config->getConfig('Captcha/Recaptcha', 'private_key');
            $publicKey  = $config->getConfig('Captcha/Recaptcha', 'public_key');
            $useHttps   = $config->getConfig('Captcha/Recaptcha', 'enable_https');

            return new \Recaptcher\Recaptcha($publicKey, $privateKey, $useHttps);
        });

        $closure->add('\Chrome\Controller\User\Logout', function ($c) {
            return new \Chrome\Controller\User\Logout($c->get('\Chrome\Context\Application_Interface'), $c->get('\Chrome\Interactor\User\Logout'));
        });

        $closure->add('\Chrome\Interactor\User\Logout', function ($c) {
            return new \Chrome\Interactor\User\Logout($c->get('\Chrome\Interactor\User\Login_Interface'), $c->get('\Chrome\Redirection\Redirection_Interface'));
        });

        $closure->add('\Chrome\Form\Module\User\Login', function($c) {
            $form = new \Chrome\Form\Module\User\Login($c->get('\Chrome\Context\Application_Interface'));
            $form->create();

            return $form;
        }, true);

        $closure->add('\Chrome\View\Form\Module\User\Login', function ($c) {
            $viewForm = new \Chrome\View\Form\Module\User\Login($c->get('\Chrome\Form\Module\User\Login'), $c->get('\Chrome\Context\View_Interface'));
            $viewForm->setElementFactory($c->get('\Chrome\View\Form\Element\Factory\Yaml'));
            $viewForm->setElementOptionFactory($c->get('\Chrome\View\Form\Factory\Option\Factory'));
            return $viewForm;
        }, true);

        $closure->add('\Chrome\View\User\UserMenu\FormRenderer', function ($c) {
            return new \Chrome\View\User\Login\FormRenderer($c->get('\Chrome\View\Form\Module\User\Login'), $c->get('\Chrome\Context\View_Interface'));
        });

        $closure->add('\Chrome\View\Captcha\FormRenderer', function ($c) {
           return new \Chrome\View\Captcha\FormRenderer($c->get('\Chrome\View\Form\Module\Captcha\Captcha'));
        });

        $closure->add('\Chrome\Form\Module\Captcha\Captcha', function ($c) {
           return new \Chrome\Form\Module\Captcha\Captcha($c->get('\Chrome\Context\Application_Interface'));
        });

        $closure->add('\Chrome\View\Form\Module\Captcha\Captcha', function ($c) {
            $viewForm = new \Chrome\View\Form\Module\Captcha\Captcha($c->get('\Chrome\Form\Module\Captcha\Captcha'), $c->get('\Chrome\Context\View_Interface'));
            $viewForm->setElementFactory($c->get('\Chrome\View\Form\Element\Factory\Yaml'));
            $viewForm->setElementOptionFactory($c->get('\Chrome\View\Form\Factory\Option\Factory'));
            return $viewForm;
        });

        $closure->add('\Chrome\Form\User\Register\StepOne', function ($c) {
            return new \Chrome\Form\Module\User\Register\StepOne($c->get('\Chrome\Context\Application_Interface'));
        });

        $closure->add('\Chrome\View\User\Register\Form\StepOne', function ($c) {
            $viewForm = new \Chrome\View\Form\Module\User\Register\StepOne($c->get('\Chrome\Form\User\Register\StepOne'), $c->get('\Chrome\Context\View_Interface'));
            $viewForm->setElementOptionFactory($c->get('\Chrome\View\Form\Factory\Option\Factory'));
            $viewForm->setElementFactory($c->get('\Chrome\View\Form\Element\Factory\Yaml'));
            return $viewForm;
        });

        $closure->add('\Chrome\View\User\Register\Form\Renderer\StepOne', function ($c) {
            return new \Chrome\View\User\Register\Form\Renderer\StepOne($c->get('\Chrome\View\User\Register\Form\StepOne'));
        });

        $closure->add('\Chrome\Form\User\Register\StepTwo', function ($c) {
            return new \Chrome\Form\Module\User\Register\StepTwo($c->get('\Chrome\Context\Application_Interface'));
        });

        $closure->add('\Chrome\View\User\Register\Form\StepTwo', function ($c) {
            $formView = new \Chrome\View\Form\Module\User\Register\StepTwo($c->get('\Chrome\Form\User\Register\StepTwo'), $c->get('\Chrome\Context\View_Interface'));
            $formView->setElementFactory($c->get('\Chrome\View\Form\Element\Factory\Yaml'));
            $formView->setElementOptionFactory($c->get('\Chrome\View\Form\Factory\Option\Factory'));
            return $formView;
        });

        $closure->add('\Chrome\View\User\Register\Form\Renderer\StepTwo', function ($c) {
            return new \Chrome\View\User\Register\Form\Renderer\StepTwo($c->get('\Chrome\View\User\Register\Form\StepTwo'));
        });

        $closure->add('\Chrome\View\User\Login\FormRenderer', function ($c) {
           return new \Chrome\View\User\Login\FormRenderer($c->get('\Chrome\View\Form\Module\User\Login'));
        });

        $closure->add('\Chrome\Interactor\User\Registration', function ($c) {
            $config = $c->get('\Chrome\Config\Config_Interface');
            $hash = $c->get('\Chrome\Hash\Hash_Interface');
            $registrationModel = $c->get('\Chrome\Model\User\Registration_Interface');
            return new \Chrome\Interactor\User\Registration($config, $registrationModel, $hash);
        });

        $closure->add('\Chrome\Controller\User\Register', function ($c) {
            $appContext = $c->get('\Chrome\Context\Application_Interface');
            $interactor = $c->get('\Chrome\Interactor\User\Registration');
            $view = $c->get('\Chrome\View\User\Register');
            return new \Chrome\Controller\User\Register($appContext, $interactor, $view);
        });

        $closure->add('\Chrome\View\Form\Module\Index\Index', function ($c) {
            $formView = new \Chrome\View\Index\Form($c->get('\Chrome\Form\Module\Index\Index'), $c->get('\Chrome\Context\View_Interface'));
            $formView->setElementOptionFactory($c->get('\Chrome\View\Form\Factory\Option\Factory'));
            $formView->setElementFactory($c->get('\Chrome\View\Form\Element\Factory\Yaml'));
            return $formView;
        });

        $closure->add('\Chrome\Form\Module\Index\Index', function ($c) {
            return new \Chrome\Form\Module\Index\Index($c->get('\Chrome\Context\Application_Interface'));
        });
    }

    /**
     * setExceptionHandler()
     *
     * @param mixed $obj
     * @return void
     */
    public function setExceptionHandler(\Chrome\Exception\Handler_Interface $obj)
    {
        $this->_exceptionHandler = $obj;
    }

    /**
     * getExceptionHandler()
     *
     * @return \Chrome\Exception\Handler_Interface
     */
    public function getExceptionHandler()
    {
        return $this->_exceptionHandler;
    }

    public function getApplicationContext()
    {
        return $this->_applicationContext;
    }

    public function getDiContainer()
    {
        return $this->_diContainer;
    }

    public function getExceptionConfiguration()
    {
        return $this->_exceptionConfiguration;
    }
}
