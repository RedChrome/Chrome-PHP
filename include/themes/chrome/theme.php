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
 * @subpackage Chrome.Design.Theme
 */
class Chrome_Design_Theme_Chrome extends Chrome_Design_Theme_Abstract
{
    public function initDesign(Chrome_Design_Interface $design, Chrome_Controller_Interface $controller, \Chrome\DI\Container_Interface $diContainer)
    {
        require_once LIB . 'core/design/options/static.php';
        require_once LIB . 'core/design/loader/static.php';

        // @todo use another exception handler
        $exceptionHandler = new Chrome_Exception_Handler_Default();

        $template = new Chrome_Template();
        $template->assignTemplate('design/chrome/design.tpl');
        $template->assign('LINKER', $diContainer->get('\Chrome\Linker\Linker_Interface'));

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

        $option = new Chrome_Renderable_Options_Static();

        // apply loaders
        foreach($compositions as $key => $composition)
        {
            $option->setPosition($key);
            $composition->setOption($option);

            $loader = $diContainer->get('\Chrome_Design_Loader_Interface');
            $loader->setTheme('chrome');
            $loader->addComposition($composition);
            $loader->load();

            $htmlList->addRenderable($composition);
        }
    }
}
