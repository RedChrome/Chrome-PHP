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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [30.03.2010 20:36:12] --> $
 */

if(CHROME_PHP !== true)
    die();

class Chrome_Design_Decorator_Chrome_HTML extends Chrome_Design_Decorator_Abstract
{            
    public function render(Chrome_Controller_Interface $controller) {
        
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