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
 * @package CHROME-PHP
 * @subpackage Chrome.Design
 */


namespace Chrome;

/**
 * Interface for all renaderable objects. Those objects are supposed to be responsible for displaying.
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Design
 */
interface Renderable
{
    /**
     * Renders the object
     *
     * @return mixed
     */
    public function render();
}



namespace Chrome\Renderable;


// todo: finish interface
interface Option_Interface
{
}

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Design
 */
interface List_Interface extends \Iterator
{
    public function addRenderable(\Chrome\Renderable $obj);

    public function getRenderables();

    public function setRenderables(array $renderables);

    public function getRenderable($index);

    public function isLast();

    public function isFirst();

    public function count();
}


class RenderableList implements \Chrome\Renderable\List_Interface
{
    protected $_list = array();

    protected $_position = 0;

    public function addRenderable(\Chrome\Renderable $obj)
    {
        $this->_list[] = $obj;
    }

    public function getRenderables()
    {
        return $this->_list;
    }

    public function setRenderables(array $renderables)
    {
        $this->_list = array();

        foreach($renderables as $renderable)
        {
            if(!($renderable instanceof \Chrome\Renderable))
            {
                throw new \Chrome\InvalidArgumentException('All renderables have to implement interface \Chrome\Renderable! Renderable was ' . get_class($renderable));
            }
            $this->_list[] = $renderable;
        }
    }

    public function getRenderable($index)
    {
        return isset($this->_list[$index]) ? $this->_list[$index] : null;
    }

    public function isLast()
    {
        return $this->_position + 1 === $this->count();
    }

    public function isFirst()
    {
        return $this->_position === 0;
    }

    public function count()
    {
        return count($this->_list);
    }

    /*
     * Iterator interface methods
    */
    public function rewind()
    {
        $this->_position = 0;
    }

    public function current()
    {
        return $this->_list[$this->_position];
    }

    public function key()
    {
        return $this->_position;
    }

    public function next()
    {
        ++$this->_position;
    }

    public function valid()
    {
        return isset($this->_list[$this->_position]);
    }
}


namespace Chrome\Renderable\Composition;

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Design
 */
interface Composition_Interface extends \Chrome\Renderable
{
    /**
     *
     * @return \Chrome\Renderable\Option_Interface
     */
    public function getRequiredRenderables();

    public function getRenderableList();

    public function setRenderableList(\Chrome\Renderable\List_Interface $list);
}
