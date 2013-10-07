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
 * @version   $Id: 0.1 beta <!-- phpDesigner :: Timestamp [20.07.2013 16:05:36] --> $
 */

if(CHROME_PHP !== true)
    die();

/**
 * Chrome_Validator_Form_Element_Contains
 *
 * @package		CHROME-PHP
 * @subpackage  Chrome.Validator
 */
class Chrome_Validator_Form_Element_Contains extends Chrome_Validator
{
    protected $_allowedValues = null;

    public function __construct(array $allowedValues)
    {
        $this->_allowedValues = $allowedValues;
    }

    protected function _validate()
    {
        if($this->_data === null) {
            return true;
        }

        if(is_array($this->_data)) {

            foreach($this->_data as $sentValue) {
                if(!in_array($sentValue, $this->_allowedValues)) {
                    $this->_setError('input_not_allowed');
                    return false;
                }
            }

            return true;
        }

        if(!in_array($this->_data, $this->_allowedValues)) {
            $this->_setError('input_not_allowed');
            return false;
        }

        return true;
    }
}