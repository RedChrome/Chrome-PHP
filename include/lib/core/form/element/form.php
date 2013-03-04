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
 * @package    CHROME-PHP
 * @subpackage Chrome.Form
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [05.01.2013 16:28:15] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true)
    die();

/**
 * Basic form element class for ALL forms!
 * 
 * This element checks whether the user has sent a proper form, that means:
 *     1. The user has to send the right token. This token is created randomly and saved in session. 
 *         To retriev the token use getOptions(self::CHROME_FORM_ELEMENT_FORM_TOKEN)
 *     2. The user has to send the form in a specific time intervall
 *     
 * Any violation of these rules will cause the form to be invalid! 
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Form
 */
class Chrome_Form_Element_Form extends Chrome_Form_Element_Abstract
{
    /**
     * Options for this element:
     * 
     * - CHROME_FORM_ELEMENT_FORM_SESSION_NAMESPACE: 
     *     Cannot be set! Namespace in session
     *     
     * - CHROME_FORM_ELEMENT_FORM_TOKEN:
     *     Cannot be set! Namespace in session for token
     *         => Wil raise CHROME_FORM_ELEMENT_FORM_ERROR_TOKEN, if sent token didn't macht the saved(in session) token
     *     
     * - CHROME_FORM_ELEMENT_FORM_TOKEN_NAMESPACE:
     *     (string) the name of the input field for sending the token, default: 'token'
     *     
     * - CHROME_FORM_ELEMENT_FORM_TIME:
     *     Cannot be set! Namespace in session for time
     *     
     * - CHROME_FORM_ELEMENT_FORM_MAX_ALLOWED_TIME:
     *     (int): Time in seconds to accomplish the sending of the form.
     *         => Will raise CHROME_FORM_ELEMENT_FORM_ERROR_MAX_ALLOWED_TIME
     *     
     * - CHROME_FORM_ELEMENT_FORM_MIN_ALLOWED_TIME:
     *     (int): Time in seconds the user needs at least to send the form.
     *         => Will raise CHROME_FORM_ELEMENT_FORM_ERROR_MIN_ALLOWED_TIME
     * 
     * @var string
     */
    const
        CHROME_FORM_ELEMENT_FORM_SESSION_NAMESPACE  = 'FORM',
        CHROME_FORM_ELEMENT_FORM_TOKEN              = 'TOKEN',
        CHROME_FORM_ELEMENT_FORM_TOKEN_NAMESPACE    = 'TOKENNAMESPACE',
        CHROME_FORM_ELEMENT_FORM_TIME               = 'TIME',
        CHROME_FORM_ELEMENT_FORM_MAX_ALLOWED_TIME   = 'MAXALLOWEDTIME',
        CHROME_FORM_ELEMENT_FORM_MIN_ALLOWED_TIME   = 'MINALLOWEDTIME';

        
    /**
     * Errors of this element:
     * 
     * CHROME_FORM_ELEMENT_FORM_ERROR_MAX_ALLOWED_TIME: 
     *     Happens if the user waitet more than $CHROME_FORM_ELEMENT_FORM_MAX_ALLOWED_TIME seconds
     * 
     * CHROME_FORM_ELEMENT_FORM_ERROR_MIN_ALLOWED_TIME:
     *     Happens if the user was faster than $CHROME_FORM_ELEMENT_FORM_MIN_ALLOWED_TIME seconds
     *    
     * CHROME_FORM_ELEMENT_FORM_ERROR_TOKEN:
     *     Happens if the sent token didnt match the saved token -> Protection against XSRF
     *     
     * @var unknown
     */
    const
        CHROME_FORM_ELEMENT_FORM_ERROR_MAX_ALLOWED_TIME = 'ERRORMAXALLOWEDTIME',
        CHROME_FORM_ELEMENT_FORM_ERROR_MIN_ALLOWED_TIME = 'ERRORMINALLOWEDTIME',
        CHROME_FORM_ELEMENT_FORM_ERROR_TOKEN            = 'ERRORTOKEN';

    protected $_defaultOptions = array(self::CHROME_FORM_ELEMENT_FORM_MAX_ALLOWED_TIME => 3600,
                                       self::CHROME_FORM_ELEMENT_FORM_MIN_ALLOWED_TIME => 0,
                                       self::CHROME_FORM_ELEMENT_FORM_TIME => CHROME_TIME,
                                       self::CHROME_FORM_ELEMENT_FORM_TOKEN_NAMESPACE => 'token');

    /**
     * Default constructor of all form elements
     * 
     * @param Chrome_Form_Interface $form the form which should contain this element
     * @param mixed $id the id of this new element, must be unique
     * @param array $options additional options for this element
     * @return Chrome_Form_Element_Form
     */
    public function __construct(Chrome_Form_Interface $form, $id, array $options)
    {
        // this checks whether the form was created before, and if, then we use the token from the last time
        // we have to renew the timer!
        $session = Chrome_Session::getInstance();
        if(isset($session[self::CHROME_FORM_ELEMENT_SESSION_NAMESPACE][$id][self::CHROME_FORM_ELEMENT_FORM_SESSION_NAMESPACE])) {
            $sessionData = $session[self::CHROME_FORM_ELEMENT_SESSION_NAMESPACE][$id][self::CHROME_FORM_ELEMENT_FORM_SESSION_NAMESPACE];
            if(isset($sessionData[self::CHROME_FORM_ELEMENT_FORM_TOKEN]) AND !isset($options[self::CHROME_FORM_ELEMENT_FORM_TOKEN])) {
                $options[self::CHROME_FORM_ELEMENT_FORM_TOKEN] = $sessionData[self::CHROME_FORM_ELEMENT_FORM_TOKEN];
        
                // renew the timer
                $sessionData[self::CHROME_FORM_ELEMENT_FORM_TIME] = CHROME_TIME;
            }
        }
        parent::__construct($form, $id, $options);
    }
        
    protected function _isCreated()
    {
        $session = Chrome_Session::getInstance();

        if(!isset($session[self::CHROME_FORM_ELEMENT_SESSION_NAMESPACE][$this->_form->getID()][self::CHROME_FORM_ELEMENT_FORM_SESSION_NAMESPACE])) {
            $this->_errors[] = self::CHROME_FORM_ELEMENT_ERROR_NOT_CREATED;
            return false;
        }

        // is it expired?
        if($session[self::CHROME_FORM_ELEMENT_SESSION_NAMESPACE][$this->_form->getID()][self::CHROME_FORM_ELEMENT_FORM_SESSION_NAMESPACE][self::CHROME_FORM_ELEMENT_FORM_TIME] +  $this->_options[self::CHROME_FORM_ELEMENT_FORM_MIN_ALLOWED_TIME] > CHROME_TIME) {
            $this->_errors[] = self::CHROME_FORM_ELEMENT_ERROR_NOT_CREATED;
            return false;
        }

        return true;
    }

    protected function _isValid()
    {
        $session = Chrome_Session::getInstance();

        if(!isset($session[self::CHROME_FORM_ELEMENT_SESSION_NAMESPACE][$this->_form->getID()][self::CHROME_FORM_ELEMENT_FORM_SESSION_NAMESPACE])) {
            return false;
        }

        $sessionData = $session[self::CHROME_FORM_ELEMENT_SESSION_NAMESPACE][$this->_form->getID()][self::CHROME_FORM_ELEMENT_FORM_SESSION_NAMESPACE];

        if($sessionData[self::CHROME_FORM_ELEMENT_FORM_TOKEN] !== $this->getData()) {
            $this->_errors[] = self::CHROME_FORM_ELEMENT_FORM_ERROR_TOKEN;
            return false;
        }

        if($sessionData[self::CHROME_FORM_ELEMENT_FORM_TIME] + $this->_options[self::CHROME_FORM_ELEMENT_FORM_MAX_ALLOWED_TIME] < CHROME_TIME) {
            $this->_errors[] = self::CHROME_FORM_ELEMENT_FORM_ERROR_MAX_ALLOWED_TIME;
            return false;
        }

        if($sessionData[self::CHROME_FORM_ELEMENT_FORM_TIME] + $this->_options[self::CHROME_FORM_ELEMENT_FORM_MIN_ALLOWED_TIME] > CHROME_TIME) {
            $this->_errors[] = self::CHROME_FORM_ELEMENT_FORM_ERROR_MIN_ALLOWED_TIME;
            return false;
        }

        return true;
    }

    protected function _isSent()
    {
        if($this->getData() !== null) {

            return true;
        } else {
            $this->_renewTimer();
            $this->_errors[] = self::CHROME_FORM_ELEMENT_ERROR_NOT_SENT;
                                     
            return false;
        }
    }

    public function delete()
    {
        $session = Chrome_Session::getInstance();

        $data = $session[self::CHROME_FORM_ELEMENT_SESSION_NAMESPACE];
        unset($data[$this->_form->getID()][self::CHROME_FORM_ELEMENT_FORM_SESSION_NAMESPACE]);
        $session[self::CHROME_FORM_ELEMENT_SESSION_NAMESPACE] = $data;
    }

    public function getData()
    {
        return $this->_form->getSentData($this->_options[self::CHROME_FORM_ELEMENT_FORM_TOKEN_NAMESPACE]);
    }

    public function create()
    {
        if(!isset($this->_options[self::CHROME_FORM_ELEMENT_FORM_TOKEN])) {
            $this->_options[self::CHROME_FORM_ELEMENT_FORM_TOKEN] = $this->_createToken();
        }

        $session = Chrome_Session::getInstance();

        $formData = $session[self::CHROME_FORM_ELEMENT_SESSION_NAMESPACE];
        $formData
            [$this->_form->getID()]
                [self::CHROME_FORM_ELEMENT_FORM_SESSION_NAMESPACE] = array(self::CHROME_FORM_ELEMENT_FORM_TIME => $this->_options[self::CHROME_FORM_ELEMENT_FORM_TIME],
                                                                            self::CHROME_FORM_ELEMENT_FORM_TOKEN => $this->_options[self::CHROME_FORM_ELEMENT_FORM_TOKEN]);

        $session[self::CHROME_FORM_ELEMENT_SESSION_NAMESPACE] = $formData;
    }

    public function renew() {

        $session = Chrome_Session::getInstance();

        $token = $this->_createToken();

        $formData = $session[self::CHROME_FORM_ELEMENT_SESSION_NAMESPACE];

        $formData[$this->_form->getID()][self::CHROME_FORM_ELEMENT_FORM_SESSION_NAMESPACE] =
                                                array(
                                                    self::CHROME_FORM_ELEMENT_FORM_TIME => CHROME_TIME,
                                                    self::CHROME_FORM_ELEMENT_FORM_TOKEN => $token
                                                    );

        $session[self::CHROME_FORM_ELEMENT_SESSION_NAMESPACE] = $formData;

        $this->_options[self::CHROME_FORM_ELEMENT_FORM_TOKEN] = $token;
        $this->_options[self::CHROME_FORM_ELEMENT_FORM_TIME] = CHROME_TIME;
    }

    public function save() {

    }

    protected function _createToken() {
        return md5(uniqid(mt_rand(), true));
    }
    
    /**
     * sets the timer for this form to the current time
     * This does not check whether its needed or not. For this see {@see _renewTimerIfNeeded}
     * 
     * @return void
     */
    protected function _renewTimer() {
        $session = Chrome_Session::getInstance();

        $formData = $session[self::CHROME_FORM_ELEMENT_SESSION_NAMESPACE];

        $formData[$this->_form->getID()][self::CHROME_FORM_ELEMENT_FORM_SESSION_NAMESPACE][self::CHROME_FORM_ELEMENT_FORM_TIME] = CHROME_TIME;

        $session[self::CHROME_FORM_ELEMENT_SESSION_NAMESPACE] = $formData;
    }
}