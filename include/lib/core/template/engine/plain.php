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
 * @subpackage Chrome.Template.Engine
 * @copyright  Copyright (c) 2008-2009 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://chrome-php.de/license/new-bsd		New BSD License
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [10.08.2011 14:46:46] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true)
    die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Template.Engine
 */ 
class Chrome_Template_Engine_Plain extends Chrome_Template_Engine_Abstract
{
    public function __construct(Chrome_Template_Abstract $obj)
    {
        $this->_templateInstance = $obj;
    }
    
    public function render()
    {
        
        // here we need to set vars, so that php knows the content of the tmpl-vars!!
        foreach($this->_var AS $key => $value) {
            $$key = $value;
        }
                
        ob_start();
        
        include($this->_file);
        
        $return = ob_get_contents();
        
        ob_end_clean();
        
        // all assigned vars get destroyed automatically
        
        return $return;       
    }
}