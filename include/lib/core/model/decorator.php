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
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [13.03.2013 19:45:20] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true)
    die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Model
 */
abstract class Chrome_Model_Decorator_Abstract extends Chrome_Model_Abstract
{
	protected $_decorator = null;

	public function __construct(Chrome_Model_Interface $instance = null) {
	   if($instance !== null) {
	       $this->setDecorator($instance);
        }
	}

	public function __call($func, $args) {

		return call_user_func_array(array($this->_decorator, $func), $args);

	}

    /**
     *@todo rename it to setDecorable
     */
    public function setDecorator(Chrome_Model_Abstract $instance) {
        $this->_decorator = $instance;
    }

    public function getDecorator() {
        return $this->_decorator;
    }
}