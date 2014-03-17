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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [22.04.2013 19:45:38] --> $
 */

if(CHROME_PHP !== true) die();

/**
 * @package    CHROME-PHP
 * @subpackage Chrome.Design
 */
class Chrome_Renderable_Template extends Chrome_Renderable_Composition
{
    protected $_template = null;

    protected $_exceptionHandler = null;

    public function __construct(Chrome_Template_Interface $template, \Chrome\Exception\Handler_Interface $exceptionHandler)
    {
        $this->_template = $template;
        $this->_exceptionHandler = $exceptionHandler;
        parent::__construct();
    }

    public function render()
    {
        $this->_template->assign('VIEW', $this->_renderables);
        $this->_template->assign('exceptionHandler', $this->_exceptionHandler);
        return $this->_template->render();
    }

    public function getRequiredRenderables()
    {
        return null;
    }
}
