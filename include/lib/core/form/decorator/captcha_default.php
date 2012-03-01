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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [24.08.2011 14:30:07] --> $
 */
if(CHROME_PHP !== true)
    die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Form
 */ 
class Chrome_Form_Decorator_Captcha_Default extends Chrome_Form_Decorator_Abstract
{
    public function render() {
        
        $lang = new Chrome_Language(Chrome_Language::CHROME_LANGUAGE_GENERAL);
        
        $img = '<img src="'._PUBLIC.'captcha/default.php?name='.$this->_formElement->getForm()->getID().'" id="captcha_'.$this->_formElement->getForm()->getID().'" />';
        $input = '<br><input type="text" name="'.$this->_formElement->getID().'" value="" '.$this->_getPreparedAttrs().'"><br>
                <a onclick="javascript:document.getElementById(\'captcha_'.$this->_formElement->getForm()->getID().'\').src=\''._PUBLIC.'captcha/default.php?name='.$this->_formElement->getForm()->getID().'&renew=\'+getToken()">'.$lang->get('captcha_renew').'</a>';
        return $img.$input;
    }   
}
