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
if(CHROME_PHP !== true)
    die();

interface Chrome_Form_Option_Element_Captcha_Interface extends Chrome_Form_Option_Element_Interface
{
    public function getCaptcha();
}

class Chrome_Form_Option_Element_Captcha extends Chrome_Form_Option_Element implements Chrome_Form_Option_Element_Captcha_Interface
{
    protected $_form = null;

    protected $_captcha = null;

    protected $_frontendOptions = array();

    protected $_backendOptions = array();

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
}

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Form
 */
class Chrome_Form_Element_Captcha extends Chrome_Form_Element_Abstract
{
    protected $_captcha = null;

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

    protected function _getValidator()
    {
        return new Chrome_Validator_Form_Element_Captcha($this->_captcha);
    }

    public function create()
    {
        $this->_captcha->create();

        return true;
    }
}