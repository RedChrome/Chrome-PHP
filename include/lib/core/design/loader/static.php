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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [27.03.2013 15:38:08] --> $
 */

if(CHROME_PHP !== true)
   die();

/**
 * @package    CHROME-PHP
 * @subpackage Chrome.Design
 */
class Chrome_Design_Loader_Static implements Chrome_Design_Loader_Interface
{
    protected $_compositions = array();

    protected $_applicationContext = null;

    public function __construct(Chrome_Application_Context_Interface $appContext) {
        $this->_applicationContext = $appContext;
    }

    public function addComposition(Chrome_Renderable_Composition_Interface $composition) {
        $this->_compositions[] = $composition;
    }

    public function getCompositions() {
        return $this->_compositions;
    }

    public function load() {

        foreach($this->_compositions as $composition) {

            $option = new Chrome_Renderable_Options_Static();

            $composition->getRequiredRenderables($option);

            $this->_loadViewsByPosition($composition, $option->getPosition());
        }
    }

    // this should get moved to a model
    protected function _loadViewsByPosition(Chrome_Renderable_Composition_Interface $composition, $position) {

        $dbFac = $this->_applicationContext->getDatabaseFactory();

        $db = $dbFac->buildInterface('Simple', array('iterator', 'assoc'));

        $result = $db->query('SELECT file, class, type FROM cpp_design_static WHERE position = "?" ORDER BY `order` ASC', array($position));

        foreach($result as $row) {

            require_once BASEDIR.$row['file'];

            switch($row['type']) {
                case 'view': {

                    $view = new $row['class']();

                    $composition->getRenderableList()->addRenderable($view);

                    break;
                }

                case 'controller': {

                    $controller = new $row['class']($this->_applicationContext);
                    $controller->execute();
                    $view = $controller->getView();

                    $composition->getRenderableList()->addRenderable($view);

                    break;
                }

                default: {
                    // todo finish default case
                }
            }
        }
    }
}