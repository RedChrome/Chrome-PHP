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
 * @subpackage Chrome.Validator
 */
if(CHROME_PHP !== true)
    die();

/**
 * Chrome_Validator_Form_Element_Birthday
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Validator
 */
class Chrome_Validator_Form_Element_Birthday extends Chrome_Validator
{

    public function __construct($years = 5)
    {
        $this->_years = new DateInterval('P' . $years . 'Y');
    }

    // @todo: $this->_data is a string: yyyy-mm-dd. it would be better if its an object of DateTime!
    protected function _validate()
    {
        $currentDate = new DateTime();

        $maxDate = $currentDate->sub($this->_years);

        if($this->_data >= $maxDate)
        {
            $this->_setError('Date not in the required intervall!');
            return false;
        }
        return true;
    }
}