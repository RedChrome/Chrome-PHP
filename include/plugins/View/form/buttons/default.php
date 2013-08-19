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
 * @version   $Id: 0.1 beta <!-- phpDesigner :: Timestamp [22.03.2013 15:52:52] --> $
 */
if( CHROME_PHP !== true ) die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Form
 */
class Chrome_View_Form_Element_Buttons_Default extends Chrome_View_Form_Element_Attachable_Abstract
{
    /*public function setOption(Chrome_View_Form_Element_Option_Interface $option)
    {
        $viewOptions = $option->getAttachments();

        foreach($this->_elementOption->getAttachments() as $key => $viewElement)
        {
            // dem viewForm die option injezieren
            #$viewElement->set


        }



    }*/

    public function render()
    {
        $return = '';

        foreach( $this->_option->getAttachments() as $button ) {
            $return .= $button->render();
        }

        return $return;
    }

    /*
	public function element( $name, array $options = array())
	{
		$buttons = $this->_formElement->getOptions( Chrome_Form_Element_Buttons::CHROME_FORM_ELEMENT_BUTTONS );

		foreach( $buttons as $key => $button ) {
			if( $key === $name or $button->getID() === $name ) {
				return $button->getDecorator()->render();
			}
		}

	}

	public function renderAll()
	{
		$return = '';

		$buttons = $this->_formElement->getOptions( Chrome_Form_Element_Buttons::CHROME_FORM_ELEMENT_BUTTONS );

		foreach( $buttons as $key => $button ) {

			$return .= $button->getDecorator()->render();

		}

		return $return;
	}*/

}