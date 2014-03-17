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

/**
 * Interface for validator classes
 *
 * The validation logic should be inside validate().
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Validator
 */
interface Chrome_Validator_Interface
{
    /**
     * Resets all previously done configurations, validations or other customizations.
     *
     * The previously set options will not be modified. Use this, if you want to validate other data
     *
     * @return void
     */
    public function reset();

    /**
     * Sets the data to validate
     *
     * @param mixed $data
     * @return void
     */
    public function setData($data);

    /**
     * Sets additional options for validator
     *
     * @param array $options array containing options, see impl. for concrete options
     * @return void
     */
    public function setOptions(array $options);

    /**
     * Validates the data
     *
     * @return void
     */
    public function validate();

    /**
     * Returns true if data is valid
     *
     * @return bool true if data is valid, false else
     */
    public function isValid();

    /**
     * Returns exactly the same as isValid with the given data.
     *
     * This is a shortcut for setting data, validating it and retrieving the isValid result.
     *
     * @param mixed $data
     * @return boolean
     */
    public function isValidData($data);

    /**
     * Returns one error while validating or an error with the data
     *
     * @return string
     */
    public function getError();

    /**
     * Returns all errors
     *
     * @return array numerically indexed
     */
    public function getAllErrors();
}

/**
 * Interface for validator classes which can append other validators
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Validator
 */
interface Chrome_Validator_Composition_Interface extends Chrome_Validator_Interface
{
    /**
     * Adds a validator
     *
     * @param Chrome_Validator_Interface $validator validator to add
     * @return void
     */
    public function addValidator(Chrome_Validator_Interface $validator);

    /**
     * adds validators given in array (as values, key gets ignored) in the given order
     *
     * Throws an Chrome_InvalidArgumentException if a value of the array does not contain a appropriate class
     *
     * @param array $validators an array containing as values classes which implements Chrome_Validator_Interface
     * @return void
     */
    public function addValidators(array $validators);

    /**
     * Unsets the current validators and adds validators given in array (as values, key gets ignored) in the given order
     *
     * Throws an Chrome_InvalidArgumentException if a value of the array does not contain a appropriate class
     *
     * @param array $validators an array containing as values classes which implements Chrome_Validator_Interface
     * @return void
     */
    public function setValidators(array $validators);

    /**
     * Returns the validators set via add/setValidator.
     *
     * The return value is a array (numerically indexed), which values are the validators
     *
     * @return array
     */
    public function getValidators();

    /**
     * Returns the validator at the $index position
     *
     * If $index is not set, then it returns null, otherwise the requested validator
     *
     * @return Chrome_Validator_Interface or null
     */
    public function getValidator($index);
}

/**
 * Chrome_Validator
 *
 * Example:
 * <code>
 * class Chrome_Validator_Test extends Chrome_Validator {
 *
 *	private $_email;
 *
 * 	public function __construct($email) {
 * 		$this->_email = $email;
 * 		parent::__construct();
 * 	}
 *
 * 	private function _validate() {
 *
 * 		if(strlen($email) < 5) {
 * 			$this->setError('Email too short');
 * 		} // etc..
 * 	}
 * }
 *
 * $v['e1'] = new Chrome_Validator_Test($_POST['email']);
 * $v['e2'] = new Chrome_Valiadtor_Test($_POST['email']);
 * // add here another Validator e.g. Chrome_Validator_User
 *
 * foreach($v AS $validator) {
 * 	if(!$validator->isValid()) {
 * 		while($error = $validator->getError())
 * 			$errorMsg .= $error;
 * 	}
 * }
 *
 * echo $errorMsg;
 *
 * </code>
 *
 * @package		CHROME-PHP
 * @subpackage  Chrome.Validator
 */
abstract class Chrome_Validator implements Chrome_Validator_Interface
{
    /**
     * Stores options
     *
     * @var array
     */
    protected $_options = array();

    /**
     * stores all error messages
     *
     * @var array
     */
    protected $_errorMsg = array();

    /**
     * Says whether the last validate() call was valid or not
     *
     * Not used for caching!
     *
     * @var boolean
     */
    protected $_isValid = null;

    /**
     * Data to validate
     *
     * @var mixed
     */
    protected $_data = null;

    /**
     * Sets additional options
     *
     * Use this method only if its really necessary
     *
     * @return void
     */
    public function setOptions(array $options)
    {
        $this->_options = $options;
    }

    /**
     * Validates the data
     */
    public function validate()
    {
        $boolean = $this->_validate();

        if(is_bool($boolean)) {
            $this->_isValid = $boolean;
        } else {
            $this->_isValid = (sizeof($this->_errorMsg) === 0);
        }
    }

    /**
     * Sets the data to validate
     *
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->_data = $data;
    }

    /**
     * Concrete implementation of validation logic
     *
     * If a boolean is returned, then isValid will return exactly this boolean
     * If null is returned, then isValid will return true if no error was set.
     *
     * @return boolean|null
     */
    abstract protected function _validate();

    /**
     * Adds a error message
     *
     * @param string $msg error message
     */
    protected function _setError($msg, array $params = null)
    {
        if($params !== null)
        {
            $this->_errorMsg[] = array($msg, $params);
        } else
        {
            $this->_errorMsg[] = $msg;
        }
    }

    /**
     * @see Chrome_Validator_Interface::reset()
     */
    public function reset()
    {
        $this->_errorMsg = array();
        $this->_data = null;
        $this->_isValid = null;
    }

    /**
     * @see Chrome_Validator_Interface::isValidData()
     */
    public function isValidData($data)
    {
        $this->_reset();
        $this->setData($data);
        $this->validate();

        return $this->_isValid;
    }

    /**
     * Determines whether $data was valid/invalid using this validator
     *
     * @return boolean
     */
    public function isValid()
    {
        return $this->_isValid;
    }

    /**
     * pops an error off
     *
     * @return string error message
     */
    public function getError()
    {
        return array_pop($this->_errorMsg);
    }

    /**
     * Gets all error messages
     *
     * @return array
     */
    public function getAllErrors()
    {
        $return = $this->_errorMsg;
        $this->_errorMsg = array();
        return $return;
    }
}

abstract class Chrome_Validator_Composition_Abstract extends Chrome_Validator implements Chrome_Validator_Composition_Interface
{
    protected $_validators = array();

    public function addValidator(Chrome_Validator_Interface $validator)
    {
        $this->_validators[] = $validator;
    }

    public function addValidators(array $validators)
    {
        foreach($validators as $validator) {
            if($validator instanceof Chrome_Validator_Interface) {
                $this->_validators[] = $validator;
            } else {
                throw new Chrome_InvalidArgumentException('An element of the array was not a subclass of Chrome_Validator_Interface!');
            }
        }
    }

    public function setValidators(array $validators)
    {
        $this->_validators = array();

        $this->addValidators($validators);
    }

    public function getValidators()
    {
        return $this->_validators;
    }

    public function getValidator($index)
    {
        return isset($this->_validators[$index]) ? $this->_validators[$index] : null;
    }

    public function setData($data)
    {
        foreach($this->_validators as $validator)
        {
            $validator->setData($data);
        }
    }
}

/**
 * Use this validator if you want to compose a complex set of validators together
 *
 * The composing logic should get put in _getValidator
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Validator
 */
abstract class Chrome_Validator_Composer_Abstract extends Chrome_Validator
{
    /**
     * Composes multiple validators to one complex validator
     *
     * @return Chrome_Validator_Interface
     */
    abstract protected function _getValidator();

    protected function _validate()
    {
        $validator = $this->_getValidator();
        $validator->setData($this->_data);
        $validator->validate();

        return $validator->isValid();
    }
}

/**
 * An abstract validator, which needs a configuration
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Validator
 */
abstract class Chrome_Validator_Configurable extends Chrome_Validator
{
    /**
     * The configurations
     *
     * @var \Chrome\Config\Config_Interface
     */
    protected $_config = null;

    public function __construct(\Chrome\Config\Config_Interface $config)
    {
        $this->_config = $config;
    }
}