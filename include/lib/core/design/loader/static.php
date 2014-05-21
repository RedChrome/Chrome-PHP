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

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Design
 */
class Chrome_Design_Loader_Static implements Chrome_Design_Loader_Interface
{
    protected $_compositions = array();

    protected $_dependencyContainer = null;

    protected $_viewFactory = null;

    protected $_model = null;

    protected $_theme = '';

    public function __construct(\Chrome\DI\Container_Interface $dependencyContainer, Chrome_View_Factory_Interface $viewFactory, Chrome_Model_Abstract $model)
    {
        $this->_dependencyContainer = $dependencyContainer;
        $this->_viewFactory = $viewFactory;
        $this->_model = $model;
    }

    public function setTheme($theme)
    {
        $this->_theme = $theme;
    }

    public function addComposition(\Chrome\Renderable\Composition\Composition_Interface $composition)
    {
        $this->_compositions[] = $composition;
    }

    public function getCompositions()
    {
        return $this->_compositions;
    }

    public function load()
    {
        foreach($this->_compositions as $composition)
        {
            $option = $composition->getRequiredRenderables();

            if(!($option instanceof \Chrome\Renderable\Option\StaticOption))
            {
                continue;
            }

            $this->_loadViewsByPosition($composition, $this->_model->getViewsByPosition($option->getPosition(), $this->_theme));
        }
    }

    // @todo: this should get moved to a model
    protected function _loadViewsByPosition(\Chrome\Renderable\Composition\Composition_Interface $composition, \Chrome\Database\Result\Iterator $resultViewsWithPosition)
    {
        if($resultViewsWithPosition->isEmpty())
        {
            return;
        }

        foreach($resultViewsWithPosition as $row)
        {
            $file = new \Chrome\File(BASEDIR . $row['file']);

            if(!$file->exists())
            {
                throw new \Chrome\Exception('Cannot load file '.$file.' containing required class for rendering');
            }

            require_once $file->getFileName();

            $view = null;

            switch(strtolower($row['type']))
            {
                case 'view':
                    {
                        // TODO: use dependency container to create view
                        #$view = $this->_dependencyContainer->get($row['class']);
                        $view = $this->_viewFactory->build($row['class']);

                        break;
                    }

                case 'controller':
                    {
                        $controller = $this->_dependencyContainer->get($row['class']);

                        $controller->execute();

                        $view = $controller->getView();

                        // discard view
                        if(!($view instanceof \Chrome\Renderable))
                        {
                            return;
                        }

                        break;
                    }

                default:
                    {
                        throw new \Chrome\Exception('Unknown type ' . $row['type'] . '. Available types are: controller, view');
                    }
            }

            $composition->getRenderableList()->addRenderable($view);
        }
    }
}
class Chrome_Model_Design_Loader_Static_Cache extends Chrome_Model_Cache_Abstract
{
    public function getViewsByPosition($position, $theme)
    {
        $key = $theme . '/' . $position;

        if(!$this->_cache->has($key))
        {
            $this->_cache->set($key, $this->_decorable->getViewsByPosition($position, $theme));
        }

        return $this->_cache->get($key);
    }
}
class Chrome_Model_Design_Loader_Static_DB extends \Chrome_Model_Database_Statement_Abstract
{
    protected function _setDatabaseOptions()
    {
        $this->_dbResult = array('\Chrome\Database\Result\Iterator', '\Chrome\Database\Result\Assoc');
    }

    public function getViewsByPosition($position, $theme)
    {
        return $this->_getDBInterface()->loadQuery('designLoaderStaticGetViewsByPosition')->execute(array($position, $theme));
    }
}
