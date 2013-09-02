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
 * @subpackage Chrome.Form
 * @copyright Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version $Id: 0.1 beta <!-- phpDesigner :: Timestamp [29.03.2013 17:12:17] --> $
 */
if(CHROME_PHP !== true)
    die();

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Form
 */
class Chrome_View_Form_Element_Form_Default extends Chrome_View_Form_Element_Abstract
{
    private $_int = 0;

    protected function _render()
    {
        if($this->_int == 0)
        {
            $this->_setFlags();
            $this->_setFlag('method', $this->_formElement->getForm()->getAttribute(Chrome_Form_Interface::ATTRIBUTE_METHOD));
            $this->_setFlag('action', $this->_formElement->getForm()->getAttribute(Chrome_Form_Interface::ATTRIBUTE_ACTION));
            $this->_setFlag('required', '');
            if(isset($this->_attribute['id'])) {
                $this->_setFlag('id', '');
            }

            $token = $this->_elementOption->getToken();
            $tokenNamespace = $this->_elementOption->getTokenNamespace();

            $this->_int = 1;

            return '<form '. $this->_renderFlags() . '>' . "\n" .
                 '<input type="hidden" id="' . $this->_getIdPrefix().$tokenNamespace . '" name="' . $tokenNamespace . '" value="' . $token . '" />';
        } else
        {
            --$this->_renderCount;
            $this->_int = 0;
            return '</form>';
        }
    }
}