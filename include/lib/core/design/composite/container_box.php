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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [20.07.2012 16:31:46] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true)
    die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Design
 */
class Chrome_Design_Composite_Container_Box extends Chrome_Design_Composite_Container
{
    protected $_views = array();

    protected $_start = '';
    protected $_end = '';

    public static function getInstance() {
        return new self();
    }

    public function __construct($start, $end) {

        $this->_start = $start;
        $this->_end = $end;

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

        $design = Chrome_Design::getInstance();

        foreach($this->_views AS $view) {
            $return .= $design->get($this->_start, $view).$view->render().$design->get($this->_end, $view);
        }

        return $return;
    }
}