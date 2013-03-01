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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [17.02.2013 15:55:23] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true)
    die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Design
 */
class Chrome_Design_Chrome_Columns_Three extends Chrome_Design_Composite_Abstract
{
    private static $_instance = null;

    protected $_columns = array();

    protected function __construct() {
        parent::__construct();

        Chrome_Design_Composite_Body::getInstance()->setComposite($this);
        $this->_composite = new Chrome_Design_Composite_Container();
    }

    public static function getInstance() {
        if(self::$_instance === null) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function setFirstColumn(Chrome_Design_Composite_Interface $column) {
        $this->_columns[0] = $column;
    }

    public function getFirstColumn() {
        return $this->_columns[0];
    }

    public function setSecondColumn(Chrome_Design_Composite_Interface $column) {
        $this->_columns[1] = $column;
    }

    public function getSecondColumn() {
        return $this->_columns[1];
    }

    public function setContent(Chrome_Design_Composite_Interface $content) {
        $this->_columns[2] = $content;
    }

    public function getContent() {
        return $this->_columns[2];
    }

    public function render(Chrome_Controller_Interface $controller) {

        $return = '
<div class="ym-column">
  <div class="ym-col1">
    <div class="ym-cbox">
      ';

      if(isset($this->_columns[0])) {
        $return .= $this->_columns[0]->render($controller);
      }
      $return .= '
    </div>
  </div>
  <div class="ym-col2">
    <div class="ym-cbox">
      ';

      if(isset($this->_columns[1])) {
        $return .= $this->_columns[1]->render($controller);
      }
      $return .= '
    </div>
  </div>
  <div class="ym-col3">
    <div class="ym-cbox">
      ';

      if(isset($this->_columns[2])) {
        $return .= $this->_columns[2]->render($controller);
      }

      $return .= '
    </div>
  </div>
</div>';
        return $return;
    }
}