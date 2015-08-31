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

require_once 'factory.php';
require_once 'form.php';

/**
 * @package CHROME-PHP
 * @subpackage Chrome.View
 */
interface Chrome_View_Interface extends \Chrome\Renderable
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

abstract class Chrome_View implements Chrome_View_Interface
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

/**
 * @package CHROME-PHP
 * @subpackage Chrome.View
 */
abstract class Chrome_View_Abstract extends Chrome_View
{
    /**
     * Contains the controller
     *
     * @var \Chrome\Controller\AbstractController
     */
    protected $_controller = null;

    /**
     * Constructor
     * @todo why controller?
     * @return \Chrome\Controller\Controller_Interface
     */
    public function __construct(\Chrome\Context\View_Interface $viewContext, \Chrome\Controller\Controller_Interface $controller)
    {
        parent::__construct($viewContext);
        #$this->_controller = $controller;
    }
}

abstract class Chrome_View_Strategy_Abstract extends Chrome_View
{
    protected $_views = array();

    public function render()
    {
        $return = '';

        if(!is_array($this->_views)) {
            $this->_views = array($this->_views);
        }

        foreach($this->_views as $view) {
            $return .= $view->render();
        }

        return $return;
    }

    public function addRenderable(\Chrome\Renderable $renderable)
    {
        $this->_views[] = $renderable;
    }
}

class Chrome_View_Template_Simple_Abstract extends Chrome_View
{
    protected $_templateFile = '';

    public function render()
    {
        $template = new \Chrome\Template\PHP();
        $template->assignTemplate($this->_templateFile);
        return $template->render();
    }
}

require_once 'nview.php';
