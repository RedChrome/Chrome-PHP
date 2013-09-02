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
 */
if(CHROME_PHP !== true)
    die();

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Form
 */
class Chrome_Form_Element_Date extends Chrome_Form_Element_Abstract implements Chrome_Form_Element_Storable
{
    protected $_date = null;

    public function isCreated()
    {
        return true;
    }

    public function getData()
    {
        return $this->_date;
    }

    protected function _getValidator()
    {
        $andValidator = new Chrome_Validator_Composition_And();
        $andValidator->addValidator(new Chrome_Validator_Form_Element_Inline(array($this, 'inlineValidation')));

        $this->_addUserValidator($andValidator);

        return $andValidator;
    }

    protected function _getDataToValidate()
    {
        try
        {
            $this->_date = DateTime::createFromFormat('!Y-m-d', $this->_form->getSentData($this->_id));
        } catch(Exception $e)
        {
            $this->_date = null;
        }

        return $this->_date;
    }

    public function inlineValidation($data)
    {
        if($data instanceof DateTime) {
            return true;
        }

        return 'ERRORCREATINGDATE';
    }

    public function getStorableData()
    {
        if($this->_date !== null) {
            return $this->_date->format('Y-m-d');
        }

        return null;

    }
}
