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
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://chrome-php.de/license/new-bsd        New BSD License
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [20.03.2013 15:09:02] --> $
 */

if(CHROME_PHP !== true)
    die();

class Chrome_View_Plugin_Decorator extends Chrome_View_Plugin_Abstract
{
    public function setViewTitle(Chrome_View_Interface $obj, $title) {

        $obj->setVar('view_title', $title);
    }

    public function getViewTitle(Chrome_View_Interface $obj) {

        $title = $obj->getVar('view_title');

        if($title === null) {
            return 'No Title set';
        }

        return $title;
    }

    public function addStyle(Chrome_View_Interface $obj, $style) {

        Chrome_Design::getInstance()->getStyle()->addStyle($style);
    }

    public function setStyle(Chrome_View_Interface $obj, $style) {
        $styleObj = Chrome_Design::getInstance()->getStyle();
        $styleObj->removeAllStyles();
        $styleObj->addStyle($style);
    }

    public function setAjaxEnvironment(Chrome_View_Interface $obj) {
        $this->addStyle($obj, 'ajax');
    }

    public function getMethods()
    {
        return array('setViewTitle', 'getViewTitle', 'setAjaxEnvironment', 'setStyle', 'addStyle');
    }

    public function getClassName()
    {
        return 'Chrome_View_Helper_Decorator';
    }
}