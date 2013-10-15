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

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.View.Form
 */
class Chrome_View_Form_Element_Backward_Default extends Chrome_View_Form_Element_Abstract
{
    protected function _render()
    {
        // @todo implement option: delte passwords via javascript on backward

        $lang = $this->_getTranslate();
        //$lang = new Chrome_Language(Chrome_Language::CHROME_LANGUAGE_DEFAULT_LANGUAGE);

        $this->_flags['value'] = $lang->get('backward');
        $this->_flags['required'] = null;

        $formId = $this->_viewForm->getViewElements($this->_formElement->getForm()->getID())->getId();#->getFlag('id');
        $this->_flags['onClick'] = 'javascript:truncate_form_input(\'' . $formId . '\');return true';

        return '<input type="submit" ' . $this->_renderFlags() . '/>';

        // f($this->_options[self::CHROME_FORM_DECORATOR_BACKWARD_DELETE_PASSWORDS] === true) {
        // $addOnclick = 'onclick="javascript:truncate_form_input(\''.$this->_formElement->getForm()->getID().'\');return true"';
        // else {
        // $addOnclick = '';
        //
    }
}