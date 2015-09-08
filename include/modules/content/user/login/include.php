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
 */

/**
 * Form for an user login
 *
 * @package CHROME-PHP
 * @subpackage Chrome.User
 */
class Chrome_Form_Login extends \Chrome\Form\AbstractForm
{
    private static $_instance = null;

    /**
     * Singleton pattern
     * only one instance of this form
     *
     * @return Chrome_Form_Login
     */
    public static function getInstance(\Chrome\Context\Application_Interface $appContext)
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
        // get lang obj
        $lang = $this->_applicationContext->getViewContext()->getLocalization()->getTranslate();
        // $lang = new Chrome_Language('modules/content/user/login');

        $this->_id = 'login';
        $this->setAttribute(self::ATTRIBUTE_NAME, $this->_id);
        $this->setAttribute(self::ATTRIBUTE_METHOD, self::CHROME_FORM_METHOD_POST);
        $this->setAttribute(self::ATTRIBUTE_ID, $this->_id);
        //$this->setAttribute(self::ATTRIBUTE_ACTION, new \Chrome\Resource\Resource('rel:')); // has no effect

        // create an boolean converter, cause 'stay_loggedin' only accepts true or false
        $boolConverter = new \Chrome\Converter\ConverterList();
        $boolConverter->addConversion('bool');

        // this element has to be set in every form!
        // max time, this form is valid is 300 sec
        $formElementOption = new \Chrome\Form\Option\Element\Form(new \Chrome\Form\Storage\Session($this->_applicationContext->getRequestHandler()->getRequestData()->getSession(), $this->_id));
        $formElementOption->setMaxAllowedTime(300)->setMinAllowedTime(0);

        $formElement = new \Chrome\Form\Element\Form($this, $this->_id, $formElementOption);
        $this->_addElement($formElement);

        // this is the 'username' input
        // it is required, of course, to login
        $identityOption = new \Chrome\Form\Option\Element();
        $identityOption->setIsRequired(true);

        $identityElement = new \Chrome\Form\Element\Text($this, 'identity', $identityOption);
        $this->_addElement($identityElement);

        // this is the password input
        $passwordOption = new \Chrome\Form\Option\Element();
        $passwordOption->setIsRequired(true);

        $passwordElement = new \Chrome\Form\Element\Password($this, 'password', $passwordOption);
        $this->_addElement($passwordElement);

        // stay_loggedin input, default selection is false
        // only true or false are allowed, to be sure the user has sent on of them, we add the boolConverter
        // this determines, whether the user stays logged in, even if he leaves the website
        $checkboxOption = new \Chrome\Form\Option\MultipleElement();
        $checkboxOption->setAllowedValues(array(1));

        $checkboxElement = new \Chrome\Form\Element\Checkbox($this, 'stay_loggedin', $checkboxOption);
        $this->_addElement($checkboxElement);

        // submit button, nothing special
        $submitOption = new \Chrome\Form\Option\Element();
        $submitOption->setIsRequired(true)->setAllowedValue($lang->get('login'));

        $submitElement = new \Chrome\Form\Element\Submit($this, 'submit', $submitOption);

        $buttonsOption = new \Chrome\Form\Option\Element\Buttons();
        $buttonsOption->attach($submitElement);
        $buttonsElement = new \Chrome\Form\Element\Buttons($this, 'buttons', $buttonsOption);

        $this->_addElement($buttonsElement);

        // adds the renew handler, every ~10 request renew => renews the token
        $this->addReceivingHandler(new \Chrome\Form\Handler\Renew(10));
        // deletes the input when the form is destroyed
        $this->addReceivingHandler(new \Chrome\Form\Handler\Destroy());
    }
}
class Chrome_View_Form_Login extends Chrome_View_Form_Abstract
{
    private static $_instance = null;

    public static function getInstance(\Chrome\Form\Form_Interface $form, \Chrome\Context\View_Interface $viewContext)
    {
        if(self::$_instance === null)
        {
            self::$_instance = new self($form, $viewContext);
        }

        return self::$_instance;
    }

    protected function _modifyElementOption(\Chrome\Form\Element\BasicElement_Interface $formElement, Chrome_View_Form_Element_Option_Basic_Interface $viewOption)
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
        }

        return $viewOption;
    }
}