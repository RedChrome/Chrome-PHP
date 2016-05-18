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
 * load error & exception classes
 */
require_once LIB . 'core/error/error.php';

/**
 * load file functions
 */
require_once LIB . 'core/file/file.php';
require_once LIB . 'core/file/directory.php';

/**
 * load Chrome_Hash for easy hashing
 */
require_once LIB . 'core/hash/hash.php';

/**
 * load request factory
 */
require_once LIB . 'core/request/request.php';

/**
 * load response factory
 */
require_once LIB . 'core/response/response.php';

/**
 * load URI class
 */
// require_once LIB.'core/uri.php';

/**
 * load application interfaces
 */
require_once LIB . 'core/application.php';

/**
 * loads dependencies from composer
 */
require_once LIB . 'vendor/autoload.php';
require_once LIB . 'core/registry/object.php';
require_once LIB . 'core/log/log.php';
require_once LIB . 'core/classloader/classloader.php';

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Application
 */
class ResourceApplication implements \Chrome\Application\Application_Interface
{

    /**
     * Contains the application class
     *
     * @var string
     */
    protected $_appClass = '';

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
     * @var \Chrome\Exception\Handler_Interface
     */
    private $_exceptionHandler = null;

    /**
     *
     * @var \Chrome\Exception\Configuration_Interface
     */
    private $_exceptionConfiguration = null;

    /**
     * Chrome_Front_Controller::getController()
     *
     * @return \Chrome\Controller\AbstractController
     */
    public function getController()
    {
        return null;
    }

    public function __construct(\Chrome\Exception\Handler_Interface $handler = null)
    {
        if ($handler === null) {
            require_once LIB . 'exception/handler/htmlstacktrace.php';
            $handler = new \Chrome\Exception\Handler\HtmlStackTrace();
        }

        $this->_exceptionHandler = $handler;
    }

    /**
     * Set up all needed classes and dependencies
     *
     * @return void
     */
    public function init(Application_Interface $app = null)
    {
        $this->_exceptionConfiguration = new \Chrome\Exception\Configuration();
        $this->_exceptionConfiguration->setExceptionHandler($this->_exceptionHandler);
        $this->_exceptionConfiguration->setErrorHandler(new \Chrome\Exception\Handler\DefaultErrorHandler());

        $viewContext = new \Chrome\Context\View();
        $this->_modelContext = new \Chrome\Context\Model();
        $this->_applicationContext = new \Chrome\Context\Application();

        $this->_applicationContext->setViewContext($viewContext);
        $this->_applicationContext->setModelContext($this->_modelContext);

        // require_once LIB . 'core/request/request/http.php';
        require_once LIB . 'core/response/response/http.php';

        $hash = new \Chrome\Hash\Hash();

        $request = \Zend\Diactoros\ServerRequestFactory::fromGlobals($_SERVER, $_GET, $_POST, $_COOKIE, $_FILES);
        $cookie = new \Chrome\Request\Cookie\Cookie($request, $hash);
        $session = new \Chrome\Request\Session\Session($cookie, $request, $hash, new \Chrome\Directory(TMP . CHROME_SESSION_SAVE_PATH));

        $this->_applicationContext->setRequestContext(new \Chrome\Request\Context($request, $cookie, $session));

        $response = new \Chrome\Response\HTTP($request->getServerParams()['SERVER_PROTOCOL']);
        $this->_applicationContext->setResponse($response);

        // $requestFactory->addRequestObject(new \Chrome\Request\Handler\HTTPHandler(new \Chrome\Hash\Hash(), new \Chrome\Directory(TMP.CHROME_SESSION_SAVE_PATH)));

        // $reqHandler = $requestFactory->getRequest();
        // $requestData = $requestFactory->getRequestDataObject();

        $this->_initClassloader();

        // $this->_applicationContext->setRequestHandler($reqHandler);
        // $session = $requestData->getSession();
        // $cookie = $requestData->getCookie();

        // $responseFactory = new \Chrome\Response\Factory();

        // $responseFactory->addResponseHandler(new \Chrome\Response\Handler\HTTPHandler($reqHandler));

        // $response = $responseFactory->getResponse();
        // $this->_applicationContext->setResponse($response);
    }

    protected function _initClassloader()
    {
        // classloader
        $this->_classloader = new \Chrome\Classloader\Classloader(new \Chrome\Directory(BASEDIR));
        $this->_applicationContext->setClassloader($this->_classloader);

        // $this->_classloader->setLogger($this->_loggerRegistry->get('autoloader'));
        $this->_classloader->setExceptionHandler(new \Chrome\Exception\Handler\HtmlStackTrace());

        require_once PLUGIN . 'classloader/captcha.php';

        $this->_classloader->appendResolver(new \Chrome\Classloader\Resolver\Captcha(new \Chrome\Directory('plugins/captcha')));

        $autoloader = new \Chrome\Classloader\Autoloader($this->_classloader);
    }

    public function setApplication($appClass)
    {
        $this->_appClass = $appClass;
    }

    public function execute()
    {
        try {
            if (! class_exists($this->_appClass, false)) {
                throw new \Chrome\Exception('Could not find application class ' . $this->_appClass);
            }

            $class = new $this->_appClass($this);

            $class->execute();
        } catch (\Chrome\Exception $e) {
            $this->_exceptionHandler->exception($e);
        }
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

    public function getExceptionConfiguration()
    {
        return $this->_exceptionConfiguration;
    }

    public function getDiContainer()
    {
        return null;
    }
}
