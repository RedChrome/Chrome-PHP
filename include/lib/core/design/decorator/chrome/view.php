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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [04.05.2010 20:38:04] --> $
 */

if(CHROME_PHP !== true)
    die();

/**
 * Chrome_Design_Decorator_Chrome_View_Left_Box
 * 
 * @package   
 * @author CHROME-PHP
 * @copyright Alexander Book
 * @version 2010
 * @access public
 */
class Chrome_Design_Decorator_Chrome_View_Left_Box extends Chrome_Design_Decorator_Abstract
{            
    public function render(Chrome_Controller_Interface $controller) {
        
        if($this->_decorate === null) {
            throw new Chrome_Exception('No decorateable object set in Chrome_Design_Decorator_Content::render()!');
        }
        
        return '
<div class="Navi">
 <div>
  <div>
   <div>
    <div>
     <h3 class="title">'.$this->_decorate->getViewTitle().'</h3>	
     '.$this->_decorate->render().'
    </div>
   </div>
  </div>
 </div>
</div>
';
    }
}

/**
 * Chrome_Design_Decorator_Chrome_View_Right_Box
 * 
 * @package   
 * @author CHROME-PHP
 * @copyright Alexander Book
 * @version 2010
 * @access public
 */
class Chrome_Design_Decorator_Chrome_View_Right_Box extends Chrome_Design_Decorator_Abstract
{            
    public function render(Chrome_Controller_Interface $controller) {
        
        if($this->_decorate === null) {
            throw new Chrome_Exception('No decorateable object set in Chrome_Design_Decorator_Content::render()!');
        }
        
        return '
<div class="Navi">
 <div>
  <div>
   <div>
    <div>
     <h3 class="title">'.$this->_decorate->getViewTitle().'</h3>	
     '.$this->_decorate->render().'
    </div>
   </div>
  </div>
 </div>
</div>
';
    }
}

/**
 * Chrome_Design_Decorator_Chrome_View_Footer
 * 
 * @package   
 * @author CHROME-PHP
 * @copyright Alexander Book
 * @version 2010
 * @access public
 */
class Chrome_Design_Decorator_Chrome_View_Footer extends Chrome_Design_Decorator_Abstract
{
    public function render(Chrome_Controller_Interface $controller) {
        
        if($this->_decorate === null) {
            throw new Chrome_Exception('No decorateable object set in Chrome_Design_Decorator_Content::render()!');
        }
        
        return $this->_decorate->render().'<br>';
    }
}

/**
 * Chrome_Design_Decorator_Chrome_View_Header
 * 
 * @package   
 * @author CHROME-PHP
 * @copyright Alexander Book
 * @version 2010
 * @access public
 */
class Chrome_Design_Decorator_Chrome_View_Header extends Chrome_Design_Decorator_Abstract
{
    public function render(Chrome_Controller_Interface $controller) {
        
        if($this->_decorate === null) {
            throw new Chrome_Exception('No decorateable object set in Chrome_Design_Decorator_Content::render()!');
        }
        
        return $this->_decorate->render();
    }
}