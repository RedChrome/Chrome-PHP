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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [14.04.2013 20:15:08] --> $
 */

if(CHROME_PHP !== true)
   die();

/**
 * @package    CHROME-PHP
 * @subpackage Chrome.Design
 */
interface Chrome_Renderable_List_Interface extends Iterator
{
    public function addRenderable(Chrome_Renderable $obj);

    public function getRenderables();

    public function setRenderables(array $renderables);

    public function getRenderable($index);
}

/**
 * @package    CHROME-PHP
 * @subpackage Chrome.Design
 */
interface Chrome_Renderable
{
    /**
     * @return mixed
     */
    public function render();
}

/**
 * @package    CHROME-PHP
 * @subpackage Chrome.Design
 */
interface Chrome_Renderable_Composition_Interface extends Chrome_Renderable
{
    /**
     * @return Chrome_Renderable_Options_Interface
     */
    public function getRequiredRenderables();

    public function getRenderableList();

    public function setRenderableList(Chrome_Renderable_List_Interface $list);
}


/**
 * @package    CHROME-PHP
 * @subpackage Chrome.Design
 */
interface Chrome_Design_Interface extends Chrome_Renderable
{
    public function getApplicationContext();

    public function setRenderable(Chrome_Renderable $renderable);

    public function getRenderable();
}
/**
 * @package    CHROME-PHP
 * @subpackage Chrome.Design
 */
interface Chrome_Design_Loader_Interface
{
    public function addComposition(Chrome_Renderable_Composition_Interface $composition);

    public function getCompositions();

    public function load();
}

// todo: finish interface
interface Chrome_Renderable_Options_Interface {

}

/**
 * @package    CHROME-PHP
 * @subpackage Chrome.Design.Theme
 */
interface Chrome_Design_Theme_Interface
{
    public function initDesign(Chrome_Design_Interface $design);
}

// @todo remove those includes, place them anywhere else
require_once 'renderable/composition.php';
require_once 'renderable/template.php';
require_once 'design/default.php';
require_once 'factory.php';
require_once 'factory/design.php';
require_once 'factory/theme.php';


class Chrome_Renderable_List implements Chrome_Renderable_List_Interface
{
    protected $_list = array();

    protected $_position = 0;

    public function addRenderable(Chrome_Renderable $obj) {
        $this->_list[] = $obj;
    }

    public function getRenderables() {
        return $this->_list;
    }

    public function setRenderables(array $renderables) {

        $this->_list = array();

        foreach($renderables as $renderable) {
            if( !($renderable instanceof Chrome_Renderable)) {
                throw new Chrome_InvalidArgumentException('All renderables have to implement interface Chrome_Renderable! Renderable was '.get_class($renderable));
            }
            $this->_list[] = $renderable;
        }
    }

    public function getRenderable($index) {
        return isset($this->_list[$index]) ? $this->_list[$index] : null;
    }

    /*
     * Iterator interface methods
     */
    public function rewind() {
        $this->_position = 0;
    }

    public function current() {
        return $this->_list[$this->_position];
    }

    public function key() {
        return $this->_position;
    }

    public function next() {
        ++$this->_position;
    }

    public function valid() {
        return isset($this->_list[$this->_position]);
    }
}
