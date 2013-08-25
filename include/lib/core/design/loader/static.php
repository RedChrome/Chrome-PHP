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
 * @copyright Copyright (c) 2008-2009 Chrome - PHP (http://www.chrome-php.de)
 * @license http://chrome-php.de/license/new-bsd New BSD License
 * @version $Id: 0.1 beta <!-- phpDesigner :: Timestamp [31.05.2013 20:06:44] --> $
 */
if(CHROME_PHP !== true)
    die();

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Design
 */
class Chrome_Design_Loader_Static implements Chrome_Design_Loader_Interface
{

    protected $_compositions = array();

    protected $_controllerFactory = null;

    protected $_viewFactory = null;

    protected $_model = null;

    protected $_theme = '';

    public function __construct(Chrome_Controller_Factory $controllerFactory, Chrome_View_Factory_Interface $viewFactory, Chrome_Model_Abstract $model, $theme)
    {
        $this->_controllerFactory = $controllerFactory;
        $this->_viewFactory = $viewFactory;
        $this->_model = $model;
        $this->_theme = $theme;
    }

    public function addComposition(Chrome_Renderable_Composition_Interface $composition)
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

            if(!($option instanceof Chrome_Renderable_Options_Static))
            {
                continue;
            }

            $this->_loadViewsByPosition($composition, $this->_model->getViewsByPosition($option->getPosition(), $this->_theme));
        }
    }

    // @todo: this should get moved to a model
    protected function _loadViewsByPosition(Chrome_Renderable_Composition_Interface $composition, Chrome_Database_Result_Iterator $resultViewsWithPosition)
    {
        if($resultViewsWithPosition->isEmpty())
        {
            return;
        }

        foreach($resultViewsWithPosition as $row)
        {

            if(!_isFile(BASEDIR . $row['file']))
            {
                throw new Chrome_Exception('Cannot load file ' . BASEDIR . $row['file'] . ' containing required class for rendering');
            }

            require_once BASEDIR . $row['file'];

            $view = null;

            switch(strtolower($row['type']))
            {
                case 'view':
                    {
                        $view = $this->_viewFactory->build($row['class']);

                        break;
                    }

                case 'controller':
                    {
                        $controller = $this->_controllerFactory->build($row['class']);

                        $controller->execute();

                        $view = $controller->getView();

                        // discard view
                        if(!($view instanceof Chrome_Renderable))
                        {
                            return;
                        }

                        break;
                    }

                default:
                    {
                        throw new Chrome_Exception('Unknown type ' . $row['type'] . '. Available types are: controller, view');
                    }
            }

            $composition->getRenderableList()->addRenderable($view);
        }
    }
}
class Chrome_Model_Design_Loader_Static_Cache extends Chrome_Model_Cache_Abstract
{

    protected function _setUpCache()
    {
        $this->_cacheInterface = 'Serialization';
        $this->_cacheOption = new Chrome_Cache_Option_Serialization();
        $this->_cacheOption->setCacheFile(CACHE.'_designLoaderStatic.cache');
    }

    public function getViewsByPosition($position, $theme)
    {
        $key = $theme . '/' . $position;

        if($this->_cache->has($key))
        {
            return $this->_cache->get($key);
        }

        $data = $this->_decorable->getViewsByPosition($position, $theme);
        $this->_cache->set($key, $data);
        return $data;
    }
}
class Chrome_Model_Design_Loader_Static extends Chrome_Model_Database_Abstract
{
    protected function _setDatabaseOptions()
    {
        $this->_dbInterface = 'Simple';
        $this->_dbResult = array('Iterator', 'Assoc');
    }

    public function getViewsByPosition($position, $theme)
    {
        $db = $this->_getDBInterface();

        return $db->query('SELECT `file`, `class`, `type` FROM cpp_design_static WHERE `position` = "?" AND `theme` = "?"  ORDER BY `order` ASC', array($position, $theme));
    }
}
