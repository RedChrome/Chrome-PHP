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
 * @subpackage Chrome.Form
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version   $Id: 0.1 beta <!-- phpDesigner :: Timestamp [01.03.2012 16:07:30] --> $
 */
if(CHROME_PHP !== true)
    die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Form
 */
class Chrome_Form_Decorator_Submit_Default extends Chrome_Form_Decorator_Abstract
{
   private $_int = 0;

    public function render() {

        $array = $this->_formElement->getOptions(Chrome_Form_Element_Submit::CHROME_FORM_ELEMENT_SUBMIT_VALUES);

        $value = $array[$this->_int];

        $this->_int = (++$this->_int) % ($array);

        return '<input type="submit" name="'.$this->_formElement->getID().'" value="'.$value.'" '.$this->_getPreparedAttrs().'/>';
    }
}