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
 * A composition of multiple validators combined via AND/&&
 *
 * What it actually does:
 * $validator1->isValid() AND $val2->isValid() AND ...
 *
 * If only one validator returns false, so this class does. All subsequent validators are not processed
 * (like php's AND does)
 *
 * If all validators return true, then this class returns true and forgets all error messages.
 *
 *
 * @package		CHROME-PHP
 * @subpackage  Chrome.Validator
 */
class AndComposition extends AbstractComposition
{
    public function __construct()
    {
    }

    protected function _validate()
    {
        foreach($this->_validators as $validator) {

            if($this->_validateWith($validator) === false) {
                return false;
            }
        }

        // forget all errors
        $this->_errors = array();

        return true;
    }
}
