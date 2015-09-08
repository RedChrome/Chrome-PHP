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
 * @subpackage Chrome.View
 */

namespace Chrome\View;

require_once 'factory.php';
require_once 'form.php';

/**
 * @package CHROME-PHP
 * @subpackage Chrome.View
 */
interface View_Interface extends \Chrome\Renderable
{
    /**
     * Sets a var
     *
     * @param string $key
     * @param mixed $value
     */
    public function setVar($key, $value);

    /**
     * Gets a set var
     *
     * @return mixed $value
     */
    public function getVar($key);
}

abstract class AbstractView implements View_Interface
{
    /**
     * @var Chrome_View_Plugin_Facade_Interface
     */
    protected $_pluginFacade  = null;

    /**
     * Contains the context of this view
     *
     * @var \Chrome\Context\View_Interface
     */
    protected $_viewContext = null;

    public function __construct(\Chrome\Context\View_Interface $viewContext)
    {
        $this->_viewContext = $viewContext;
        $this->_pluginFacade = $viewContext->getPluginFacade();
        $this->_setUp();
    }

    protected function _setUp()
    {
        // does nothing. you can put here your view logic (e.g. to set title or add .js files to include)
    }

    /**
     * Contains data for plugin methods
     *
     * @var array
     */
    protected $_vars = array();

    /**
     * magic method
     *
     * Calls a method from view helper if it exists
     *
     * @return mixed
    */
    public function __call($func, $args)
    {
        return $this->_callPluginMethod($func, $args);
    }

    /**
     * Calls the method $func with arguments $args
     *
     * @return mixed
     */
    protected function _callPluginMethod($func, $args)
    {
        if($this->_pluginFacade === null) {
            $this->_pluginFacade = $this->_viewContext->getPluginFacade();

            if($this->_pluginFacade === null) {
                return;
            }
        }

        return $this->_pluginFacade->call($func, array_merge(array($this), $args));
    }

    public function setVar($key, $value)
    {
        $this->_vars[$key] = $value;
    }

    public function getVar($key)
    {
        return (isset($this->_vars[$key])) ? $this->_vars[$key] : null;
    }
}


abstract class AbstractViewStrategy extends AbstractView
{
    protected $_view = null;

    public function render()
    {
        if($this->_view !== null) {
            return $this->_view->render();
        }
    }
}


interface Layout_Interface extends \Chrome\Renderable
{

}

abstract class AbstractLayout extends AbstractView
{

}

abstract class AbstractListLayout extends AbstractView implements Layout_Interface
{

    protected $_views = array();

    protected $_appending = '';

    public function render()
    {
        if(!is_array($this->_views)) {
            $this->_views = array($this->_views);
        }

        foreach($this->_views as $view) {
            $this->_append($view);
        }

        return $this->_appending;
    }

    protected function _append(\Chrome\Renderable $view)
    {
        $this->_appending .= $view->render();
    }

    public function addRenderable(\Chrome\Renderable $renderable)
    {
        $this->_views[] = $renderable;
    }
}

abstract class AbstractTemplate extends AbstractView
{
    protected $_templateFile = '';

    public function render()
    {
        $template = new \Chrome\Template\PHP();
        $template->assignTemplate($this->_templateFile);
        return $template->render();
    }
}

