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

namespace Chrome\Validator\Form\Element;

use Chrome\Validator\AbstractValidator;

/**
 * a validator which validates all attachments of a form element. If only one attachment is valid, then
 * the whole element is valid
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Validator
 */
class AttachmentValidator extends AbstractValidator
{
    protected $_option = null;

    public function __construct(\Chrome_Form_Option_Element_Attachable_Interface $option)
    {
        $this->_option = $option;
    }

    protected function _validate()
    {
        foreach($this->_option->getAttachments() as $attachment)
        {
            if($attachment->isValid())
            {
                return true;
            }
        }

        return false;
    }
}
