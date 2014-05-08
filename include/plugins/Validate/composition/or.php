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

namespace Chrome\Validator\Composition;

use \Chrome\Validator\AbstractComposition;

/**
 * A composition of multiple validators combined with OR/ ||.
 * Equivalent:
 * ($validator1->isValid() OR $val2->isValid() OR ...)
 *
 * This means: If only one validator return true, then this will also return true.
 * All errors from previous validators are forgotten if one validator returns true.
 *
 * If all validators return false, so does this class.
 *
 * @package		CHROME-PHP
 * @subpackage  Chrome.Validator
 */
class OrComposition extends AbstractComposition
{
    public function __construct()
    {
    }

    protected function _validate()
    {
        foreach($this->_validators as $validator) {

            if($this->_validateWith($validator) === true) {
                // forget all previous errors.
                $this->_errors = array();
                return true;
            }
        }

        return false;
    }
}
