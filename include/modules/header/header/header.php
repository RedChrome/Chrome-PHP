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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [27.11.2010 14:23:33] --> $
 */

if(CHROME_PHP !== true)
    die();

class Chrome_View_Header_Header extends Chrome_View_Abstract
{
    public function render() {
        return '<h1>Chrome-PHP Preview</h1>';
    }
}

class Chrome_Controller_Header_Header extends Chrome_Controller_Header_Abstract
{
    public function __construct()
    {
        Chrome_Design_Composite_Header::getInstance()->getComposite()->addView(new Chrome_View_Header_Header($this));
    }
}

new Chrome_Controller_Header_Header();