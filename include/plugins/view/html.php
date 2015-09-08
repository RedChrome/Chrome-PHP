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
 */

use \Chrome\Resource\Resource_Interface;

/**
 * @package CHROME-PHP
 * @subpackage Chrome.View
 *
 */
class Chrome_View_Plugin_HTML extends Chrome_View_Plugin_Abstract
{
    private $_title = '';
    private $_JS = array();
    private $_CSS = array();

    public function getTitle()
    {
        return $this->_title . $this->_getDefaultTitleEnding();
    }

    public function addTitle(\Chrome\View\View_Interface $obj, $title)
    {
        if($this->_title === '')
        {
            $this->_title = $this->_getDefaultTitleBeginning();
        }

        if(is_array($title))
        {
            $this->_title .= $this->_getDefaultTitleSeparator() . implode($this->_getDefaultTitleSeparator(), $title);
        } else
        {
            $this->_title .= $this->_getDefaultTitleSeparator() . $title;
        }
    }

    public function setTitle(\Chrome\View\View_Interface $obj, $title)
    {
        $this->_title = $title;
    }

    public function addJS(\Chrome\View\View_Interface $obj, Resource_Interface $resource)
    {
        $this->_JS[] = $this->_applicationContext->getDiContainer()->get('\Chrome\Linker\Linker_Interface')->get($resource);
    }

    public function setJS(\Chrome\View\View_Interface $obj, array $js)
    {
        $this->_JS = $js;
    }

    public function getJS(\Chrome\View\View_Interface $obj = null, $getAsHtmlString = true)
    {
        if($getAsHtmlString === true)
        {

            $return = '';

            foreach($this->_JS as $file)
            {
                $return .= '<script type="text/javascript" src="' . $file . '"></script>' . "\n";
            }

            return $return;
        }

        return $this->_JS;
    }

    public function addCSS(\Chrome\View\View_Interface $obj, Resource_Interface $resource)
    {
        $this->_CSS[] =  $this->_applicationContext->getDiContainer()->get('\Chrome\Linker\Linker_Interface')->get($resource);
    }

    public function setCSS(\Chrome\View\View_Interface $obj, array $css)
    {
        $this->_CSS = $css;
    }

    public function getCSS(\Chrome\View\View_Interface $obj = null, $getAsHtmlString = true)
    {
        if($getAsHtmlString !== true)
        {
            return $this->_CSS;
        }

        $return = '';
        foreach($this->_CSS as $css)
        {
            $return .= '<link rel="stylesheet" href="' . $css . '" type="text/css" />' . "\n";
        }
        return $return;
    }

    private function _getDefaultTitleBeginning()
    {
        return $this->_applicationContext->getConfig()->getConfig('Site', 'Title_Beginning');
    }

    private function _getDefaultTitleEnding()
    {
        return $this->_applicationContext->getConfig()->getConfig('Site', 'Title_Ending');
    }

    private function _getDefaultTitleSeparator()
    {
        return $this->_applicationContext->getConfig()->getConfig('Site', 'Title_Separator');
    }

    public function getMethods()
    {
        return array('getTitle', 'addTitle', 'setTitle', 'addJS', 'addCSS', 'setJS', 'setCSS', 'getJS', 'getCSS');
    }

    public function getClassName()
    {
        return 'Chrome_View_Helper_HTML';
    }
}