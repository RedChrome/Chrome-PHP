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
 * @subpackage Chrome.Design
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [01.03.2013 16:01:53] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true)
    die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Design
 */
class Chrome_Design_Chrome_Composite_Content extends Chrome_Design_Composite_Abstract
{
    private static $_instance = null;

    protected function __construct() {
        parent::__construct();

        Chrome_Design_Chrome_Columns_Three::getInstance()->setContent($this);
        $this->_preComposite = new Chrome_Design_Composite_Container();
        $this->_composite = new Chrome_Design_Composite_Container();
        $this->_postComposite = new Chrome_Design_Composite_Container();
    }

    public static function getInstance() {
        if(self::$_instance === null) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }
}