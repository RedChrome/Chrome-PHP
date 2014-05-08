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

namespace Chrome\Validator\General;

use \Chrome\Validator\AbstractValidator;

/**
 * Checks whether the first input equals the second input.
 *
 * You can set the first input by calling setData() and
 * the second input by calling setData() again.
 *
 * Or use the method setFirstData(), setSecondData();
 *
 * @package		CHROME-PHP
 * @subpackage  Chrome.Validator
 */
class EqualsValidator extends AbstractValidator
{
    protected $_firstData = null;

    protected $_secondData = null;

    protected $_isValidateable = false;

    public function setData($data)
    {
        if($this->_firstData === null) {
            $this->setFirstData($data);
        } else {
            $this->setSecondData($data);
        }
    }

    public function setFirstData($firstData)
    {
        $this->_firstData = $firstData;
        $this->_isValidateable = $this->_isValidateable | 1;
    }

    public function setSecondData($secondData)
    {
        $this->_secondData = $secondData;
        $this->_isValidateable = $this->_isValidateable | 2;
    }

    protected function _validate()
    {
        $this->_namespace = 'plugins/validate/general';

        // not all required data is set, thus we're assuming that the values are not the same
        if( ($this->_isValidateable & 3) !== 3) {
            return false;
        }

        if($this->_firstData === $this->_secondData) {
            return true;
        } else {
            $this->_setError('inputs_are_not_equal');
            return false;
        }
    }
}