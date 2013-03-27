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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [27.03.2013 15:44:14] --> $
 */

if(CHROME_PHP !== true) die();

/**
 * @package    CHROME-PHP
 * @subpackage Chrome.Design
 */
class Chrome_Design implements Chrome_Design_Interface
{
	protected $_applicationContext = null;

	protected $_renderable = null;

    protected $_controller = null;

	public function __construct(Chrome_Application_Context_Interface $appContext, Chrome_Controller_Interface $controller)
	{
	    $this->_controller = $controller;
		$this->_applicationContext = $appContext;
	}

	public function getApplicationContext()
	{
		return $this->_applicationContext;
	}

	public function setRenderable(Chrome_Renderable $renderable)
	{
		$this->_renderable = $renderable;
	}

	public function getRenderable()
	{
		return $this->_renderable;
	}

    public function getController()
    {
        return $this->_controller;
    }

	public function render()
	{
		if($this->_renderable !== null) {
			$this->_applicationContext->getResponse()->write($this->_renderable->render());
		}

        return null;
	}
}
