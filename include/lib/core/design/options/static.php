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
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@chrome-php.de so we can send you a copy immediately.
 *
 * @package    CHROME-PHP
 * @subpackage Chrome.Design
 * @copyright  Copyright (c) 2008-2009 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://chrome-php.de/license/new-bsd        New BSD License
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [27.03.2013 19:03:50] --> $
 */

if(CHROME_PHP !== true) die();

/**
 * @package    CHROME-PHP
 * @subpackage Chrome.Design
 */
class Chrome_Renderable_Options_Static implements Chrome_Renderable_Options_Interface
{
    protected $_position = null;

    public function setPosition($pos)
    {
        $this->_position = $pos;
    }

    public function getPosition()
    {
        return $this->_position;
    }
}
