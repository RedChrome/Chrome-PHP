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

namespace Chrome\Renderable\Composition;

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Design
 */
abstract class AbstractComposition implements \Chrome\Renderable\Composition\Composition_Interface
{
    protected $_renderables = null;

    public function __construct()
    {
        $this->_renderables = new \Chrome\Renderable\RenderableList();
    }

    public function getRenderableList()
    {
        return $this->_renderables;
    }

    public function setRenderableList(\Chrome\Renderable\List_Interface $list)
    {
        $this->_renderables = $list;
    }

    public function render()
    {
        $return = '';

        foreach($this->_renderables as $renderable)
        {
            $return .= $renderable->render();
        }

        return $return;
    }
}

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Design
 */
class Composition extends \Chrome\Renderable\Composition\AbstractComposition
{
    protected $_option = null;

    public function setOption(\Chrome\Renderable\Option_Interface $option)
    {
        $this->_option = $option;
    }

    public function getRequiredRenderables()
    {
        return $this->_option;
    }
}

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Design
 */
class ArrayComposition extends Composition
{

    public function render()
    {
        $array = array();

        foreach($this->_renderables as $renderable)
        {
            $array = array_merge($array, (array) $renderable->render());
        }

        return $array;
    }
}


