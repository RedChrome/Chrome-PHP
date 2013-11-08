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
 * @version   $Id: 0.1 beta <!-- phpDesigner :: Timestamp [21.07.2013 18:46:09] --> $
 */

if(CHROME_PHP !== true)
    die();

/**
 * Chrome_Validator_Form_Element_SentReadonly
 *
 * @package		CHROME-PHP
 * @subpackage  Chrome.Validator
 */
class Chrome_Validator_Form_Element_SentReadonly extends Chrome_Validator
{
    protected $_option = null;

    public function __construct(Chrome_Form_Option_Element_Basic_Interface $option)
    {
        $this->_option = $option;
    }

    protected function _validate()
    {
        if($this->_option instanceof Chrome_Form_Element_Interface)
        {
            if($this->_option->getIsReadonly() === true) {
                if($this->_data !== null) {
                    $this->_setError('readonly_field_was_sent');
                    return false;
                }
            }

            return true;
        }

        if($this->_option instanceof Chrome_Form_Option_Element_Multiple_Interface) {

            foreach($this->_option->getReadonly() as $readonlyInput) {
                if(in_array($readonlyInput, (array) $this->_data)) {
                    $this->_setError('readonly_field_was_sent');
                    return false;
                }
            }
            return true;
        }

        return true;
    }
}