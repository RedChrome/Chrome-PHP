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
 * @subpackage Chrome.View.Form
 */

namespace Chrome\View\Form\Element\Submit;

/**
 * @package CHROME-PHP
 * @subpackage Chrome.View.Form
 */
class Html extends \Chrome\View\Form\Element\AbstractElement
{
    protected function _render()
    {
        $allowedValues = $this->_elementOption->getAllowedValue();

        $this->_attribute->setAttribute('value', $allowedValues);
        $this->_attribute->remove('required');

        return '<input type="submit" '.$this->_renderFlags().' formnovalidate/>';
    }
}