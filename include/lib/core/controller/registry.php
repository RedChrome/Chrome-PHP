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
 * @subpackage Chrome.Log
 */
namespace Chrome\Registry\Controller\Factory;

interface Registry_Interface extends \Chrome\Registry\Object
{
    const DEFAULT_FACTORY = self::DEFAULT_OBJECT;

    public function set($key, \Chrome_Controller_Factory_Interface $controllerFactory);
}

class Registry extends \Chrome\Registry\Object_Abstract implements Registry_Interface
{
    public function set($key, \Chrome_Controller_Factory_Interface $controllerFactory)
    {
        $this->_set($key, $controllerFactory);
    }

    protected function _objectNotFound($key)
    {
        throw new \Chrome_Exception('Could not find Controller Factory "'.$key.'"');
    }
}

class Registry_Single extends \Chrome\Registry\Object_Single_Abstract implements Registry_Interface
{
    public function set($key, \Chrome_Controller_Factory_Interface $controllerFactory)
    {
        $this->_set($controllerFactory);
    }

    protected function _objectNotFound($key)
    {
        throw new \Chrome_Exception('No controller factory set');
    }
}
