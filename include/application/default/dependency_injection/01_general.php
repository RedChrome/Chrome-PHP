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
 * @subpackage Chrome.DependencyInjection
 */
namespace Chrome\DI\Loader;

use Psr\Http\Message\UriInterface;

class General implements Loader_Interface
{

    public function load(\Chrome\DI\Container_Interface $diContainer)
    {
        $closure = $diContainer->getHandler('closure');

        $this->_request($closure);
        $this->_localization($closure);
        $this->_model($closure);
        $this->_classloader($closure);
        $this->_cache($closure);
        $this->_linker($closure);
        $this->_viewFormFactory($closure);

        $closure->add('\Chrome\Exception\Handler_Interface', function ($c) {
            return new \Chrome\Exception\Handler\HtmlStackTrace();
        });

        $closure->add('\Chrome\Design\Loader_Interface', function ($c) {
            $model = $c->get('\Chrome\Model\Design\StaticLoader_Interface');
            return new \Chrome\Design\StaticLoader($c, $model);
        });

        $closure->add('\Chrome\Redirection\Redirection_Interface', function ($c) {
            return new \Chrome\Redirection\Redirection($c->get('\Chrome\Context\Application_Interface'));
        });

        $closure->add('\Chrome\Resource\Model_Interface', function ($c) {
            return $c->get('\Chrome\Model\Resource\Database');
        }, true);

        $closure->add('\Chrome\Logger\Model', function ($c) {
            return $c->get('\Chrome\Context\Application_Interface')
                ->getLoggerRegistry()
                ->get();
        }, true);

        $closure->add('\Chrome\Helper\Authentication\Creation_Interface', function ($c) {
            return new \Chrome\Helper\Authentication\Creation($c->get('\Chrome\Authentication\Authentication_Interface'));
        }, true);

        $closure->add('\Chrome\Interactor\Result_Interface', function ($c) {
            return new \Chrome\Interactor\Result();
        });

        $closure->add('\Chrome\Exception\Handler\DefaultController', function ($c) {
            return new \Chrome\Exception\Handler\HtmlStackTrace();
        });

        $closure->add('\Chrome\Exception\Handler\DefaultRouter', function ($c) {
            return new \Chrome\Exception\Handler\HtmlStackTrace();
        });
    }

    protected function _request($closure)
    {
        $closure->add('\Psr\Http\Message\ServerRequestInterface', function ($c) {
            return \Zend\Diactoros\ServerRequestFactory::fromGlobals($_SERVER, $_GET, $_POST, $_COOKIE, $_FILES);
        }, true);

        $closure->add('\Psr\Http\Message\UriInterface', function ($c) {
            return new \Zend\Diactoros\Uri();
        });

        $closure->add('\Chrome\Request\RequestContext_Interface', function ($c) {
            return new \Chrome\Request\Context($c->get('\Psr\Http\Message\ServerRequestInterface'), $c->get('\Chrome\Request\Cookie_Interface'), $c->get('\Chrome\Request\Session_Interface'));
        }, true);

        $closure->add('\Chrome\Request\Cookie_Interface', function ($c) {
            return new \Chrome\Request\Cookie\Cookie($c->get('\Psr\Http\Message\ServerRequestInterface'), $c->get('\Chrome\Hash\Hash_Interface'));
        }, true);

        $closure->add('\Chrome\Request\Session_Interface', function ($c) {
            return new \Chrome\Request\Session\Session($c->get('\Chrome\Request\Cookie_Interface'), $c->get('\Psr\Http\Message\ServerRequestInterface'), $c->get('\Chrome\Hash\Hash_Interface'), $c->get('\Chrome\Request\Session\SavePath'));
        }, true);

        $closure->add('\Chrome\Request\Session\SavePath', function ($c) {
            return new \Chrome\Directory(TMP . CHROME_SESSION_SAVE_PATH);
        });
    }

    protected function _localization($closure)
    {
        $closure->add('\Chrome\Localization\Translate_Interface', function ($c) {
            return $c->get('\Chrome\Context\Application_Interface')
                ->getViewContext()
                ->getLocalization()
                ->getTranslate();
        }, true);

        $closure->add('\Chrome\Localization\Localization_Interface', function ($c) {
            return $c->get('\Chrome\Context\Application_Interface')
                ->getViewContext()
                ->getLocalization();
        }, true);
    }

    protected function _model($closure)
    {
        $closure->add('\Chrome\Model\Classloader\Model_Interface', function ($c) {
            $cacheOption = new \Chrome\Cache\Option\File\Serialization();
            $cacheOption->setCacheFile(new \Chrome\File(CACHE . '_require.cache'));
            $cache = new \Chrome\Cache\File\Serialization($cacheOption);

            return new \Chrome\Model\Classloader\Cache($c->get('\Chrome\Model\Classloader\Database'), $cache);
        }, true);

        $closure->add('\Chrome\Model\Authorisation\Simple\Model_Interface', function ($c) {
            $model = $c->get('\Chrome\Model\Authorisation\Adapter\Simple\Database');
            $model->setResourceModel($c->get('\Chrome\Resource\Model_Interface'));
            return $model;
        });

        $closure->add('\Chrome\Model\Route\Dynamic', function ($c) {
            $cacheOption = new \Chrome\Cache\Option\File\Serialization();
            $cacheOption->setCacheFile(new \Chrome\File(CACHE . 'router/_dynamic.cache'));
            $cache = new \Chrome\Cache\File\Serialization($cacheOption);

            return new \Chrome\Model\Route\DynamicRoute\Cache($c->get('\Chrome\Model\Route\DynamicRoute\Database'), $cache);
        });

        $closure->add('\Chrome\Model\Config', function ($c) {
            $cacheOption = new \Chrome\Cache\Option\File\Serialization();
            $cacheOption->setCacheFile(new \Chrome\File(CACHE . '_config.cache'));
            $cache = new \Chrome\Cache\File\Serialization($cacheOption);

            return new \Chrome\Model\Config\Cache($c->get('\Chrome\Model\Config\Database'), $cache);
        }, true);

        $closure->add('\Chrome\Model\Database\Statement_Interface', function ($c) {
            return new \Chrome\Model\Database\JsonStatement($c->get('\Chrome\Cache\Memory\DBStatement'), new \Chrome\Directory(RESOURCE . 'database'));
        });

        $closure->add('\Chrome\Model\Route\Static_Interface', function ($c) {
            $cacheOption = new \Chrome\Cache\Option\File\Serialization();
            $cacheOption->setCacheFile(new \Chrome\File(CACHE . 'router/_static.cache'));
            $cache = new \Chrome\Cache\File\Serialization($cacheOption);

            return new \Chrome\Model\Route\FixedRoute\Cache($c->get('\Chrome\Model\Route\Fixed\Database'), $cache);
        }, true);

        $closure->add('\Chrome\Model\Route\Fixed\Database', function ($c) {
            return $c->get('\Chrome\Model\Route\FixedRoute\Database');
        }, true);

        $closure->add('\Chrome\Model\Design\StaticLoader_Interface', function ($c) {
            $cacheOption = new \Chrome\Cache\Option\File\Serialization();
            $cacheOption->setCacheFile(new \Chrome\File(CACHE . '_designLoaderStatic.cache'));
            $cache = new \Chrome\Cache\File\Serialization($cacheOption);

            return new \Chrome\Model\Design\StaticLoaderCache($c->get('\Chrome\Model\Design\StaticLoaderDatabase'), $cache);
        }, true);
    }

    protected function _classloader($closure)
    {
        $closure->add('\Chrome\Classloader\Resolver\Model_Interface', function ($c) {
            return new \Chrome\Classloader\Resolver\Model($c->get('\Chrome\Model\Classloader\Model_Interface'), $c, new \Chrome\Directory(''));
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

        $closure->add('\Chrome\Controller\User\Register', function ($c) {
            return new \Chrome\Controller\User\Register($c->get('\Chrome\Context\Application_Interface'), $c->get('\Chrome\Interactor\User\Registration_Interface'), new \Chrome\View\User\Register($c->get('\Chrome\Context\View_Interface')));
        });
    }

    protected function _cache($closure)
    {
        $closure->add('\Chrome\Cache\Memory\DBStatement', function ($c) {
            // fix this cache, only one instance!
            return $c->get('\Chrome\Cache\Memory');
        }, true);

        $closure->add('\Chrome\Cache\Memory', function ($c) {
            return new \Chrome\Cache\Memory();
        });
    }

    protected function _linker($closure)
    {
        $closure->add('\Chrome\Linker\HTTP\Helper\Model\Static_Interface', function ($c) {
            $model = $c->get('\Chrome\Model\Route\Fixed\Database');
            $model->setResourceModel($c->get('\Chrome\Resource\Model_Interface'));
            return $model;
        }, true);

        $closure->add('\Chrome\Linker\LinkerReferenceUri', function ($c) {
            return $c->get('\Psr\Http\Message\UriInterface')
                ->withPath(ROOT_URL . '/');
        }, true);

        $closure->add('\Chrome\Linker\Linker_Interface', function ($c) {
            $linker = new \Chrome\Linker\HTTP\Linker($c->get('\Chrome\Linker\LinkerReferenceUri'));

            require_once LIB . 'core/linker/http/relative.php';
            require_once LIB . 'core/linker/http/uri.php';
            require_once LIB . 'core/linker/http/fixed.php';
            require_once LIB . 'core/linker/http/identifier.php';
            require_once LIB . 'core/linker/http/fallback.php';

            $linker->addResourceHelper(new \Chrome\Linker\HTTP\RelativeHelper());
            $linker->addResourceHelper(new \Chrome\Linker\Http\IdentifierHelper($c->get('\Chrome\Linker\HTTP\Helper\Model\Static_Interface')));
            $linker->addResourceHelper(new \Chrome\Linker\HTTP\FixedHelper($c->get('\Chrome\Linker\HTTP\Helper\Model\Static_Interface')));
            $linker->addResourceHelper(new \Chrome\Linker\HTTP\UriHelper());
            $linker->addResourceHelper(new \Chrome\Linker\HTTP\FallbackHelper());

            return $linker;
        }, true);
    }

    protected function _viewFormFactory($closure)
    {
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
    }
}
