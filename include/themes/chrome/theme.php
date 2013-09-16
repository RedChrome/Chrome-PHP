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
 * @version $Id: 0.1 beta <!-- phpDesigner :: Timestamp [31.05.2013 20:06:10] --> $
 */
if(CHROME_PHP !== true)
    die();

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Design.Theme
 */
class Chrome_Design_Theme_Chrome extends Chrome_Design_Theme_Abstract
{
    public function initDesign(Chrome_Design_Interface $design, Chrome_Controller_Interface $controller)
    {
        require_once LIB . 'core/design/options/static.php';
        require_once LIB . 'core/design/loader/static.php';

        // @todo use another exception handler
        $exceptionHandler = new Chrome_Exception_Handler_Default();

        $template = new Chrome_Template();
        $template->assignTemplate('design/chrome/design.tpl');

        // this list needs 7 renderables
        $htmlList = new Chrome_Renderable_List();
        $html = new Chrome_Renderable_Template($template, $exceptionHandler);
        $html->setRenderableList($htmlList);

        $design->setRenderable($html);

        $head = new Chrome_Renderable_Composition();
        $preBodyIn = new Chrome_Renderable_Composition();
        $leftBox = new Chrome_Renderable_Composition();
        $rightBox = new Chrome_Renderable_Composition();
        $body = new Chrome_Renderable_Composition();
        $footer = new Chrome_Renderable_Composition();
        $postBodyIn = new Chrome_Renderable_Composition();

        $view = $controller->getView();

        if($view instanceof Chrome_Renderable)
        {
            $body->getRenderableList()->addRenderable($view);
        }

        $compositions = array('head' => $head,
                            'preBodyIn' => $preBodyIn,
                            'leftBox' => $leftBox,
                            'rightBox' => $rightBox,
                            'body' => $body,
                            'footer' => $footer,
                            'postBodyIn' => $postBodyIn);

        $model = new Chrome_Model_Design_Loader_Static_Cache(new Chrome_Model_Design_Loader_Static($this->_appContext->getModelContext()));
        $controllerFactory = $this->_appContext->getControllerFactoryRegistry()->get();
        $viewFactory = $this->_appContext->getViewContext()->getFactory();

        $option = new Chrome_Renderable_Options_Static();

        // apply loaders
        foreach($compositions as $key => $composition)
        {
            $option->setPosition($key);
            $composition->setOption($option);

            $loader = new Chrome_Design_Loader_Static($controllerFactory, $viewFactory, $model, 'chrome');
            $loader->addComposition($composition);
            $loader->load();

            $htmlList->addRenderable($composition);
        }
    }
}
