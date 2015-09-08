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
 * @subpackage Chrome.Validator
 */

namespace Chrome\Validator\Form\Element;

use \Chrome\Validator\AbstractValidator;

/**
 * This validates the form element "form"
 *
 *
 *
 *
 * @package		CHROME-PHP
 * @subpackage  Chrome.Validator
 */
class ElementFormValidator extends AbstractValidator
{
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
            CHROME_FORM_ELEMENT_FORM_ERROR_TOKEN = 'token_not_valid';

    protected $_id = '';

    protected $_formOption = null;

    public function __construct($id, \Chrome\Form\Option\Element\Form $formOptions)
    {
        $this->_id = $id;
        $this->_formOption = $formOptions;
    }

    protected function _validate()
    {
        $this->_namespace = 'plugins/validate/form/element/form';

        $storedData = $this->_formOption->getStorage()->get($this->_id);

        if($storedData[\Chrome\Form\Element\Form::CHROME_FORM_ELEMENT_FORM_TOKEN] !== $this->_data)
        {
            $this->_setError(self::CHROME_FORM_ELEMENT_FORM_ERROR_TOKEN);
            return false;
        }

        if($storedData[\Chrome\Form\Element\Form::CHROME_FORM_ELEMENT_FORM_TIME] + $this->_formOption->getMaxAllowedTime() < $this->_formOption->getTime())
        {
            $this->_setError(self::CHROME_FORM_ELEMENT_FORM_ERROR_MAX_ALLOWED_TIME);
            return false;
        }

        if($storedData[\Chrome\Form\Element\Form::CHROME_FORM_ELEMENT_FORM_TIME] + $this->_formOption->getMinAllowedTime() > $this->_formOption->getTime())
        {
            $this->_setError(self::CHROME_FORM_ELEMENT_FORM_ERROR_MIN_ALLOWED_TIME);
            return false;
        }
    }
}