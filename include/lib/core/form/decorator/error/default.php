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
 * @version   $Id: 0.1 beta <!-- phpDesigner :: Timestamp [21.03.2013 13:07:44] --> $
 */
if(CHROME_PHP !== true)
    die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Form
 */
class Chrome_Form_Decorator_Error_Default extends Chrome_Form_Decorator_Abstract
{
    const CHROME_FORM_DECORATOR_ERROR_EXCLUDE_ELEMENTS = 'EXCLUDEELEMENTS';
    const CHROME_FORM_DECORATOR_ERROR_LANGUAGE_OBJ = 'LANGOBJ';
    const CHROME_FORM_DECORATOR_ERROR_DISPLAY_ALL = 'DISPLAYALL';

    protected $_defaultOptions = array(self::CHROME_FORM_DECORATOR_ERROR_EXCLUDE_ELEMENTS => array(),
                                       self::CHROME_FORM_DECORATOR_ERROR_LANGUAGE_OBJ => null,
                                       self::CHROME_FORM_DECORATOR_ERROR_DISPLAY_ALL => false);

    public function render()
    {
        $return = '<div class="wrongInput" align="left"><ul>';

        if($this->_options[self::CHROME_FORM_DECORATOR_ERROR_DISPLAY_ALL] === true) {
            $errors = $this->_formElement->getForm()->getErrors();
        } else {
            $errors = array_merge($this->_formElement->getForm()->getValidationErrors());
        }

        $lang = $this->_options[self::CHROME_FORM_DECORATOR_ERROR_LANGUAGE_OBJ];

        foreach($errors as $element => $array) {

            if(in_array($element,(array) $this->_options[self::CHROME_FORM_DECORATOR_ERROR_EXCLUDE_ELEMENTS])) {
                continue;
            }

            $return .= '<li>'.$element;

            if(is_array($array)) {
                $return .= '<ul>';
                foreach($array as $error) {
                    if($lang === null) {
                        $return .= '<li>'.$error.'</li>';
                    } else {
                        $return .= '<li>'.$lang->get($error).'</li>';
                    }
                }
                $return .= '</ul>';
            }

            $return .= '</li>';

        }

        $return .= '</ul></div>';

        return $return;

    }
}
