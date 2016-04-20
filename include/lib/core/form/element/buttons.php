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
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@chrome-php.de so we can send you a copy immediately.
 *
 * @package    CHROME-PHP
 * @subpackage Chrome.Form
 */

namespace Chrome\Form\Option\Element;

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Form
 */
class Buttons extends \Chrome\Form\Option\Element implements \Chrome\Form\Option\AttachableElement_Interface
{
    protected $_buttons = array();

    public function attach(\Chrome\Form\Element\BasicElement_Interface $element)
    {
        $this->_buttons[] = $element;

        return $this;
    }

    public function getAttachments()
    {
        return $this->_buttons;
    }

    public function setAttachments(array $elements)
    {
        $this->_buttons = array();

        foreach($elements as $element) {
            if(!($element instanceof \Chrome\Form\Element\BasicElement_Interface)) {
                throw new \Chrome\Exception('All elements in array have to be instances of \Chrome\Form\Element\BasicElement_Interface');
            }

            $this->_buttons[] = $element;
        }

        return $this;
    }
}

namespace Chrome\Form\Element;

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Form
 */
class Buttons extends \Chrome\Form\Element\AbstractElement implements \Chrome\Form\Element\Interfaces\Buttons
{
    const CHROME_FORM_ELEMENT_ERROR_NO_BUTTON_PRESSED = 'NOBUTTONPRESSED';

    public function __construct(\Chrome\Form\Form_Interface $form, $id, \Chrome\Form\Option\Element\Buttons $option)
    {
        parent::__construct($form, $id, $option);
    }

    protected function _isCreated()
    {
        foreach($this->_option->getAttachments() as $button) {
            if($button->isCreated() === false) {
                $this->_errors[] = $button->getErrors();
                return false;
            }
        }

        return true;
    }

    protected function _isSent()
    {
        if($this->_option->getIsRequired() === false OR $this->_option->getIsReadonly() === true) {
            foreach($this->_option->getAttachments() as $button) {
                // call isSent, but we dont care about the result
                $button->isSent();
            }
            // if its not required, then it is always sent
            return true;
        }

        // only one buttons must have been sent!
        foreach($this->_option->getAttachments() as $button) {
            if($button->isSent() === true) {
                return true;
            }
        }

        $this->_errors[] = self::CHROME_FORM_ELEMENT_ERROR_NO_BUTTON_PRESSED;

        return false;
    }

    public function isValid()
    {
        // is this okay?
        return true;
    }

    public function create()
    {
        foreach($this->_option->getAttachments() as $button) {
            $button->create();
        }

        return true;
    }

    public function getData()
    {
        // we're using here no converter, because the data is already converted in the particular sub-buttons

        $array = array();

        foreach($this->_option->getAttachments() as $button) {
            $array[$button->getID()] = $button->getData();
        }

        return $array;
    }
}
