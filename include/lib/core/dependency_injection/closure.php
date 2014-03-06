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

class Closure implements Handler_Interface
{
    protected $_closures = array();

    protected $_static = array();

    protected $_instanciated = array();

    public function add($key, \Closure $closure, $static = false)
    {
        $this->_closures[$key] = $closure;
        if($static === true) {
            $this->_static[$key] = $closure;
        }
    }

    public function remove($key)
    {
        unset($this->_closures[$key], $this->_static[$key], $this->_instanciated[$key]);
    }

    public function has($key)
    {
        return isset($this->_closures[$key]);
    }

    public function isStatic($key)
    {
        return isset($this->_static[$key]);
    }

    public function get($key, Container_Interface $container)
    {
        if(!$this->has($key)) {
            return null;
        }

        // return an already instanciated object. For this object
        // the param $static was true.
        if(isset($this->_instanciated[$key])) {
            return $this->_instanciated[$key];
        }

        $object = $this->_closures[$key]($container);

        if($object === null) {
            throw new \Chrome_Exception('Closure function for key "'.$key.'" returns null');
        }

        if($this->isStatic($key)) {
            $this->_instanciated[$key] = $object;
            $this->_static[$key];
        }

        return $object;
    }
}