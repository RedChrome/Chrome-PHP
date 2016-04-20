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
namespace Chrome\Form\Option\Element;

interface Captcha_Interface extends \Chrome\Form\Option\Element_Interface
{
    /**
     *
     * @return \Chrome\Captcha\Captcha_Interface
     */
    public function getCaptcha();

    /**
     * Returns true if the captcha gets re-created if it was invalid
     *
     * @return boolean
     */
    public function getRecreateIfInvalid();
}

class Captcha extends \Chrome\Form\Option\Element implements Captcha_Interface
{
    protected $_form = null;

    protected $_captcha = null;

    protected $_frontendOptions = array();

    protected $_backendOptions = array();

    protected $_recreateIfInvalid = true;

    public function __construct(\Chrome\Form\Form_Interface $form)
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

    public function setCaptcha(\Chrome\Captcha\Captcha_Interface $captcha)
    {
        $this->_captcha = $captcha;
    }

    public function getCaptcha()
    {
        if ($this->_captcha === null) {
            $this->_captcha = new \Chrome\Captcha\Captcha($this->_form->getID(), $this->_form->getApplicationContext(), $this->_frontendOptions, $this->_backendOptions);
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
namespace Chrome\Form\Element;

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Form
 */
class Captcha extends \Chrome\Form\Element\AbstractElement implements \Chrome\Form\Element\Interfaces\Captcha
{
    protected $_captcha = null;

    protected $_reCreated = false;

    protected $_created = false;

    public function __construct(\Chrome\Form\Form_Interface $form, $id, \Chrome\Form\Option\Element\Captcha_Interface $option)
    {
        parent::__construct($form, $id, $option);
        $this->_captcha = $option->getCaptcha();
    }

    public function isCreated()
    {
        if($this->_created === false) {
            $this->_errors[] = 'captcha_not_created';
        }

        return $this->_created;
    }

    protected function _isValid()
    {
        $isValid = parent::_isValid();

        // only re-create the captcha one time and only if the option says to recreate it.
        if ($this->_created AND $this->_reCreated === false AND !$isValid AND $this->_option->getRecreateIfInvalid() === true) {
            $this->_captcha->create();
            $this->_reCreated = true;
        }

        return $isValid;
    }

    public function renew()
    {
        if($this->_created) {
            $this->_captcha->create();
        }
    }

    protected function _getValidator()
    {
        return new \Chrome\Validator\Form\Element\CaptchaValidator($this->_captcha);
    }

    public function create()
    {
        if ($this->_captcha instanceof \Chrome\Captcha\Captcha_Interface) {
            $this->_captcha->create();

            $this->_created = true;

            return true;
        }

        return false;
    }
}