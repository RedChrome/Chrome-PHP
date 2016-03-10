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
 * @package   CHROME-PHP
 * @subpackage Chrome.Test
 */
namespace Test\Chrome\Application;

require_once APPLICATION . 'default.php';

class DefaultTestApplication extends \Chrome\Application\DefaultApplication
{
    protected $_model = null;

    protected function _initDatabase()
    {
        $this->_applicationContext->setModelContext($this->_model);
        $this->_modelContext = $this->_model;
        // overwrite this method, thus there is no database connection to product database created
    }

    public function setModelContext(\Chrome\Context\Model_Interface $modelContext)
    {
        $this->_model = $modelContext;
    }

    protected function _initRequestAndResponse()
    {
        $hash = $this->_diContainer->get('\Chrome\Hash\Hash');

        $request = \Zend\Diactoros\ServerRequestFactory::fromGlobals(array_merge($_SERVER, array('HTTP_USER_AGENT' => 'Chrome', 'REMOTE_ADDR' => '127.0.0.1')), $_GET, $_POST, $_COOKIE, $_FILES);
        $cookie = new \Chrome\Request\Cookie\Cookie($request, $hash);
        $session = new \Chrome\Request\Session\Session($cookie, $request, $hash, new \Chrome\Directory(TMP.CHROME_SESSION_SAVE_PATH));

        $this->_applicationContext->setRequestContext(new \Chrome\Request\Context($request, $cookie, $session));

        $response = new \Chrome\Response\Console();
        $this->_applicationContext->setResponse($response);
    }

    protected function _initDiContainer()
    {
        parent::_initDiContainer();
        $this->_diContainer->getHandler('closure')->add('\Chrome\Linker\Linker_Interface', function ($c) {
            return new \Chrome\Linker\Console\Linker($c->get('\Chrome\Resource\Model_Interface'));
        });
    }
}