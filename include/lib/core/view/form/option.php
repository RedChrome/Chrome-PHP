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
 * @subpackage Chrome.View.Form.Option
 */

namespace Chrome\View\Form\Option;

/**
 * Default implementation of Element_Interface
 *
 * @package CHROME-PHP
 * @subpackage Chrome.View.Form.Option
 */
class Element implements Element_Interface
{
    protected $_internalAttribute = array();
    protected $_storedData = null;
    protected $_label = null;
    protected $_placeholder = null;
    protected $_defaultInput = array();

    public function setInternalAttribute($key, $value)
    {
        $this->_internalAttribute[$key] = $value;
    }

    public function getInternalAttribute($key)
    {
        return (isset($this->_internalAttribute[$key])) ? $this->_internalAttribute[$key] : null;
    }

    public function getPlaceholder()
    {
        return $this->_placeholder;
    }

    public function getStoredData()
    {
        return $this->_storedData;
    }

    public function setStoredData($storedData)
    {
        $this->_storedData = $storedData;
    }

    public function getLabel()
    {
        return $this->_label;
    }

    public function setLabel(\Chrome\View\Form\Option\Label_Interface $labelObject)
    {
        $this->_label = $labelObject;
        return $this;
    }

    public function setPlaceholder($placeholder)
    {
        $this->_placeholder = $placeholder;
        return $this;
    }

    public function getDefaultInput()
    {
        return $this->_defaultInput;
    }

    public function setDefaultInput($input)
    {
        $this->_defaultInput = (array) $input;
    }
}

/**
 * Default implementation of MultipleElement_Interface
 *
 * @package CHROME-PHP
 * @subpackage Chrome.View.Form.Option
 */
class MultipleElement extends Element implements MultipleElement_Interface
{
}

/**
 * Default implementation of AttachableElement_Interface
 *
 * @package CHROME-PHP
 * @subpackage Chrome.View.Form.Option
 */
class AttachableElement extends Element implements AttachableElement_Interface
{
    protected $_attachments = array();

    public function attach(\Chrome\View\Form\Element\Element_Interface $element)
    {
        $this->_attachments[] = $element;
    }

    public function getAttachments()
    {
        return $this->_attachments;
    }

    public function setAttachments(array $elements)
    {
        foreach($elements as $element)
        {
            $this->attach($element);
        }
    }
}