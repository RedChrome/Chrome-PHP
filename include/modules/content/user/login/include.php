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
 * @package CHROME-PHP
 * @subpackage Chrome.User
 * @copyright Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version $Id: 0.1 beta <!-- phpDesigner :: Timestamp [21.07.2013 17:59:18] --> $
 * @author Alexander Book
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
    public static function getInstance(Chrome_Context_Application_Interface $appContext)
    {
        if(self::$_instance === null)
        {
            self::$_instance = new self($appContext);
        }

        return self::$_instance;
    }

    /**
     * Init the form
     *
     * @return Chrome_Form_Login
     */
    protected function _init()
    {
        // get lang obj for this module
        $lang = $this->_applicationContext->getViewContext()->getLocalization()->getTranslate();
        // $lang = new Chrome_Language('modules/content/user/login');

        $this->_id = 'login';
        $this->setAttribute(self::ATTRIBUTE_NAME, $this->_id);
        $this->setAttribute(self::ATTRIBUTE_METHOD, self::CHROME_FORM_METHOD_POST);
        $this->setAttribute(self::ATTRIBUTE_ID, $this->_id);
        $this->setAttribute(self::ATTRIBUTE_ACTION, 'login.html');

        // create an boolean converter, cause 'stay_loggedin' only accepts true or false
        $boolConverter = new Chrome_Converter_List();
        $boolConverter->addConversion('bool');

        // this element has to be set in every form!
        // max time, this form is valid is 300 sec
        $formElementOption = new Chrome_Form_Option_Element_Form(new Chrome_Form_Storage_Session($this->_applicationContext->getRequestHandler()->getRequestData()->getSession(), $this->_id));
        $formElementOption->setMaxAllowedTime(300)->setMinAllowedTime(0);

        $formElement = new Chrome_Form_Element_Form($this, $this->_id, $formElementOption);
        $this->_addElement($formElement);

        // this is the 'username' input
        // it is required, of course, to login
        $identityOption = new Chrome_Form_Option_Element();
        $identityOption->setIsRequired(true);

        $identityElement = new Chrome_Form_Element_Text($this, 'identity', $identityOption);
        $this->_addElement($identityElement);

        // this is the password input
        $passwordOption = new Chrome_Form_Option_Element();
        $passwordOption->setIsRequired(true);

        $passwordElement = new Chrome_Form_Element_Password($this, 'password', $passwordOption);
        $this->_addElement($passwordElement);

        // stay_loggedin input, default selection is false
        // only true or false are allowed, to be sure the user has sent on of them, we add the boolConverter
        // this determines, whether the user stays logged in, even if he leaves the website
        $checkboxOption = new Chrome_Form_Option_Element_Multiple();
        $checkboxOption->setIsRequired(false)->setAllowedValues(array(1));

        $checkboxElement = new Chrome_Form_Element_Checkbox($this, 'stay_loggedin', $checkboxOption);
        $this->_addElement($checkboxElement);

        // submit button, nothing special
        $submitOption = new Chrome_Form_Option_Element_Values();
        $submitOption->setIsRequired(true)->setAllowedValues(array($lang->get('login')));

        $submitElement = new Chrome_Form_Element_Submit($this, 'submit', $submitOption);
        $this->_addElement($submitElement);

        // cause this form can get used everywhere, we need to be sure
        // that this form is once created
        if(!$this->isCreated())
        {
            $this->create();
        }

        // adds the renew handler, every ~10 request renew => renews the token
        $this->addReceivingHandler(new Chrome_Form_Handler_Renew(10));
        // deletes the input when the form is destroyed
        $this->addReceivingHandler(new Chrome_Form_Handler_Destroy());
    }
}
class Chrome_View_Form_Login extends Chrome_View_Form_Abstract
{
    private static $_instance = null;

    public static function getInstance(Chrome_Form_Interface $form, Chrome_Context_View_Interface $viewContext)
    {
        if(self::$_instance === null)
        {
            self::$_instance = new self($form, $viewContext);
        }

        return self::$_instance;
    }

    protected function _initFactories()
    {
        if($this->_formElementFactory === null)
        {
            $this->_formElementFactory = new Chrome_View_Form_Element_Factory_Suffix('Default');
        }
        // $this->_renderer = new Chrome_View_Form_Index_Renderer();

        parent::_initFactories();
    }

    protected function _modifyElementOption(Chrome_Form_Element_Interface $formElement, Chrome_View_Form_Element_Option_Interface $viewOption)
    {
        $lang = $this->_viewContext->getLocalization()->getTranslate();

        switch($formElement->getID())
        {
            case 'identity':
                {
                    $currLang = $lang->get('modules/content/user/login/identity');
                    $viewOption->setLabel(new Chrome_View_Form_Label_Default(array('identity' => $currLang)))->setPlaceholder($currLang);

                    break;
                }
            case 'password':
                {
                    $currLang = $lang->get('modules/content/user/login/password');
                    $viewOption->setLabel(new Chrome_View_Form_Label_Default(array('password' => $currLang)))->setPlaceholder($currLang);
                    break;
                }

            case 'stay_loggedin':
                {
                    $currLang = $lang->get('modules/content/user/login/stay_loggedin');
                    $viewOption->setLabel(new Chrome_View_Form_Label_Default(array('1' => $currLang)));
                    break;
                }

            case 'submit':
                {
                    // viewOption->setLabelPosition($viewOption::LABEL_POSITION_BEHIND);
                    //$viewOption->setLabel(new Chrome_View_Form_Label_Default(array('submit' => 'Anmelden!!')));
                    break;
                }
        }

        return $viewOption;
    }
}