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

namespace Chrome\Validator\Form\Element;

use Chrome\Validator\AbstractValidator;

/**
 * Validator which skips other validator processes if the form element is marked as read-only.
 *
 * Since readonly input filed are not send to the server, we dont need to validate an empty input.
 *
 * @package		CHROME-PHP
 * @subpackage  Chrome.Validator
 */
class ReadonlyValidator extends AbstractValidator
{
    public function __construct(\Chrome_Form_Option_Element_Interface $option)
    {
        $this->_options = $option;
    }

    protected function _validate()
    {
        if($this->_options->getIsReadonly() === true) {
            return true;
        }

        // this specific validator must not set errors.
        return false;
    }
}