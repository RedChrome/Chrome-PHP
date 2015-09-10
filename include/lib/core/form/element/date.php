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
namespace Chrome\Form\Element;

use \Chrome\Validator\Composition\AndComposition;
use \Chrome\Validator\Form\Element\CallbackValidator;

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Form
 */
class Date extends \Chrome\Form\Element\AbstractElement implements \Chrome\Form\Element\Storable_Interface, \Chrome\Form\Element\Interfaces\Date
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
        $andValidator = new AndComposition();
        $andValidator->addValidator(new CallbackValidator(array($this, 'inlineValidation')));

        $this->_addUserValidator($andValidator);

        return $andValidator;
    }

    protected function _getDataToValidate()
    {
        try
        {
            $this->_date = \DateTime::createFromFormat('!Y-m-d', (string) $this->_form->getSentData($this->_id));
        } catch(\Exception $e)
        {
            $this->_date = null;
        }

        return $this->_date;
    }

    public function inlineValidation($data)
    {
        if($data instanceof \DateTime) {
            return true;
        }

        $this->_date = null;

        return 'date_was_not_valid';
    }

    public function getStorableData()
    {
        if($this->_date !== null) {
            return $this->_date->format('Y-m-d');
        }

        return null;
    }
}
