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
        $lang = $this->_getTranslate();
        //$lang = new Chrome_Language(Chrome_Language::CHROME_LANGUAGE_DEFAULT_LANGUAGE);

        $this->_attribute->setAttribute('value', $lang->get('backward'));
        $this->_attribute->remove('required');

        // The attribute "formnovalidate" is crucial!
        // if the user clicks on the backward button, he may not have filled out the whole form
        // so we need to disable the html5 validation. This is done by this simple attribute
        return '<input type="submit" ' . $this->_renderFlags() . ' formnovalidate/>';
    }
}