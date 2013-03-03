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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [03.03.2013 11:17:27] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true)
    die();

/**
 * TODO: add documentation
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Form
 */
class Chrome_Form_Element_Form extends Chrome_Form_Element_Abstract
{
    const
        CHROME_FORM_ELEMENT_FORM_SESSION_NAMESPACE  = 'FORM',
        CHROME_FORM_ELEMENT_FORM_TOKEN              = 'TOKEN',
        CHROME_FORM_ELEMENT_FORM_TOKEN_NAMESPACE    = 'TOKENNAMESPACE',
        CHROME_FORM_ELEMENT_FORM_TIME               = 'TIME',
        CHROME_FORM_ELEMENT_FORM_MAX_ALLOWED_TIME   = 'MAXALLOWEDTIME',
        CHROME_FORM_ELEMENT_FORM_MIN_ALLOWED_TIME   = 'MINALLOWEDTIME';


    const
        CHROME_FORM_ELEMENT_FORM_ERROR_MAX_ALLOWED_TIME = 'ERRORMAXALLOWEDTIME',
        CHROME_FORM_ELEMENT_FORM_ERROR_MIN_ALLOWED_TIME = 'ERRORMINALLOWEDTIME',
        CHROME_FORM_ELEMENT_FORM_ERROR_TOKEN            = 'ERRORTOKEN',
        CHROME_FORM_ELEMENT_FORM_ERROR_NOT_SENT         = 'ERRORNOTSENT';

    protected $_defaultOptions = array(self::CHROME_FORM_ELEMENT_FORM_MAX_ALLOWED_TIME => 3600,
                                       self::CHROME_FORM_ELEMENT_FORM_MIN_ALLOWED_TIME => 0,
                                       self::CHROME_FORM_ELEMENT_FORM_TIME => CHROME_TIME,
                                       self::CHROME_FORM_ELEMENT_FORM_TOKEN_NAMESPACE => 'token');

    public function __construct(Chrome_Form_Interface $form, $id, array $options) {

        // this checks whether the form was created before, and if, then we use the token from the last time
        // we have to renew the timer!

        $this->_session = $form->getRequestData()->getSession();
        if(isset($this->_session[self::CHROME_FORM_ELEMENT_SESSION_NAMESPACE][$id][self::CHROME_FORM_ELEMENT_FORM_SESSION_NAMESPACE])) {

            $sessionData = $this->_session[self::CHROME_FORM_ELEMENT_SESSION_NAMESPACE][$id][self::CHROME_FORM_ELEMENT_FORM_SESSION_NAMESPACE];
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
        if(!isset($this->_session[self::CHROME_FORM_ELEMENT_SESSION_NAMESPACE][$this->_form->getID()][self::CHROME_FORM_ELEMENT_FORM_SESSION_NAMESPACE])) {
            $this->_errors[] = self::CHROME_FORM_ELEMENT_ERROR_NOT_CREATED;
            return false;
        }

        // is it expired?
        if($this->_session[self::CHROME_FORM_ELEMENT_SESSION_NAMESPACE][$this->_form->getID()][self::CHROME_FORM_ELEMENT_FORM_SESSION_NAMESPACE][self::CHROME_FORM_ELEMENT_FORM_TIME] +  $this->_options[self::CHROME_FORM_ELEMENT_FORM_MIN_ALLOWED_TIME] > CHROME_TIME) {
            $this->_errors[] = self::CHROME_FORM_ELEMENT_ERROR_NOT_CREATED;
            return false;
        }

        return true;
    }

    protected function _isValid()
    {
        if(!isset($this->_session[self::CHROME_FORM_ELEMENT_SESSION_NAMESPACE][$this->_form->getID()][self::CHROME_FORM_ELEMENT_FORM_SESSION_NAMESPACE])) {
            return false;
        }

        $sessionData = $this->_session[self::CHROME_FORM_ELEMENT_SESSION_NAMESPACE][$this->_form->getID()][self::CHROME_FORM_ELEMENT_FORM_SESSION_NAMESPACE];

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
        $data = $this->_session[self::CHROME_FORM_ELEMENT_SESSION_NAMESPACE];
        unset($data[$this->_form->getID()][self::CHROME_FORM_ELEMENT_FORM_SESSION_NAMESPACE]);
        $this->_session[self::CHROME_FORM_ELEMENT_SESSION_NAMESPACE] = $data;
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

        $formData = $this->_session[self::CHROME_FORM_ELEMENT_SESSION_NAMESPACE];
        $formData
            [$this->_form->getID()]
                [self::CHROME_FORM_ELEMENT_FORM_SESSION_NAMESPACE] = array(self::CHROME_FORM_ELEMENT_FORM_TIME => $this->_options[self::CHROME_FORM_ELEMENT_FORM_TIME],
                                                                            self::CHROME_FORM_ELEMENT_FORM_TOKEN => $this->_options[self::CHROME_FORM_ELEMENT_FORM_TOKEN]);

        $this->_session[self::CHROME_FORM_ELEMENT_SESSION_NAMESPACE] = $formData;
    }

    public function renew() {

        $token = $this->_createToken();

        $formData = $this->_session[self::CHROME_FORM_ELEMENT_SESSION_NAMESPACE];

        $formData[$this->_form->getID()][self::CHROME_FORM_ELEMENT_FORM_SESSION_NAMESPACE] =
                                                array(
                                                    self::CHROME_FORM_ELEMENT_FORM_TIME => CHROME_TIME,
                                                    self::CHROME_FORM_ELEMENT_FORM_TOKEN => $token
                                                    );

        $this->_session[self::CHROME_FORM_ELEMENT_SESSION_NAMESPACE] = $formData;

        $this->_options[self::CHROME_FORM_ELEMENT_FORM_TOKEN] = $token;
        $this->_options[self::CHROME_FORM_ELEMENT_FORM_TIME] = CHROME_TIME;
    }

    public function save() {

    }

    protected function _createToken() {
        return md5(uniqid(mt_rand(), true));
    }

    protected function _renewTimer() {
        $formData = $this->_session[self::CHROME_FORM_ELEMENT_SESSION_NAMESPACE];

        $formData[$this->_form->getID()][self::CHROME_FORM_ELEMENT_FORM_SESSION_NAMESPACE][self::CHROME_FORM_ELEMENT_FORM_TIME] = CHROME_TIME;

        $this->_session[self::CHROME_FORM_ELEMENT_SESSION_NAMESPACE] = $formData;
    }
}