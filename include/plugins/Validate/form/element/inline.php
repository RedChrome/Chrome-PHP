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
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version   $Id: 0.1 beta <!-- phpDesigner :: Timestamp [23.07.2013 13:21:27] --> $
 */

if(CHROME_PHP !== true)
    die();

/**
 * Chrome_Validator_Form_Element_Inline
 *
 * @package		CHROME-PHP
 * @subpackage  Chrome.Validator
 */
class Chrome_Validator_Form_Element_Inline extends Chrome_Validator
{
    protected $_callback = null;

    public function __construct($callable)
    {
        if(!is_callable($callable)) {
            throw new Chrome_Exception('Argument #1 must be a valid callback!');
        }

        $this->_callback = $callable;
    }

    protected function _validate()
    {
        $returnValue = call_user_func($this->_callback, $this->_data);

        if($returnValue === true) {
            return true;
        }

        $this->_setError($returnValue);

        return false;
    }
}