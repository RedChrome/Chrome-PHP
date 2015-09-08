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
use Chrome\Validator\Composition_Interface;
use Chrome\Validator\Form\Element\ElementValidator;

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

    protected $_composition = null;

    public function __construct(\Chrome\Form\Option\AttachableElement_Interface $option, Composition_Interface $composition)
    {
        $this->_option = $option;
        $this->_composition = $composition;
    }

    protected function _validate()
    {
        foreach($this->_option->getAttachments() as $attachment)
        {
            $this->_composition->addValidator(new ElementValidator($attachment));
        }

        $this->_composition->setData($this->_data);
        $this->_composition->validate();

        return $this->_composition->isValid();
    }
}
