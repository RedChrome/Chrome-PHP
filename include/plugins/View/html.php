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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [03.03.2013 18:22:36] --> $
 */

if(CHROME_PHP !== true) die();

class Chrome_View_Plugin_HTML extends Chrome_View_Plugin_Abstract
{
    private $_title = '';

    private $_JS = array();

    private $_CSS = array();

    public function getTitle()
    {
        return $this->_title . $this->_getDefaultTitleEnding();
    }

    public function addTitle(Chrome_View_Interface $obj, $title)
    {
        if($this->_title === '') {
            $this->_title = $this->_getDefaultTitleBeginning();
        }

        if(is_array($title)) {
            $this->_title .= $this->_getDefaultTitleSeparator() . implode($this->_getDefaultTitleSeparator(), $title);
        } else {
            $this->_title .= $this->_getDefaultTitleSeparator() . $title;
        }
    }

    public function setTitle(Chrome_View_Interface $obj, $title)
    {
        $this->_title = $title;
    }

    public function addJS(Chrome_View_Interface $obj, $filename = null, $directory = null)
    {
        if($filename === null and $directory === null) {
            if(isset($obj->JS) and is_array($obj->JS)) {
                $this->_JS = array_merge($obj->JS, $this->_JS);
                return;
            } else {
                throw new Chrome_Exception('Cannot add a .js file! The "JS" property of the view class must be an array!');
            }
        } else {
            if($filename === null) {
                throw new Chrome_Exception('Either filename AND directory is not set, OR filename must be set!');
            }

            $this->_JS[] = _PUBLIC . $directory . $filename;
        }
    }

    public function setJS(Chrome_View_Interface $obj, array $js = null)
    {
        if($js === null) {

            if(isset($obj->JS) and is_array($obj->JS)) {
                $this->_JS = $obj->JS;
            } else {
                throw new Chrome_Exception('Cannot add multiple .js files if property "JS" of the view class is not an array!');
            }
            return;
        }

        $this->_JS = $js;
    }

    public function getJS(Chrome_View_Interface $obj = null, $getAsString = true)
    {
        if($getAsString === true) {

            $return = '';

            foreach($this->_JS as $file) {
                $return .= '<script type="text/javascript" src="' . $file . '"></script>' . "\n";
            }

            return $return;
        }

        return $this->_JS;
    }

    public function addCSS(Chrome_View_Interface $obj, $filename = null, $directory = null)
    {
        if($filename === null and $directory === null) {
            if(isset($obj->CSS) and is_array($obj->CSS)) {
                $this->_CSS = array_merge($obj->CSS, $this->_CSS);
            } else {
                throw new Chrome_Exception('Cannot add an .css file if property "CSS" does not exist OR it is not an array in a view class!');
            }
            return;
        }

        if($filename === null) {
            throw new Chrome_Exception('No filename for a .css file given!');
        }

        $this->_CSS[] = _PUBLIC . $directory . $filename;
    }

    public function setCSS(Chrome_View_Interface $obj, array $css = null)
    {
        if($css === null) {
            if(isset($obj->CSS) and is_array($obj->CSS)) {
                $this->_CSS = $obj->CSS;
                return;
            }

            throw new Chrome_Exception('Cannot set .css files if property "CSS" does not exist OR it is not an array in a view class!');
        }

        $this->_CSS = $css;
    }

    public function getCSS(Chrome_View_Interface $obj = null, $getAsString = true)
    {
        if($getAsString !== true) {
            return $this->_CSS;
        }

        $return = '';
        foreach($this->_CSS as $css) {
            $return .= '<link rel="stylesheet" href="' . $css . '" type="text/css" />' . "\n";
        }
        return $return;
    }

    private function _getDefaultTitleBeginning()
    {
        return Chrome_Config::getConfig('Site', 'Title_Beginning');
    }

    private function _getDefaultTitleEnding()
    {
        return Chrome_Config::getConfig('Site', 'Title_Ending');
    }

    private function _getDefaultTitleSeparator()
    {
        return Chrome_Config::getConfig('Site', 'Title_Separator');
    }

    public function getMethods()
    {
        return array(
            'getTitle',
            'addTitle',
            'setTitle',
            'addJS',
            'addCSS',
            'setJS',
            'setCSS',
            'getJS',
            'getCSS');
    }

    public function getClassName()
    {
        return 'Chrome_View_Helper_HTML';
    }
}