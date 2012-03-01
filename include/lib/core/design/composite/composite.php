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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [14.08.2011 14:04:00] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true)
    die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Design
 */
interface Chrome_Design_Composite_Interface extends Chrome_Design_Renderable
{
    public static function getInstance();

    public function add(Chrome_Design_Renderable $obj, $position = null);

    public function get();

    public function remove($index);

    public function set(array $composites);

    public function getType();
}

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Design
 */
abstract class Chrome_Design_Composite_Abstract implements Chrome_Design_Composite_Interface
{
    protected $_composites = array();

    final private function __clone()
    {
    }

    private function __construct()
    {
    }

    public function add(Chrome_Design_Renderable $obj, $position = null)
    {
        if($position === null) {
            $this->_composites[] = $obj;


        } else {

        // if we want to add an obj between others

            $position = (int) $position - 1;
            $array = array();
            $inserted = false;
            // we go through every object,
            // then compare if the position is already set
            // if its set then we add the $obj first
            foreach($this->_composites AS $key =>  $composite) {
                if($key >= $position AND $inserted === false) {
                    $array[] = $obj;
                    $inserted = true;
                }

                $array[] = $composite;
            }

            $this->_composites = $array;

            if(!isset($this->_composites[$position])) {
                $this->_composites[$position] = $obj;
            }
        }
    }

    public function get()
    {
        return $this->_composites;
    }

    public function remove($index)
    {
        if(!isset($this->_composites[$index])) {
            return;
        }

        unset($this->_composites[$index]);
        // indexes them numerically
        $this->_composites = array_values($this->_composites);
    }

    public function set(array $composites)
    {

        $this->_composites = array();

        foreach($composites AS $obj) {
            if(!($obj instanceof Chrome_Design_Renderable)) {
                throw new Chrome_Exception('Cannot set '.var_export($obj, true).' into the composites! It is not an instance of Chrome_Design_Renderable in Chrome_Design_Composite_Abstract::set()!');
            }

            $this->_composites[] = $obj;
        }
    }

}

require_once 'body.php';
require_once 'content.php';
require_once 'footer.php';
require_once 'head.php';
require_once 'header.php';
require_once 'html.php';
require_once 'left_box.php';
require_once 'right_box.php';