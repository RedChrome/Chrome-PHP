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
 * @version   $Id: 0.1 beta <!-- phpDesigner :: Timestamp [21.07.2013 18:45:01] --> $
 */

if(CHROME_PHP !== true)
    die();

/**
 *
 * @package		CHROME-PHP
 * @subpackage  Chrome.Validator
 */
class Chrome_Validator_Composition_Or extends Chrome_Validator_Composition_Abstract
{
    public function __construct()
    {
    }

    public function validate()
    {
        foreach($this->_validators as $validator) {
            $validator->validate();
            if($validator->isValid()) {
                $this->_errorMsg = array();
                return true;
            } else {
                $this->_errorMsg = array_merge($this->_errorMsg, $validator->getAllErrors());
            }
        }

        return false;
    }

    protected function _validate()
    {
    }
}
