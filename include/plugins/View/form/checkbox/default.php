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
 * @subpackage Chrome.Form
 * @copyright Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version $Id: 0.1 beta <!-- phpDesigner :: Timestamp [22.03.2013 16:08:16] --> $
 */
if(CHROME_PHP !== true)
    die();

/**
 * TODO: change attribute class if errors exists
 * @package CHROME-PHP
 * @subpackage Chrome.Form
 */
class Chrome_View_Form_Element_Checkbox_Default extends Chrome_View_Form_Element_Multiple_Abstract
{
    private $_int = 0;

    protected function getNext()
    {
        $next = $this->_availableSelections[$this->_int];
        $this->_int = ++$this->_int % count($this->_availableSelections);
        return $next;
    }

    protected function _render()
    {
        $this->_tempFlag['value'] = 'anything';

        $return = '<input type="checkbox" ' . $this->_renderFlags() . '/>';
        return $return;
    }
}
