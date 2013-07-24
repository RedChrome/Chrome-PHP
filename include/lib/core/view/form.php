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
interface Chrome_View_Form_Interface extends Chrome_Renderable
{
    /**
     * @param string $elementName
     * @return Chrome_View_Form_Element_Interface
     */
    public function getElements($elementName);
    
    public function addElement(Chrome_View_Form_Element_Interface $element);
}

/**
 * @package CHROME-PHP
 * @subpackage Chrome.View
 */
interface Chrome_View_Form_Factory_Interface
{
    public function __construct(Chrome_Form_Interface $form);

    /**
     * @return Chrome_View_Form_Interface
     */
    public function factory();
}

/**
 * @package CHROME-PHP
 * @subpackage Chrome.View
 */
interface Chrome_View_Form_Element_Factory_Interface
{
    public function __construct();
    
    /**
     * 
     * @param Chrome_Form_Element_Interface $formElement
     * @return Chrome_View_Form_Element_Interface
     */
    public function factory(Chrome_Form_Element_Interface $formElement);    
}

/**
 * @package CHROME-PHP
 * @subpackage Chrome.View
 */
interface Chrome_View_Form_Element_Interface extends Chrome_Renderable
{
    public function __construct(Chrome_Form_Element_Interface $formElement);
    
    public function setOption(Chrome_View_Form_Option_Interface $option);
    
    public function getOption();
}