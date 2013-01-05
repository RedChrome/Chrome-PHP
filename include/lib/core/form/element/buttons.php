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
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@chrome-php.de so we can send you a copy immediately.
 *
 * @package    CHROME-PHP
 * @subpackage Chrome.Form
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [05.01.2013 16:27:41] --> $
 */

if( CHROME_PHP !== true ) die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Form
 */
class Chrome_Form_Element_Buttons extends Chrome_Form_Element_Abstract
{
	const CHROME_FORM_ELEMENT_BUTTONS = 'BUTTONS';

    const CHROME_FORM_ELEMENT_ERROR_NO_BUTTON_PRESSED = 'NOBUTTONPRESSED';

	protected $_defaultOptions = array( self::CHROME_FORM_ELEMENT_IS_REQUIRED => true, self::CHROME_FORM_ELEMENT_BUTTONS =>
			array() );

	protected $_data = null;

	protected function _isCreated()
	{
		foreach( $this->_options[self::CHROME_FORM_ELEMENT_BUTTONS] as $button ) {
			if( $button->isCreated() === false ) {
			    $this->_errors[] = $button->getErrors();
				return false;
			}
		}

		return true;
	}

	protected function _isValid()
	{
		foreach( $this->_options[self::CHROME_FORM_ELEMENT_BUTTONS] as $button ) {
			if( $button->isValid() === false ) {
			    $this->_errors[] = $button->getErrors();
				return false;
			}
		}

		return true;
	}

	protected function _isSent()
	{
		if( $this->_options[self::CHROME_FORM_ELEMENT_IS_REQUIRED] === false ) {
			foreach( $this->_options[self::CHROME_FORM_ELEMENT_BUTTONS] as $button ) {
                // call isSent, but we dont care about the result
				$button->isSent();
			}
            // if its not required, then it is always sent
            return true;
		}

		// only one buttons must have been sent!
		foreach( $this->_options[self::CHROME_FORM_ELEMENT_BUTTONS] as $button ) {
			if( $button->isSent() === true ) {
				return true;
			}
		}

        $this->_errors[] = self::CHROME_FORM_ELEMENT_ERROR_NO_BUTTON_PRESSED;

		return false;
	}

	public function create()
	{
		foreach( $this->_options[self::CHROME_FORM_ELEMENT_BUTTONS] as $button ) {
			$button->create();
		}
	}

	public function getData()
	{
		// we're using here no converter, because the data is already converted in the particular buttons

		$array = array();

		foreach( $this->_options[self::CHROME_FORM_ELEMENT_BUTTONS] as $button ) {
			$array[$button->getID()] = $button->getData();
		}

		return $array;
	}

	public function save()
	{
		foreach( $this->_options[self::CHROME_FORM_ELEMENT_BUTTONS] as $button ) {
			$button->save();
		}
	}
}
