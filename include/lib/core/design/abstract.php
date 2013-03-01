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
 * @subpackage Chrome.Design
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [01.03.2013 00:37:37] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true)
    die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Design
 */
interface Chrome_Design_Abstract_Interface
{
    public function get($string, Chrome_Design_Renderable $obj = null);

    public static function getInstance();

    public function getStyle();

    public function getMapper();
}

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Design
 */
abstract class Chrome_Design_Abstract
{
    protected $_values = array();

    public function get($string, Chrome_Design_Renderable $obj = null) {
        return isset($this->_values[$string]) ? $this->_values[$string] : $this->_get($string, $obj);
    }

    protected abstract function _get($string, Chrome_Design_Renderable $obj = null);
}