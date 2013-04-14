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
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [03.04.2013 13:39:12] --> $
 * @author     Alexander Book
 */
if(CHROME_PHP !== true)
    die();

/**
 * Interface for attributes
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Form.Option
 */
interface Chrome_Form_Attribute_Interface
{
    public function setAttribute($key, $value);

    public function getAttribute($key);

    public function getAttributes();

    public function hasAttribute($key);

    public function renderAttributes();
}

/**
 * @todo sollen wirklich im form default werte für alle elements hinterlegt werden?
 */
interface Chrome_Form_Option_Interface
{

}

/**
 * Interface for an element option class. This contains the settings for a form element
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Form.Option
 */
interface Chrome_Form_Option_Element_Interface
{
    public function setIsRequired($boolean);

    public function getIsRequired();

    public function setIsReadonly($boolean);

    public function getIsReadonly();

    public function setDoSaveData($saveData, $saveEmptyData = false);

    public function getDoSaveData();

    public function getDoSaveEmptyData();

    public function setAllowedValues(array $allowedValues);

    public function getAllowedValues();

    public function setDecoratorOption(Chrome_Form_Options_Decorator_Interface $decOptions);

    public function getDecoratorOption();

    public function addValidator(Chrome_Validator_Interface $validator);

    public function setValidator(Chrome_Validator_Interface $validator);

    public function getValidator();

    public function getValidators();
}

/**
 * This contains the settings for a form decorator
 *
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Form.Option
 */
interface Chrome_Form_Option_Decorator_Interface
{



}