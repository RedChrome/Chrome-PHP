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
 * @subpackage Chrome.User
 * @copyright  Copyright (c) 2008-2009 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://chrome-php.de/license/new-bsd		New BSD License
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [01.03.2012 16:43:31] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true)
    die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.User
 */
class Chrome_Controller_Content_Login extends Chrome_Controller_Content_Abstract
{
	protected function _initialize() {
	}


    protected function _execute()
    {
        if(isset($this->_GET['request'])) {
            $request = $this->_GET['request'];
        } else if(isset($this->_POST['request'])) {
            $request  =$this->_POST['request'];
        } else {
            $request = '';
        }

        switch($request) {

            case 'ajax': {
                require_once 'controller/ajax.php';
                $controller = new Chrome_Controller_Content_Login_AJAX();
                break;
            }

            default: {
                 require_once 'controller/default.php';
                 $controller = new Chrome_Controller_Content_Login_Default();
            }

        }

        $controller->execute();
    }

    public function getResponse() {

        /**
         * not good, but it works ;)
         */
        if(isset($_GET['request'])) {
            $request = $_GET['request'];
        } else if(isset($_POST['request'])) {
            $request  =$_POST['request'];
        } else {
            $request = '';
        }

        switch($request) {

            case 'ajax': {
                Chrome_Response::setResponseClass('ajax');
                break;
            }

            default: {
                // do nothing special
            }

        }

        return parent::getResponse();
    }
}