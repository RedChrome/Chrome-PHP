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
 * @subpackage Chrome.Design
 * @copyright  Copyright (c) 2008-2009 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://chrome-php.de/license/new-bsd		New BSD License
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [14.08.2011 14:03:01] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true)
    die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Design
 */
class Chrome_Design_Composite_Container extends Chrome_Design_Composite_Abstract
{
    protected $_views = array();

    public static function getInstance() {
        return new self();
    }

    public function __construct() {
        parent::__construct();
    }

    public function addView(Chrome_Design_Renderable $obj) {
        $this->_views[] = $obj;
    }

    public function setView(array $views) {
        $this->_views = $views;
    }

    public function getViews() {
        return $this->_views;
    }

    public function render() {

        $return = '';

        foreach($this->_views AS $view) {
            $return .= "\n".$view->render()."\n";
        }

        return $return;
    }
}