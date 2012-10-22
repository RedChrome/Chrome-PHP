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
 * @version   $Id: 0.1 beta <!-- phpDesigner :: Timestamp [20.10.2012 19:48:57] --> $
 */
if( CHROME_PHP !== true ) die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Form
 */
class Chrome_Form_Decorator_Checkbox_Yaml extends Chrome_Form_Decorator_Checkbox_Default
{
	private $_index = 0;

	public function render()
	{
		$return = '';

		$values = $this->_formElement->getOptions( Chrome_Form_Element_Checkbox::CHROME_FORM_ELEMENT_SELECTION_OPTIONS );
		if( $this->_index === 0 ) {
			$return .= '<div class="ym-fbox-check">';
		}

		$return .= '' . parent::render() . '';

		++$this->_index;

		if( $this->_index === sizeof( $values ) ) {

			$return .= '</div>';
			$this->_index = 0;
		}

		return $return;
	}
}
