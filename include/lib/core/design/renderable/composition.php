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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [28.03.2013 12:49:59] --> $
 */

if(CHROME_PHP !== true) die();

/**
 * @package    CHROME-PHP
 * @subpackage Chrome.Design
 */
abstract class Chrome_Renderable_Composition implements Chrome_Renderable_Composition_Interface
{
	protected $_renderables = null;

	public function __construct()
	{
		$this->_renderables = new Chrome_Renderable_List();
	}

	public function getRenderableList()
	{
		return $this->_renderables;
	}

	public function setRenderableList(Chrome_Renderable_List_Interface $list)
	{
		$this->_renderables = $list;
	}

	public function render()
	{
		$return = '';

		foreach($this->_renderables as $renderable) {
			$return .= $renderable->render();
		}

		return $return;
	}
}

class Chrome_Renderable_Composition_Impl extends Chrome_Renderable_Composition
{
	protected $_option = null;

	public function setOption(Chrome_Renderable_Options_Interface $option)
	{
		$this->_option = $option;
	}

	public function getRequiredRenderables()
	{
		return $this->_option;
	}
}

class Chrome_Renderable_Composition_Array_Impl extends Chrome_Renderable_Composition_Impl
{
    public function render() {
        $array = array();

        foreach($this->_renderables as $renderable) {
            $array = array_merge($array, (array) $renderable->render());
		}

        return $array;
    }
}

