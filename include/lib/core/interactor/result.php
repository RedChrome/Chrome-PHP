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
 * @subpackage Chrome.Hash
 */
namespace Chrome\Interactor;

interface Interactor_Interface
{

}

interface State_Interface
{
    public function failed();

    public function succeeded();

    public function hasFailed();

    public function hasSucceeded();
}

interface Error_Interface
{
    public function setErrors($key, array $errors);

    public function setError($key, $error);

    public function hasError($key);

    public function hasErrors();

    /**
     *
     * @param string $key
     * @return \Iterator
     */
    public function getError($key);

    /**
     * Returns an array of iterators
     *
     * @return \Iterator
     */
    public function getErrors();
}

interface Result_Interface extends State_Interface, Error_Interface
{
    /**
     * Resets everything
     */
    public function reset();
}

class Result implements Result_Interface
{
    protected $_state = null;

    protected $_errors = array();

    public function failed()
    {
        $this->_state = false;
    }

    public function succeeded()
    {
        $this->_state = true;
    }

    public function hasFailed()
    {
        return ($this->_state === false);
    }

    public function hasSucceeded()
    {
        return ($this->_state === true);
    }

    public function setError($key, $error)
    {
        $this->_errors[$key][] = $error;
    }

    public function hasError($key)
    {
        return (isset($this->_errors[$key]));
    }

    public function hasErrors()
    {
        return count($this->_errors) > 0;
    }

    public function setErrors($key, array $errors)
    {
        if(count($errors) === 0) {
            $this->_errors[$key] = true;
        }

        foreach($errors as $error)
        {
            $this->_errors[$key][] = $error;
        }
    }

    /**
     *
     * @param string $key
     * @return \Iterator
     */
    public function getError($key)
    {
        $errors = array();

        if($this->hasError($key)) {
            $errors = $this->_errors[$key];
        }

        return new \ArrayIterator($errors);
    }

    /**
     * @return \Iterator
     */
    public function getErrors()
    {
        $array = array();

        foreach($this->_errors as $key => $errors)
        {
            $array[$key] = new \ArrayIterator($errors);
        }

        return new \ArrayIterator($array);
    }

    public function reset()
    {
        $this->_state = null;
        $this->_errors = array();
    }
}

class ExceptionResult extends Result
{
    protected $_exception = null;

    public function __construct(\Chrome\Exception $e) {
        $this->_exception = $e;
        $this->failed();
    }

    /**
     * @return \Chrome\Exception
     */
    public function getException()
    {
        return $this->_exception;
    }
}

class FailedResult extends Result
{
    public function __construct($key, array $errors)
    {
        $this->failed();
        $this->setErrors($key, $errors);
    }
}

class SucceededResult extends Result
{
    public function __construct()
    {
        $this->succeeded();
    }
}

