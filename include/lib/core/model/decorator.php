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
 * @subpackage Chrome.Model
 */

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Model
 */
abstract class Chrome_Model_Decorator_Abstract extends Chrome_Model_Abstract
{
    protected $_decorable = null;

    public function __construct(Chrome_Model_Interface $instance = null)
    {
        if($instance !== null) {
            $this->setDecorable($instance);
        }
    }

    public function __call($func, $args)
    {
        return call_user_func_array(array($this->_decorable, $func), $args);
    }

    public function setDecorable(Chrome_Model_Abstract $instance)
    {
        $this->_decorable = $instance;
    }

    public function getDecorable()
    {
        return $this->_decorable;
    }
}
