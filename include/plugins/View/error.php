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
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://chrome-php.de/license/new-bsd        New BSD License
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [01.03.2013 13:48:16] --> $
 */

if(CHROME_PHP !== true)
    die();

//TODO: finish error codes
class Chrome_View_Helper_Error extends Chrome_View_Helper_Abstract
{
    private static $_instance = null;

    public static function getInstance()
    {
        if(self::$_instance === null) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function setError(Chrome_View_Interface $obj, $errorCode) {
        switch($errorCode) {
            default: {
                echo $errorCode.' not found in Chrome_View_Helper_Error!';
            }
        }
    }

    public function getMethods()
    {
        return array('setError');
    }

    public function getClassName()
    {
        return 'Chrome_View_Helper_Error';
    }
}

Chrome_View_Helper_Error::getInstance();