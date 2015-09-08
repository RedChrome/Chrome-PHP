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

use \Chrome\Validator\AbstractValidator;

/**
 * This validates a form element by calling isValid on this object
 *
 * @package		CHROME-PHP
 * @subpackage  Chrome.Validator
 */
class ElementValidator extends AbstractValidator
{
    protected $_formElement = null;

    public function __construct(\Chrome\Form\Element\BasicElement_Interface $formElement)
    {
        $this->_formElement = $formElement;
    }

    protected function _validate()
    {
        return $this->_formElement->isValid();
    }
}