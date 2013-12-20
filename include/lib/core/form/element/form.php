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

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Form
 */
class Chrome_Form_Option_Element_Form extends Chrome_Form_Option_Element_Basic
{
    protected $_token = null;
    protected $_maxTime = 3600;
    protected $_minTime = 0;
    protected $_storage = null;
    protected $_tokenNamespace = 'token';
    protected $_time = CHROME_TIME;

    public function __construct(Chrome_Form_Storage_Interface $storage)
    {
        $this->setStorage($storage);
    }

    public function setToken($token)
    {
        $this->_token = $token;
        return $this;
    }

    public function getToken()
    {
        return $this->_token;
    }

    public function setMaxAllowedTime($time)
    {
        $this->_maxTime = (int) $time;
        return $this;
    }

    public function getMaxAllowedTime()
    {
        return $this->_maxTime;
    }

    public function setMinAllowedTime($time)
    {
        $this->_minTime = (int) $time;
        return $this;
    }

    public function getMinAllowedTime()
    {
        return $this->_minTime;
    }

    public function setStorage(Chrome_Form_Storage_Interface $storage)
    {
        $this->_storage = $storage;
    }

    public function getStorage()
    {
        return $this->_storage;
    }

    public function getTokenNamespace()
    {
        return $this->_tokenNamespace;
    }

    public function setTokenNamespace($namespace)
    {
        $this->_tokenNamespace = $namespace;
    }

    public function getTime()
    {
        return $this->_time;
    }

    public function setTime($time)
    {
        $this->_time = (int) $time;
    }
}

/**
 * Basic form element class for ALL forms!
 *
 * This element checks whether the user has sent a proper form, that means:
 * 1. The user has to send the right token. This token is created randomly and saved in session.
 * To retriev the token use getOptions(self::CHROME_FORM_ELEMENT_FORM_TOKEN)
 * 2. The user has to send the form in a specific time intervall
 *
 * Any violation of these rules will cause the form to be invalid!
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Form
 */
class Chrome_Form_Element_Form extends Chrome_Form_Element_Basic_Abstract
{
    /**
     * Options for this element:
     *
     * - CHROME_FORM_ELEMENT_FORM_TOKEN:
     * Cannot be set! Namespace in session for token
     * => Wil raise CHROME_FORM_ELEMENT_FORM_ERROR_TOKEN, if sent token didn't macht the saved(in session) token
     *
     * - CHROME_FORM_ELEMENT_FORM_TOKEN_NAMESPACE:
     * (string) the name of the input field for sending the token, default: 'token'
     *
     * - CHROME_FORM_ELEMENT_FORM_TIME:
     * Cannot be set! Namespace in session for time
     *
     * - CHROME_FORM_ELEMENT_FORM_MAX_ALLOWED_TIME:
     * (int): Time in seconds to accomplish the sending of the form.
     * => Will raise CHROME_FORM_ELEMENT_FORM_ERROR_MAX_ALLOWED_TIME
     *
     * - CHROME_FORM_ELEMENT_FORM_MIN_ALLOWED_TIME:
     * (int): Time in seconds the user needs at least to send the form.
     * => Will raise CHROME_FORM_ELEMENT_FORM_ERROR_MIN_ALLOWED_TIME
     *
     * @var string
     */
    const CHROME_FORM_ELEMENT_FORM_TOKEN = 'TOKEN',
            CHROME_FORM_ELEMENT_FORM_TOKEN_NAMESPACE = 'TOKENNAMESPACE',
            CHROME_FORM_ELEMENT_FORM_TIME = 'TIME',
            CHROME_FORM_ELEMENT_FORM_MAX_ALLOWED_TIME = 'MAXALLOWEDTIME',
            CHROME_FORM_ELEMENT_FORM_MIN_ALLOWED_TIME = 'MINALLOWEDTIME';

    /**
     * Errors of this element:
     *
     * CHROME_FORM_ELEMENT_FORM_ERROR_MAX_ALLOWED_TIME:
     * Happens if the user waited more than $CHROME_FORM_ELEMENT_FORM_MAX_ALLOWED_TIME seconds
     *
     * CHROME_FORM_ELEMENT_FORM_ERROR_MIN_ALLOWED_TIME:
     * Happens if the user was faster than $CHROME_FORM_ELEMENT_FORM_MIN_ALLOWED_TIME seconds
     *
     * CHROME_FORM_ELEMENT_FORM_ERROR_TOKEN:
     * Happens if the sent token didnt match the saved token -> Protection against XSRF
     *
     * @var unknown
     */
    const CHROME_FORM_ELEMENT_FORM_ERROR_MAX_ALLOWED_TIME = 'maximum_time_exceeded',
             CHROME_FORM_ELEMENT_FORM_ERROR_MIN_ALLOWED_TIME = 'minimum_time_fall_short',
             CHROME_FORM_ELEMENT_FORM_ERROR_TOKEN = 'token_not_valid',
             CHROME_FORM_ELEMENT_ERROR_NOT_CREATED = 'form_not_created';

    protected $_storage = null;

    /**
     *
     * @param Chrome_Form_Interface $form
     *        the form which should contain this element
     * @param string $id
     *        the id of this new element, must be unique
     * @param Chrome_Form_Option_Element_Form $options
     *        options for this element
     * @return Chrome_Form_Element_Form
     */
    public function __construct(Chrome_Form_Interface $form, $id, Chrome_Form_Option_Element_Form $option)
    {
        parent::__construct($form, $id, $option);

        $this->setStorage($option->getStorage());
        // this checks whether the form was created before, and if it was created, then we use the token from the last time
        // we have to renew the timer!
        if($this->_storage->has($this->_id) === true)
        {
            $storedData = $this->_storage->get($this->_id);

            if(isset($storedData[self::CHROME_FORM_ELEMENT_FORM_TOKEN]) and $this->_option->getToken() === null)
            {
                $this->_option->setToken($storedData[self::CHROME_FORM_ELEMENT_FORM_TOKEN]);

                // renew the timer
                // storedData[self::CHROME_FORM_ELEMENT_FORM_TIME] = CHROME_TIME;

                $this->_storage->set($this->_id, $storedData);
            }
        }
    }

    protected function _isCreated()
    {
        if(!$this->_storage->has($this->_id))
        {
            $this->_errors[] = self::CHROME_FORM_ELEMENT_ERROR_NOT_CREATED;
            return false;
        }

        //$storedData = $this->_storage->get($this->_id);

        return true;
    }

    public function isValid()
    {
        if($this->_isValid !== null)
        {
            return $this->_isValid;
        }

        $storedData = $storedData = $this->_storage->get($this->_id);

        if($storedData[self::CHROME_FORM_ELEMENT_FORM_TOKEN] !== $this->getData())
        {
            $this->_errors[] = self::CHROME_FORM_ELEMENT_FORM_ERROR_TOKEN;
            $this->_isValid = false;
            return false;
        }

        if($storedData[self::CHROME_FORM_ELEMENT_FORM_TIME] + $this->_option->getMaxAllowedTime() < $this->_option->getTime())
        {
            $this->_errors[] = self::CHROME_FORM_ELEMENT_FORM_ERROR_MAX_ALLOWED_TIME;
            $this->_isValid = false;
            return false;
        }

        if($storedData[self::CHROME_FORM_ELEMENT_FORM_TIME] + $this->_option->getMinAllowedTime() > $this->_option->getTime())
        {
            $this->_errors[] = self::CHROME_FORM_ELEMENT_FORM_ERROR_MIN_ALLOWED_TIME;
            $this->_isValid = false;
            return false;
        }

        $this->_isValid = true;
        return true;
    }

    protected function _isSent()
    {
        if($this->getData() !== null)
        {

            return true;
        } else
        {
            $this->_renewTimer();
            $this->_errors[] = self::ERROR_NOT_SENT;

            return false;
        }
    }

    public function destroy()
    {
        $this->_storage->remove($this->_id);
    }

    public function create()
    {
        if($this->_option->getToken() === null)
        {
            $this->_option->setToken($this->_createToken());
        }

        $data = array(self::CHROME_FORM_ELEMENT_FORM_TIME => $this->_option->getTime(), self::CHROME_FORM_ELEMENT_FORM_TOKEN => $this->_option->getToken());

        $this->_storage->set($this->_id, $data);
    }

    public function renew()
    {
        $token = $this->_createToken();

        $formData = $this->_storage->get($this->_id);

        $time = $this->_option->getTime();

        $data = array(self::CHROME_FORM_ELEMENT_FORM_TIME => $time, self::CHROME_FORM_ELEMENT_FORM_TOKEN => $token);

        $this->_storage->set($this->_id, $data);

        $this->_option->setToken($token);
        $this->_option->setTime($time);
    }

    public function setStorage(Chrome_Form_Storage_Interface $storage)
    {
        $this->_storage = $storage;
    }

    public function getStorage()
    {
        return $this->_storage;
    }

    protected function _createToken()
    {
        return md5(uniqid(mt_rand(), true));
    }

    public function getOption()
    {
        return $this->_option;
    }

    protected function _getData()
    {
        return $this->_convert($this->_form->getSentData($this->_option->getTokenNamespace()));
    }

    /**
     * sets the timer for this form to the current time
     *
     * @return void
     */
    protected function _renewTimer()
    {
        $data = $this->_storage->get($this->_id);

        $data[self::CHROME_FORM_ELEMENT_FORM_TIME] = $this->_option->getTime();

        $this->_storage->set($this->_id, $data);
    }
}