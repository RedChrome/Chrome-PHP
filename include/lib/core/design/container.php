<?php

/**
 * CHROME-PHP CMS
 *
 * PHP version 5
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
 * @category   CHROME-PHP
 * @package    CHROME-PHP
 * @subpackage Chrome.Design
 * @author     Alexander Book <alexander.book@gmx.de>
 * @copyright  2012 Chrome - PHP <alexander.book@gmx.de>
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [01.03.2013 17:45:12] --> $
 * @link       http://chrome-php.de
 */

if(CHROME_PHP !== true)
    die();

interface Chrome_Design_Renderable_Container_Interface
{
    public function setRenderable(Chrome_Design_Renderable $obj);

    public function getRenderable();

    public function setPriority($int);

    public function getPriority();

    public function setPosition($pos);

    public function getPosition();

    public function getID();

    public function shallMap();

    public function setShallMap($boolean);
}

interface Chrome_Design_Renderable_Container_List_Interface extends Iterator
{
    public function addContainer(Chrome_Design_Renderable_Container_Interface $container);

    public function add(Chrome_Design_Renderable $obj);

    public function removeAll();
}

class Chrome_Design_Renderable_Container implements Chrome_Design_Renderable_Container_Interface
{
    protected $_renderable = null;

    protected $_priority   = null;

    protected $_position   = null;

    protected $_id         = null;

    protected $_shallMap   = true;

    public function __construct(Chrome_Design_Renderable $obj, $position = null, $priority = null, $id = null) {
        $this->setRenderable($obj);
        $this->setPosition($position);
        $this->setPriority($priority);

        if($id !== null) {
            $this->_id = (string) $id;
        } else {
            $this->_id = get_class($obj);
        }
    }

    public function setRenderable(Chrome_Design_Renderable $obj) {
        $this->_renderable = $obj;
    }

    public function getRenderable() {
        return $this->_renderable;
    }

    public function setPriority($int) {
        ($int !== null) ? $this->_priority = (int) $int : $this->_priority = null;
    }

    public function getPriority() {
        return $this->_priority;
    }

    public function setPosition($pos) {
        ($pos !== null) ? $this->_position = (string) $pos : $this->_position = null;
    }

    public function getPosition() {
        return $this->_position;
    }

    public function getID() {
        return $this->_id;
    }

    public function shallMap() {
        return $this->_shallMap;
    }

    public function setShallMap($boolean) {
        $this->_shallMap = (bool) $boolean;
    }
}

class Chrome_Design_Renderable_Container_List implements Chrome_Design_Renderable_Container_List_Interface
{
    protected $_containers = array();

    protected $_pPosition  = 0;

    public function addContainer(Chrome_Design_Renderable_Container_Interface $container) {
        $this->_containers[] = $container;
    }

    public function add(Chrome_Design_Renderable $obj) {
        $this->_containers[] = new Chrome_Design_Renderable_Container($obj);
    }

    public function removeAll() {
        $this->_containers = array();
    }

    /*
     * Iterator interface methods
     */
    public function rewind() {
        $this->position = 0;
    }

    public function current() {
        return $this->_containers[$this->_pPosition];
    }

    public function key() {
        return $this->_pPosition;
    }

    public function next() {
        ++$this->_pPosition;
    }

    public function valid() {
        return isset($this->_containers[$this->_pPosition]);
    }
}