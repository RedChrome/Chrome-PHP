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

use Chrome\Validator\AbstractValidator;

/**
 * A validator which uses a callback to validate.
 *
 * @package		CHROME-PHP
 * @subpackage  Chrome.Validator
 */
class CallbackValidator extends AbstractValidator
{
    protected $_callback = null;

    public function __construct($callable)
    {
        if(!is_callable($callable)) {
            throw new \Chrome\Exception('Argument #1 must be a valid callback!');
        }

        $this->_callback = $callable;
    }

    protected function _validate()
    {
        $this->_namespace = 'plugins/validate/form/element';

        $returnValue = false;

        try {

            if(is_array($this->_callback) ) {
                $returnValue = call_user_func($this->_callback, $this->_data);
            } else {
                // Note that $this->_callback($this->_data) will get interpreted
                // from php by calling the method _callback of this class.
                // Thats why, we use a local variable ...
                $callback = $this->_callback;
                $returnValue = $callback($this->_data);
            }

        } catch(\Chrome\Exception $e) {
            $this->_setError('exception_while_validating');
            return false;
        }

        if($returnValue === true) {
            return true;
        }

        $this->_setError($returnValue);

        return false;
    }
}