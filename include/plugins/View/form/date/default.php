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
 * @package CHROME-PHP
 * @subpackage Chrome.View
 * @copyright Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version Git: <git_id>
 * @author Alexander Book
 */
if(CHROME_PHP !== true)
    die();
class Chrome_View_Form_Element_Date_Default extends Chrome_View_Form_Element_Abstract
{
    protected function _setFlags()
    {
        parent::_setFlags();

        if(!isset($this->_flags['value']))
        {
            return;
        }

        $inputValue = $this->_flags['value'];

        if($inputValue instanceof DateTime)
        {
            $this->_flags['value'] = $inputValue->format('Y-m-d');
        }
    }

    protected function _render()
    {
        return '<input type="date" ' . $this->_renderFlags() . '/>';
    }
}