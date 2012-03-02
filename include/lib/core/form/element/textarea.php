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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [02.03.2012 21:50:41] --> $
 * @author     Alexander Book
 */

if( CHROME_PHP !== true ) die();

/**
 * Chrome_Form_Element_Textarea
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Form
 */
class Chrome_Form_Element_Textarea extends Chrome_Form_Element_Abstract
{
	const CHROME_FORM_ELEMENT_TEXTAREA_SESSION_NAMESPACE = 'TEXTAREA';

	protected $_defaultOptions = array( self::CHROME_FORM_ELEMENT_READONLY => false, self::CHROME_FORM_ELEMENT_SAVE_DATA => false );

    protected $_isValid = null;

	public function isCreated()
	{
		return true;
	}

	public function isValid()
	{

        // cache
	    if($this->_isValid !== null) {
	       return $this->_isValid;
	    }

		$data = $this->_form->getSentData( $this->_id );

		$isValid = true;

		// if readonly is true, then data is null and the element is valid ;)
		if( $this->_options[self::CHROME_FORM_ELEMENT_READONLY] === true ) {
			return true;
		}

		foreach( $this->_validators as $validator ) {

			$validator->setData( $data );
			$validator->validate();

			if( !$validator->isValid() ) {
				$this->_errors += $validator->getAllErrors();
				$isValid = false;
			}
		}

        if($isValid === false) {
            $this->_unSave();
        }

        $this->_isValid = $isValid;

		return $isValid;
	}

	public function isSent()
	{
		if( $this->_options[self::CHROME_FORM_ELEMENT_READONLY] === true ) {
			return true;
		}

		if( $this->_options[self::CHROME_FORM_ELEMENT_IS_REQUIRED] === true ) {
			if( $this->_form->getSentData( $this->_id ) === null ) {
				$this->_errors[] = self::CHROME_FORM_ELEMENT_ERROR_NOT_SENT;
				return false;
			}
		}

		return true;
	}

	public function create()
	{
		return true;
	}

	public function getData()
	{
		if( $this->_data !== null ) {
			return $this->_data;
		}

		// if textarea is read only, then the user can't send anything
		if( $this->_options[self::CHROME_FORM_ELEMENT_READONLY] === true ) {
			return null;
		}

		$data = $this->_form->getSentData( $this->_id );

		foreach( $this->_converters as $converter ) {
			$data = Chrome_Converter::getInstance()->convert( $converter, $data );
		}

		$this->_data = $data;

		return $data;
	}

	public function getDecorator()
	{
		if( $this->_decorator === null ) {
			$this->_decorator = new Chrome_Form_Decorator_Textarea_Default( $this->_options[self::CHROME_FORM_ELEMENT_DECORATOR_OPTIONS],
				$this->_options[self::CHROME_FORM_ELEMENT_DECORATOR_ATTRIBUTES] );
			$this->_decorator->setFormElement( $this );
		}

		return $this->_decorator;
	}

	public function save()
	{
		if( $this->_options[self::CHROME_FORM_ELEMENT_SAVE_DATA] === false ) {
			return;
		}

		if( $this->_options[self::CHROME_FORM_ELEMENT_NOT_SAVE_NULL_DATA] === true ) {
			if( $this->getData() === null ) {
				return;
			}
		}

		$session = Chrome_Session::getInstance();

		$array = $session[self::CHROME_FORM_ELEMENT_SESSION_NAMESPACE];
		$array[$this->_form->getID()][self::CHROME_FORM_ELEMENT_TEXTAREA_SESSION_NAMESPACE][$this->getID()] = $this->_getData();
		$session[self::CHROME_FORM_ELEMENT_SESSION_NAMESPACE] = $array;
	}

    protected function _unSave()
    {
        $session = Chrome_Session::getInstance();

		$array = $session[self::CHROME_FORM_ELEMENT_SESSION_NAMESPACE];
		$array[$this->_form->getID()][self::CHROME_FORM_ELEMENT_TEXTAREA_SESSION_NAMESPACE][$this->getID()] = null;
    }

	public function getSavedData()
	{

		$session = Chrome_Session::getInstance();

		return ( isset( $session[self::CHROME_FORM_ELEMENT_SESSION_NAMESPACE][$this->_form->getID()][self::CHROME_FORM_ELEMENT_TEXTAREA_SESSION_NAMESPACE][$this->getID()] ) ) ?
			$session[self::CHROME_FORM_ELEMENT_SESSION_NAMESPACE][$this->_form->getID()][self::CHROME_FORM_ELEMENT_TEXTAREA_SESSION_NAMESPACE][$this->getID()] : null;
	}
}
