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
 * @category CHROME-PHP
 * @package CHROME-PHP
 * @author Alexander Book <alexander.book@gmx.de>
 * @copyright 2014 Chrome - PHP <alexander.book@gmx.de>
 */
namespace Chrome\Application;

/**
 * loads dependencies from composer
 */
require_once LIB . 'vendor/autoload.php';

/**
 * load chrome-php core
 */
require_once LIB . 'core/core.php';

use Psr\Http\Message\ServerRequestInterface;
use Chrome\Router\Router_Interface;
use Chrome\DI\Container_Interface;

class WebApplication implements Application_Interface
{
    use \Chrome\Exception\ProcessableTrait;

    /**
     *
     * @var ServerRequestInterface
     */
    protected $_request = null;

    /**
     * @var Router_Interface
     */
    protected $_router = null;

    /**
     * @var Container_Interface
     */
    protected $_diContainer = null;

    /**
     * @var \Chrome\Context\Application_Interface
     */
    protected $_appContext = null;

    protected function _initDiContainer()
    {
        $this->_diContainer = new \Chrome\DI\Container();
        $this->_appContext->setDiContainer($this->_diContainer);

        require_once LIB.'core/dependency_injection/closure.php';

        $closure = new \Chrome\DI\Handler\Closure();

        $this->_diContainer->attachHandler('closure', $closure);

        $closure->add('\Chrome\Application\DefaultApplication', function ($c)
        {
            require_once APPLICATION.'default.php';

            return new \Chrome\Application\DefaultApplication();
        });

        $closure->add('\Chrome\Application\CaptchaApplication', function ($c)
        {
            require_once APPLICATION.'resource.php';
            require_once MODULE.'misc/captcha/application.php';

            $application = new \Chrome\Application\ResourceApplication();
            $application->setApplication('Chrome\Application\Captcha\Application');
            return $application;
        });
    }

    public function init(Application_Interface $app = null)
    {
        $this->_appContext = new \Chrome\Context\Application();

        $this->_initDiContainer();

        $this->_request = \Zend\Diactoros\ServerRequestFactory::fromGlobals($_SERVER, $_GET, $_POST, $_COOKIE, $_FILES);

        $context = new \Chrome\Request\Context($this->_request);
        $this->_appContext->setRequestContext($context);

        require_once LIB.'core/router/route/application.php';

        $model = new \Chrome\Model\Route\ApplicationModel\Model();
        $model->addApplicationResolve('/public/captcha', '\Chrome\Application\CaptchaApplication');
        $model->addApplicationResolve('/', '\Chrome\Application\DefaultApplication');

        $this->_router = new \Chrome\Router\Router();
        $this->_router->setBasepath(ROOT_URL);
        $this->_router->addRoute(new \Chrome\Router\Route\ApplicationRoute($model));
    }

    public function execute()
    {
        $resource = $this->_router->route($this->_request);

        $application = $this->_diContainer->get($resource->getClass());

        if($application instanceof Application_Interface) {
            $application->init($this);
            $application->execute();
        } else {
            echo 'unknown application';
        }
    }

    public function getController()
    {
        return null;
    }

    public function getApplicationContext()
    {
        return $this->_appContext;
    }

    public function getExceptionConfiguration()
    {
        return null;
    }

    public function getDiContainer()
    {
        return $this->_diContainer;
    }
}