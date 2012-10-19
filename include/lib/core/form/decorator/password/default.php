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
 * @version   $Id: 0.1 beta <!-- phpDesigner :: Timestamp [18.10.2012 11:38:20] --> $
 */
if( CHROME_PHP !== true ) die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Form
 */
class Chrome_Form_Decorator_Password_Default extends Chrome_Form_Decorator_Abstract
{
	public function render()
	{
		$return = '';

		if( $this->getOptions( Chrome_Form_Element_Abstract::CHROME_FORM_ELEMENT_IS_REQUIRED ) === true ) {
			$this->setAttribute( 'required', 'required' );
		}

		if( ( $label = $this->getOption( self::CHROME_FORM_DECORATOR_LABEL ) ) !== null ) {
			$return .= '<label for="' . $this->_formElement->getID() . '">' . $label . '</label>';
		}

		$return .= '<input type="password" name="' . $this->_formElement->getID() . '" ' . $this->_getPreparedAttrs() .
			'/>';
		return $return;
	}
}
