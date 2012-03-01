<?php
/**
 * CHROME-PHP CMS
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://chrome-php.de/license/new-bsd
 * If you did not receive a copy of the license AND are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@chrome-php.de so we can send you a copy immediately.
 *
 * @package    CHROME-PHP
 * @copyright  Copyright (c) 2008-2009 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://chrome-php.de/license/new-bsd        New BSD License
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [04.04.2010 18:53:56] --> $
 */

if(CHROME_PHP !== true)
    die();

class Chrome_Design_Decorator_Chrome_Composite_Content extends Chrome_Design_Decorator_Abstract
{            
    public function render() {
        
        if($this->_decorate === null) {
            throw new Chrome_Exception('No decorateable object set in Chrome_Design_Decorator_Content::render()!');
        }
        
        return '
<!-- CONTENT -->        
<div id="Content">
'.$this->_decorate->render().'
</div>
<!-- CONTENT -->  ';
    }
}

class Chrome_Design_Decorator_Chrome_Composite_Footer extends Chrome_Design_Decorator_Abstract
{            
    public function render() {
        
        if($this->_decorate === null) {
            throw new Chrome_Exception('No decorateable object set in Chrome_Design_Decorator_Content::render()!');
        }
        
        return '
<!-- FOOTER -->
<div id="Footer">
'.$this->_decorate->render().'
</div>
<!-- FOOTER -->';
    }
}

class Chrome_Design_Decorator_Chrome_Composite_HTML extends Chrome_Design_Decorator_Abstract
{            
    public function render() {
        
        if($this->_decorate === null) {
            throw new Chrome_Exception('No decorateable object set in Chrome_Design_Decorator_Content::render()!');
        }
        
        return '<!DOCTYPE html PUBLIC \'-//W3C//DTD XHTML 1.0 Transitional//EN\' \'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\'>
<!-- Document created by chrome-php -->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de">
'.$this->_decorate->render().'
</html>';
    }
}

class Chrome_Design_Decorator_Chrome_Composite_Right_Box extends Chrome_Design_Decorator_Abstract
{            
    public function render() {
        
        if($this->_decorate === null) {
            throw new Chrome_Exception('No decorateable object set in Chrome_Design_Decorator_Content::render()!');
        }
        
        return '
<!-- RIGHT_BOX -->
<div id="rNavi">
     '.$this->_decorate->render().'
</div>
<!-- RIGHT_BOX -->';
    }
}

class Chrome_Design_Decorator_Chrome_Composite_Header extends Chrome_Design_Decorator_Abstract
{            
    public function render() {
        
        if($this->_decorate === null) {
            throw new Chrome_Exception('No decorateable object set in Chrome_Design_Decorator_Content::render()!');
        }
        
        return '
<!-- HEADER -->
<div id="Header">
'.$this->_decorate->render().'
</div>
<!-- HEADER -->';
    }
}


class Chrome_Design_Decorator_Chrome_Composite_Body extends Chrome_Design_Decorator_Abstract
{            
    public function render() {
        
        if($this->_decorate === null) {
            throw new Chrome_Exception('No decorateable object set in Chrome_Design_Decorator_Content::render()!');
        }
        
        return '<body class="body">
<div id="Site">
'.$this->_decorate->render().'
</div>
</body>';
    }
}

class Chrome_Design_Decorator_Chrome_Composite_Left_Box extends Chrome_Design_Decorator_Abstract
{            
    public function render() {
        
        if($this->_decorate === null) {
            throw new Chrome_Exception('No decorateable object set in Chrome_Design_Decorator_Content::render()!');
        }
        
        return '
<!-- LEFT_BOX -->
<div id="lNavi">
     '.$this->_decorate->render().'
</div>
<!-- LEFT_BOX -->';
    }
}