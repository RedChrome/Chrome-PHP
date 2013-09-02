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

if(CHROME_PHP !== true)
    die();

/**
 * @todo use superclass checkbox and allow only one input value
 * @package CHROME-PHP
 * @subpackage Chrome.Form
 */
class Chrome_Form_Element_Radio extends Chrome_Form_Element_Abstract implements Chrome_Form_Element_Storable
{
    public function __construct(Chrome_Form_Interface $form, $id, Chrome_Form_Option_Element_Multiple_Interface $option)
    {
        parent::__construct($form, $id, $option);
    }

    public function isCreated()
    {
        return true;
    }

    protected function _getValidator()
    {
        $or = new Chrome_Validator_Composition_Or();

        $and = new Chrome_Validator_Composition_And();

        $or->addValidator(new Chrome_Validator_Form_Element_Readonly($this->_option));
        $or->addValidator($and);

        $and->addValidator(new Chrome_Validator_Form_Element_SentReadonly($this->_option));
        $and->addValidator(new Chrome_Validator_Form_Element_Required($this->_option));
        $and->addValidator(new Chrome_Validator_Form_Element_Contains($this->_option->getAllowedValues()));

        if(($validator = $this->_option->getValidator()) !== null) {
            $and->addValidator($validator);
        }

        $this->_addUserValidator($and);

        return $or;
    }

    public function getStorableData()
    {
        return $this->getData();
    }
}