<?php

/**
 * CHROME-PHP CMS
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://chrome-php.de/license/new-bsd
 * If you did not receive a copy of the license AND are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@chrome-php.de so we can send you a copy immediately.
 *
 * @package    CHROME-PHP
 * @subpackage Chrome.Form
 * @copyright  Copyright (c) 2008-2009 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://chrome-php.de/license/new-bsd		New BSD License
 * @version   $Id: 0.1 beta <!-- phpDesigner :: Timestamp [28.02.2012 12:37:46] --> $
 */
if(CHROME_PHP !== true)
    die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Form
 */
class Chrome_Form_Decorator_Form_Default extends Chrome_Form_Decorator_Abstract
{
    private $_int = 0;

    public function render() {

        if($this->_int == 0) {
            $name = $this->_formElement->getID();
            $method = $this->_formElement->getForm()->getAttribute('method');
            $action = $this->_formElement->getForm()->getAttribute('action');
            $id = $this->_formElement->getForm()->getAttribute('id');

            $token = $this->_formElement->getOptions(Chrome_Form_Element_Form::CHROME_FORM_ELEMENT_FORM_TOKEN);

            $this->_int = 1;
            return '<form name="'.$name.'" method="'.$method.'" action="'.$action.'" '.$this->_getPreparedAttrs().'id="'.$id.'">'."\n"
                   .'<input type="hidden" name="'.$this->_formElement->getOptions(Chrome_Form_Element_Form::CHROME_FORM_ELEMENT_FORM_TOKEN_NAMESPACE).'" value="'
                   .$token.'" />'."\n";

        } else {
            $this->_int = 0;
            return '</form>'."\n";
        }
    }
}