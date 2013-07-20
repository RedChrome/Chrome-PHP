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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [19.07.2013 13:39:41] --> $
 * @author     Alexander Book
 */

if( CHROME_PHP !== true ) die();

require_once 'text.php';

/**
 * Chrome_Form_Element_Textarea
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Form
 */
class Chrome_Form_Element_Textarea extends Chrome_Form_Element_Text
{
	/*
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

		$array = $this->_session[self::SESSION_NAMESPACE];
		$array[$this->_form->getID()][self::CHROME_FORM_ELEMENT_TEXTAREA_SESSION_NAMESPACE][$this->getID()] = $this->_getData();
		$this->_session[self::SESSION_NAMESPACE] = $array;
	}


	public function getSavedData()
	{
		return ( isset( $this->_session[self::SESSION_NAMESPACE][$this->_form->getID()][self::CHROME_FORM_ELEMENT_TEXTAREA_SESSION_NAMESPACE][$this->getID()] ) ) ?
			$this->_session[self::SESSION_NAMESPACE][$this->_form->getID()][self::CHROME_FORM_ELEMENT_TEXTAREA_SESSION_NAMESPACE][$this->getID()] : null;
	}*/
}
