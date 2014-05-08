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
 * @subpackage Chrome.Form
 */

interface Chrome_Form_Option_Element_Captcha_Interface extends Chrome_Form_Option_Element_Interface
{
    /**
     * @return Chrome_Captcha_Interface
     */
    public function getCaptcha();

    /**
     * Returns true if the captcha gets re-created if it was invalid
     *
     * @return boolean
     */
    public function getRecreateIfInvalid();
}

class Chrome_Form_Option_Element_Captcha extends Chrome_Form_Option_Element implements Chrome_Form_Option_Element_Captcha_Interface
{
    protected $_form = null;

    protected $_captcha = null;

    protected $_frontendOptions = array();

    protected $_backendOptions = array();

    protected $_recreateIfInvalid = true;

    public function __construct(Chrome_Form_Interface $form)
    {
        $this->_form = $form;
    }

    public function setFrontendOptions(array $options)
    {
        $this->_frontendOptions = $options;
    }

    public function setBackendOptions(array $options)
    {
        $this->_backendOptions = $options;
    }

    public function setCaptcha(Chrome_Captcha_Interface $captcha)
    {
        $this->_captcha = $captcha;
    }

    public function getCaptcha()
    {
        if($this->_captcha === null)
        {
            $this->_captcha = new Chrome_Captcha($this->_form->getID(), $this->_form->getApplicationContext(), $this->_frontendOptions, $this->_backendOptions);
        }

        return $this->_captcha;
    }

    public function setRecreateIfInvalid($booleanRecreate)
    {
        $this->_recreateIfInvalid = (boolean) $booleanRecreate;
    }

    public function getRecreateIfInvalid()
    {
        return $this->_recreateIfInvalid;
    }
}

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Form
 */
class Chrome_Form_Element_Captcha extends Chrome_Form_Element_Abstract implements \Chrome\Form\Element\Interfaces\Captcha
{
    protected $_captcha = null;

    protected $_reCreated = false;

    public function __construct(Chrome_Form_Interface $form, $id, Chrome_Form_Option_Element_Captcha_Interface $option)
    {
        parent::__construct($form, $id, $option);
        $this->_captcha = $option->getCaptcha();
        $this->_captcha->create();
    }

    public function isCreated()
    {
        if($this->_captcha instanceof Chrome_Captcha_Interface)
        {
            $this->_captcha->create();
            return true;
        }

        return false;
    }

    public function isValid()
    {
        $isValid = parent::isValid();

        // only re-create the captcha one time and only if the option says to recreate it.
        if($isValid === false AND $this->_reCreated === false AND $this->_option->getRecreateIfInvalid() === true) {
            $this->_captcha->create();
            $this->_reCreated = true;
        }

        return $isValid;
    }

    protected function _getValidator()
    {
        return new \Chrome\Validator\Form\Element\CaptchaValidator($this->_captcha);
    }

    public function create()
    {
        $this->_captcha->create();

        return true;
    }
}