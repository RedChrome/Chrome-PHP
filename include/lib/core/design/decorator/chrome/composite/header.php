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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [31.03.2010 12:55:46] --> $
 */

if(CHROME_PHP !== true)
    die();

class Chrome_Design_Decorator_Chrome_Header extends Chrome_Design_Decorator_Abstract
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