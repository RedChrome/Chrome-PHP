<?php

/**
 * CHROME-PHP CMS
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://chrome-php.de/license/new-bsd
 * If you did not receive a copy of the license AND are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@chrome-php.de so we can send you a copy immediately.
 *
 * @package    CHROME-PHP
 * @subpackage Chrome.Validator
 * @copyright  Copyright (c) 2008-2009 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://chrome-php.de/license/new-bsd		New BSD License
 * @version   $Id: 0.1 beta <!-- phpDesigner :: Timestamp [10.05.2010 22:24:58] --> $
 */

if(CHROME_PHP !== true)
    die();

/**
 * Chrome_Validator_Form_Length
 *
 * @author		Alexander Book
 * @package		CHROME-PHP
 * @subpackage  Chrome.Validator
 * @copyright   Copyright (c) 2008-2009 Chrome - PHP (http://www.chrome-php.de)
 */
class Chrome_Validator_Form_Length extends Chrome_Validator
{
    const CHROME_VALIDATOR_FORM_LENGTH_MAX = 'MAXLENGTH';
    const CHROME_VALIDATOR_FORM_LENGTH_MIN = 'MINLENGTH';
    
    protected $_options = array(self::CHROME_VALIDATOR_FORM_LENGTH_MAX => 1000,
                                self::CHROME_VALIDATOR_FORM_LENGTH_MIN => 0);
    
    public function __construct()
    {
    }

    public function setData($data)
    {
        $this->_data = $data;
    }

    protected function _validate()
    {
        $length = strlen($this->_data);
        
        if($length > $this->_options[self::CHROME_VALIDATOR_FORM_LENGTH_MAX]) {
            $this->_setError('Input too long');
        }
        
        if($length < $this->_options[self::CHROME_VALIDATOR_FORM_LENGTH_MIN]) {
            $this->_setError('Input too short');
        }

        return;
    }
}