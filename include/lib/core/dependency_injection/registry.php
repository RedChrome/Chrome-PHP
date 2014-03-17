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
 * @package CHROME-PHP
 * @subpackage Chrome.DependencyInjection
 */

namespace Chrome\DI\Handler;

use Chrome\DI\Handler_Interface;
use Chrome\DI\Container_Interface;

class Registry implements Handler_Interface
{
    protected $_objects = array();

    public function add($key, $object)
    {
        if(!is_string($key)) {
            throw new \Chrome\InvalidArgumentException('Argument $key must be of type string');
        }

        if(!is_object($object)) {
            throw new \Chrome\InvalidArgumentException('Argument $object must be of type object');
        }

        $this->_objects[$key] = $object;
    }

    public function has($key)
    {
        return isset($this->_objects[$key]);
    }

    public function remove($key)
    {
        unset($this->_objects[$key]);
    }

    public function get($key, Container_Interface $container)
    {
        if(!$this->has($key)) {
            return null;
        }

        return $this->_objects[$key];
    }
}