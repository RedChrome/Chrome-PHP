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
 * @version   $Id: 0.1 beta <!-- phpDesigner :: Timestamp [23.10.2012 22:48:33] --> $
 */
if(CHROME_PHP !== true)
    die();

require_once 'default.php';

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Form
 */
class Chrome_Form_Decorator_Text_Yaml extends Chrome_Form_Decorator_Text_Default
{
    public function render() {

        if($this->_formElement->getOptions(Chrome_Form_Element_Abstract::CHROME_FORM_ELEMENT_IS_REQUIRED) === true AND ($label = $this->getOption(self::CHROME_FORM_DECORATOR_LABEL)) !== null) {
            $this->setOption(self::CHROME_FORM_DECORATOR_LABEL, $label.'<sup class="ym-required" title="This field is mandatory">*</sup>');
        }

        return '<div class="ym-fbox-text">'.parent::render().'</div>';
    }
}