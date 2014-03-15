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
 * @subpackage Chrome.Form
 */

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Form
 */
class Chrome_Form_Element_Radio extends Chrome_Form_Element_Multiple_Abstract implements Chrome_Form_Element_Storable, \Chrome\Form\Element\Interfaces\Radio
{
    public function isCreated()
    {
        return true;
    }

    protected function _getValidator()
    {
        // this is important, because the radio can only accept ONE input!
        $this->_option->setSelectMultiple(false);

        return parent::_getValidator();
    }

    public function getStorableData()
    {
        return $this->getData();
    }
}