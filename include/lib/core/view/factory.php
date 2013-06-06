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
 * @subpackage Chrome.View
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [31.05.2013 19:39:22] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true)
    die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.View
 */
interface Chrome_View_Factory_Interface
{
    public function build($viewClass);
}

/**
 * @package CHROME-PHP
 * @subpackage Chrome.View
 */
class Chrome_View_Factory implements Chrome_View_Factory_Interface
{
    protected $_viewContext = null;

    public function __construct(Chrome_Context_View_Interface $viewContext)
    {
        $this->_viewContext = $viewContext;
    }

    public function build($viewClass, Chrome_Controller_Interface $controller = null)
    {
        if($controller === null) {
            return new $viewClass($this->_viewContext);
        } else {
            return new $viewClass($this->_viewContext, $controller);
        }

    }

}