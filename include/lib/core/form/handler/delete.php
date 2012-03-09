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
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@chrome-php.de so we can send you a copy immediately.
 *
 * @package    CHROME-PHP
 * @subpackage Chrome.Form
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version   $Id: 0.1 beta <!-- phpDesigner :: Timestamp [04.03.2012 14:23:36] --> $
 */

if(CHROME_PHP !== true)
    die();

/**
 *
 * Chrome_Form_Handler_Delete
 *
 * Deletes the input and other data, if the form is getting destroyed
 *
 * You can use this as Creation/Receiving/Validation Handler, but i think it's
 * most usefull as receiving hanlder ;)
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Form
 */
class Chrome_Form_Handler_Delete implements Chrome_Form_Handler_Interface
{

    protected $_form = null;

    public function __destruct() {
        if($this->_form !== null) {
            $this->_form->delete();
        } else {
            // do nothing, form wasn't set
        }
    }

    public function is(Chrome_Form_Interface $form) {
        $this->_form = $form;
    }

    public function isNot(Chrome_Form_Interface $form) {
        $this->_form = $form;
    }
}