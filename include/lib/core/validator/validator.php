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

namespace Chrome\Validator;

use Chrome\Localization\Message;

/**
 * Interface for validator classes
 *
 * The validation logic should be inside validate().
 *
 * A Validator can get autoloaded if the class name has the following structure:
 *
 * Chrome\Validator\Any\Sub\Element\MyClassNameValidator -> file: include/plugins/Validate/any/sub/element/myclassname.php
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Validator
 */
interface Validator_Interface
{
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
     * @return \Chrome\Localization\Message_Interface
     */
    public function getError();

    /**
     * Returns all errors.
     *
     * Returns an array of \Chrome\Localization\Message_Interface
     *
     * @return array
     */
    public function getAllErrors();
}

/**
 * Interface for validator classes which can append other validators
 *
 * A Validator composition can also get autoloaded if it has the structure
 *
 * Chrome\Validator\Any\Sub\Element\MyClassNameComposition -> file: include/plugins/Validate/any/sub/element/myclassname.php
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Validator
 */
interface Composition_Interface extends Validator_Interface
{
    /**
     * Adds a validator
     *
     * @param Validator_Interface $validator validator to add
     * @return void
     */
    public function addValidator(Validator_Interface $validator);

    /**
     * adds validators given in array (as values, key gets ignored) in the given order
     *
     * Throws an \Chrome\InvalidArgumentException if a value of the array does not contain a appropriate class
     *
     * @param array $validators an array containing as values classes which implements Validator_Interface
     * @return void
     */
    public function addValidators(array $validators);

    /**
     * Unsets the current validators and adds validators given in array (as values, key gets ignored) in the given order
     *
     * Throws an \Chrome\InvalidArgumentException if a value of the array does not contain a appropriate class
     *
     * @param array $validators an array containing as values classes which implements Validator_Interface
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
     * @return Validator_Interface or null
     */
    public function getValidator($index);
}

/**
 * AbstractValidator
 *
 * Example:
 * <code>
 * namespace Chrome\Validator;
 *
 * class TestValidator extends AbstractValidator {
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
 * $v['e1'] = new TestValidator($_POST['email']);
 * $v['e2'] = new TestValidator($_POST['email']);
 * // add here another Validator e.g. UserValidator
 *
 * foreach($v AS $validator) {
 * 	if(!$validator->isValid()) {
 * 		while( ($error = $validator->getError()) !== null)
 * 			$errors[] = $error;
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
abstract class AbstractValidator implements Validator_Interface
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
    protected $_errors = array();

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
     * The namespace for a error message, see {@link \Chrome\Localization\Message_Interface}
     *
     * @var string
     */
    protected $_namespace = '';

    /**
     * Indicating, whether namespaces are getting updated if merging error messages
     *
     * @var boolean
     */
    protected $_updateNamespace = true;

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
            $this->_isValid = (count($this->_errors) === 0);
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

    public function isValidData($data)
    {
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
        return array_pop($this->_errors);
    }

    /**
     * Gets all error messages
     *
     * @return array
     */
    public function getAllErrors()
    {
        $return = $this->_errors;
        $this->_errors = array();
        return $return;
    }

    /**
     * Adds a error message
     *
     * @param string $msg error message
     */
    protected function _setError($msg, array $params = array(), $namespace = null)
    {
        if($namespace === null) {
            $namespace = $this->_namespace;
        }

        $this->_errors[] = new Message($msg, $params, $namespace);
    }

    /**
     * Validates a given validator, merges validations errors and returns it's validation state
     *
     * Note: No options are getting set
     *
     * @param Validator_Interface $validator
     * @return boolean
     */
    protected function _validateWith(Validator_Interface $validator)
    {
        $validator->validate();

        if(!$validator->isValid()) {
            $this->_errors = $this->_mergeErrors($this->_errors, $validator->getAllErrors());
            return false;
        }

        return true;
    }

    /**
     * Validates a given validator, merges validations errors and returns it's validation state using $data
     * which is used as validation input
     *
     * Note: No options are getting set
     *
     * @param Validator_Interface $validator
     * @param mixed $data
     * @return boolean
     */
    protected function _validateWithUsingData(Validator_Interface $validator, $data)
    {
        if(!$validator->isValidData($data)) {
            $this->_errors = $this->_mergeErrors($this->_errors, $validator->getAllErrors());
            return false;
        }

        return true;
    }

    /**
     * Merges all $toBeMerged messages into $initialErrors and updating namespace of $toBeMerged
     *
     * @param array $initialErrors
     * @param array $toBeMerged
     * @return array
     */
    private function _mergeErrors(array $initialErrors, array $toBeMerged)
    {
        if($this->_updateNamespace === true) {
            foreach($toBeMerged as $merge)
            {
                $merge->setNamespace($this->_namespace);
                $initialErrors[] = $merge;
            }
        } else {
            $initialErrors = array_merge($initialErrors, $toBeMerged);
        }

        return $initialErrors;
    }

    /**
     * Sets the namespace for all subsequent set error messages.
     *
     * It is highly recommended to use this method, since otherwise
     * all errors will polute the global error namespace and the
     * translation of the error messages will probably not work.
     *
     * @param string $namespace the error message namespace
     */
    protected function _setNamespace($namespace)
    {
        $this->_namespace = $namespace;
    }
}

abstract class AbstractComposition extends AbstractValidator implements Composition_Interface
{
    protected $_validators = array();

    public function addValidator(Validator_Interface $validator)
    {
        if($validator === $this) {
            throw new \Chrome\InvalidArgumentException('Tried to add this object to itself, causing circle dependencies.');
        }

        $this->_validators[] = $validator;
    }

    public function addValidators(array $validators)
    {
        foreach($validators as $validator) {
            $this->addValidator($validator);
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

namespace Chrome\Validator\Composer;

use \Chrome\Validator\AbstractValidator;

/**
 * Use this validator if you want to compose a complex set of validators together
 *
 * The composing logic should get put in _getValidator
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Validator
 */
abstract class AbstractComposer extends AbstractValidator
{
    /**
     * Composes multiple validators to one complex validator
     *
     * @return \Chrome\Validator\Validator_Interface
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

namespace Chrome\Validator\Configurable;

use \Chrome\Validator\AbstractValidator;
use \Chrome\Config\Config_Interface;

/**
 * An abstract validator, which needs a configuration
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Validator
 */
abstract class AbstractConfigurable extends AbstractValidator
{
    /**
     * The configurations
     *
     * @var Config_Interface
     */
    protected $_config = null;

    public function __construct(Config_Interface $config)
    {
        $this->_config = $config;
    }
}