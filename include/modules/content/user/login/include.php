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
 * @subpackage Chrome.User
 * @copyright  Copyright (c) 2008-2009 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://chrome-php.de/license/new-bsd		New BSD License
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [01.03.2012 17:03:31] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true)
    die();


/**
 * Form for an user login
 *
 * @package CHROME-PHP
 * @subpackage Chrome.User
 */
class Chrome_Form_Login extends Chrome_Form_Abstract
{
    private static $_instance = null;

    /**
     * Singleton pattern
     * only one instance of this form
     *
     * @return Chrome_Form_Login
     */
    public static function getInstance() {
        if(self::$_instance === null) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }
    /**
     * Init the form
     *
     * @return Chrome_Form_Login
     */
    protected function __construct()
    {
        // get lang obj for this module
        $LANG = new Chrome_Language('modules/content/user/login');

        $this->setOptionDeletingAfterReceiving(true);

        $this->_id = 'login';
        $this->setAttribute('name', $this->_id);
        $this->setAttribute('method', self::CHROME_FORM_METHOD_POST);
        $this->setAttribute('id', $this->_id);
        $this->setAttribute('action', 'login.html');

        // this element has to be set in every form!
        // max time, this form is valid is 300 sec
        $this->_elements[$this->_id] = new Chrome_Form_Element_Form($this, $this->_id, array(Chrome_Form_Element_Form::CHROME_FORM_ELEMENT_FORM_MAX_ALLOWED_TIME => 300));

        // this is the 'username' input
        // it is required, of course, to login
        // set onblur, and onfocus
        $this->_elements['credential'] = new Chrome_Form_Element_Text($this, 'credential', array(
            Chrome_Form_Element_Abstract::CHROME_FORM_ELEMENT_IS_REQUIRED => true,
            Chrome_Form_Element_Abstract::CHROME_FORM_ELEMENT_DEFAULT => $LANG->get('email'),
            Chrome_Form_Element_Abstract::CHROME_FORM_ELEMENT_DECORATOR_ATTRIBUTES => array(
                'onblur' => 'if(this.value==\'\')this.value=\''.$LANG->get('email').'\'',
                'onfocus' => 'if(this.value==\''.$LANG->get('email').'\')this.value=\'\'',
                'size' => 15)));


        // this is the password input
        // it is required too and if you click on the input, then the default input vanishes
        // for better ergonomics
        $this->_elements['password'] = new Chrome_Form_Element_Password($this, 'password', array(
            Chrome_Form_Element_Abstract::CHROME_FORM_ELEMENT_IS_REQUIRED => true,
            Chrome_Form_Element_Abstract::CHROME_FORM_ELEMENT_DEFAULT => $LANG->get('password'),
            Chrome_Form_Element_Abstract::CHROME_FORM_ELEMENT_DECORATOR_ATTRIBUTES => array(
                'onblur' => 'if(this.value==\'\')this.value=\''.$LANG->get('password').'\'',
                'onfocus' => 'if(this.value==\''.$LANG->get('password').'\')this.value=\'\'',
                'value' => $LANG->get('password'),
                'size' => 15)));

        // create an boolean converter, cause 'stay_loggedin' only accepts true or false
        $boolConverter = new Chrome_Converter_Value();
        $boolConverter->addFilter('bool');

        // stay_loggedin input, default selection is false
        // only true or false are allowed, to be sure the user has sent on of them, we add the boolConverter
        // this determines, whether the user stays logged in, even if he leaves the website
        $this->_elements['stay_loggedin'] = new Chrome_Form_Element_Checkbox($this, 'stay_loggedin', array(
            Chrome_Form_Element_Abstract::CHROME_FORM_ELEMENT_IS_REQUIRED => false,
            Chrome_Form_Element_Abstract::CHROME_FORM_ELEMENT_DEFAULT_SELECTION => array(false),
            Chrome_Form_Element_Abstract::CHROME_FORM_ELEMENT_SELECTION_OPTIONS => array(true, false),
            Chrome_Form_Element_Abstract::CHROME_FORM_ELEMENT_CONVERTER_NAMESPACE => array($boolConverter)));

        // submit button, nothing special
        $this->_elements['submit'] = new Chrome_Form_Element_Submit($this, 'submit', array(
            Chrome_Form_Element_Submit::CHROME_FORM_ELEMENT_IS_REQUIRED => true,
            Chrome_Form_Element_Submit::CHROME_FORM_ELEMENT_SUBMIT_VALUES => array($LANG->get('login'))));

        // cause this form can get used everywhere, we need to be sure
        // that this form is once created
        if(!$this->isCreated()) {
            $this->create();
        }

        // adds the renew handler, every ~10 request renew => renews the token
        $this->addReceivingHandler(new Chrome_Form_Handler_Renew(10));
    }
}