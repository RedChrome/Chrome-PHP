<?php

/**
 * CHROME-PHP CMS
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://chrome-php.de/license/new-bsd
 * If you did not receive a copy of the license AND are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@chrome-php.de so we can send you a copy immediately.
 *
 * @package    CHROME-PHP
 * @subpackage Chrome.Filter
 * @copyright  Copyright (c) 2008-2009 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://chrome-php.de/license/new-bsd		New BSD License
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [10.08.2011 15:32:07] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true)
    die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Filter
 */
class Chrome_Filter_Auth extends Chrome_Filter_Abstract
{

    protected $_loginControllerClass = 'Chrome_Controller_User_Login_Explicit';

    public function setLoginControllerClass($class) {
        $this->_loginControllerClass = $class;
    }

    public function getLoginControllerClass() {
        return $this->_loginControllerClass;
    }

    public function execute(Chrome_Request_Abstract $req, Chrome_Response_Abstract $res)
    {
        $frontController = Chrome_Front_Controller::getInstance();

        $controller = $frontController->getController();

        $ACE = $controller->getACE();

        if(Chrome_ACL::getInstance()->hasRight($ACE) === false) {
            $frontController->setController(new $this->_loginControllerClass());
        }
    }
}