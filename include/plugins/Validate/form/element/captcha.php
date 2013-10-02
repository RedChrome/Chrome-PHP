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
 * Chrome_Validator_Form_Element_Captcha
 *
 * @package		CHROME-PHP
 * @subpackage  Chrome.Validator
 */
class Chrome_Validator_Form_Element_Captcha extends Chrome_Validator
{
    protected $_captcha = null;

    public function __construct(Chrome_Captcha_Interface $captchaObject)
    {
        $this->_captcha = $captchaObject;
    }

    protected function _validate()
    {
        $isValid = (boolean) $this->_captcha->isValid($this->_data);

        if($isValid !== true)
        {
            // the captcha was invalid, so we create a new captcha for the client
            $this->_captcha->create();
            $this->_setError('Captcha not valid');
        }

        return $isValid;
    }
}