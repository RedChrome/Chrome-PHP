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
 * @deprecated
 * @package    CHROME-PHP
 * @subpackage Chrome.Design
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [25.08.2011 17:53:41] --> $
 */

die('using chrome decorator');

if(CHROME_PHP !== true)
    die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Design
 */
interface Chrome_Design_Decorator_Interface extends Chrome_Design_Renderable
{
    public function __construct(Chrome_Design_Renderable $obj = null);

    public function set(Chrome_Design_Renderable $obj);
}

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Design
 */
abstract class Chrome_Design_Decorator_Abstract implements Chrome_Design_Decorator_Interface
{
    protected $_decorate = null;

    public function __construct(Chrome_Design_Renderable $obj = null)
    {
        if($obj !== null) {
            $this->set($obj);
        }
    }

    public function set(Chrome_Design_Renderable $obj)
    {
        $this->_decorate = $obj;
    }
    
    public function __call($method, $params) {
        
        return call_user_func_array(array($this->_decorate, $method), $params);
        
    }
}