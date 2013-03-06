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
 * @category   CHROME-PHP
 * @package    CHROME-PHP
 * @subpackage Chrome.Database
 * @author     Alexander Book <alexander.book@gmx.de>
 * @copyright  2012 Chrome - PHP <alexander.book@gmx.de>
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [06.03.2013 13:24:13] --> $
 * @link       http://chrome-php.de
 */

if(CHROME_PHP !== true) die();

class Chrome_Database_Facade
{
    const DEFAULT_FACTORY = '';

    protected static $_factories = array();

    public static function getFactory($factoryName = self::DEFAULT_FACTORY)
    {
        if(isset(self::$_factories[$factoryName])) {
            return self::$_factories[$factoryName];
        } else {
            throw new Chrome_Exception_Database('Could not get factory with name "'.$factoryName.'"');
        }
    }

    public static function setFactory($factoryName, Chrome_Database_Factory_Interface $factory)
    {
        self::$_factories[$factoryName] = $factory;
    }
}
