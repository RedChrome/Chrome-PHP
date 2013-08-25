<?php

/**
 * CHROME-PHP CMS
 *
 * PHP version 5
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
 */
if(CHROME_PHP !== true) die();

/**
 * Chrome_Form_Handler_Destroy
 *
 * destorys the input and other data, if the form is getting destroyed
 *
 * You can use this as Creation/Receiving/Validation Handler, but i think it's
 * most usefull as receiving handler ;)
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Form
 */
class Chrome_Form_Handler_Destroy implements Chrome_Form_Handler_Interface
{
    protected $_form = null;

    public function __destruct()
    {
        if($this->_form !== null) {
            $this->_form->destroy();
        }
    }

    public function is(Chrome_Form_Interface $form)
    {
        $this->_form = $form;
    }

    public function isNot(Chrome_Form_Interface $form)
    {
        $this->_form = $form;
    }
}
