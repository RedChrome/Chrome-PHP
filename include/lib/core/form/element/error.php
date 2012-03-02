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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [02.03.2012 15:14:18] --> $
 */

if(CHROME_PHP !== true)
    die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Form
 */
class Chrome_Form_Element_Error extends Chrome_Form_Element_Abstract
{

    //protected $_defaultOptions = array(self::CHROME_FORM_ELEMENT_IS_REQUIRED => false);

    public function isCreated() {
        return true;
    }

    public function isValid() {
        return true;
    }

    public function isSent() {
        return true;
    }

    public function create() {
        return true;
    }

    public function getData()
    {
        return null;
    }

    public function getDecorator() {
        if($this->_decorator === null) {
            $this->_decorator = new Chrome_Form_Decorator_Error_Default($this->_options[self::CHROME_FORM_ELEMENT_DECORATOR_OPTIONS], $this->_options[self::CHROME_FORM_ELEMENT_DECORATOR_ATTRIBUTES]);
            $this->_decorator->setFormElement($this);
        }

        return $this->_decorator;
    }

    public function save() {

    }
}