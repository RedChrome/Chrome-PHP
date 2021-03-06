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
 * @subpackage Chrome.View.Form
 */

namespace Chrome\View\Form\Element\Form;

/**
 * Class responsible for rendering form start/end
 *
 * @package CHROME-PHP
 * @subpackage Chrome.View.Form
 */
class Html extends \Chrome\View\Form\Element\AbstractBasicElement
{
    private $_int = 0;

    protected function _init()
    {
        parent::_init();
        $this->_elementOption = $this->_formElement->getOption();
    }

    protected function _render()
    {
        if($this->_int == 0)
        {
            $this->_attribute->setAttribute('method', $this->_formElement->getForm()->getAttribute(\Chrome\Form\Form_Interface::ATTRIBUTE_METHOD));
            $this->_attribute->setAttribute('action', $this->_formElement->getForm()->getAttribute(\Chrome\Form\Form_Interface::ATTRIBUTE_ACTION));
            $this->_attribute->setAttribute('required', '');

            $token = $this->_elementOption->getToken();
            $tokenNamespace = $this->_elementOption->getTokenNamespace();

            $this->_int = 1;

            return '<form '. $this->_renderFlags() . '>' . "\n" .
                 '<input type="hidden" id="' . $tokenNamespace . '" name="' . $tokenNamespace . '" value="' . $token . '" />';
        } else
        {
            $this->_int = 0;
            return '</form>';
        }
    }
}