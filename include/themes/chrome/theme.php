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

namespace Chrome\Design\Theme;

use \Chrome\Design\AbstractTheme;

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Design.Theme
 */
class Chrome extends AbstractTheme
{
    public function apply()
    {
        $diContainer = $this->_appContext->getDiContainer();

        require_once LIB . 'core/design/loader/static.php';

        $exceptionHandler = new \Chrome\Exception\Handler\HtmlStackTrace();

        $template = new \Chrome\Template\PHP();
        $template->assignTemplate('design/chrome/design.tpl');
        $template->assign('LINKER', $diContainer->get('\Chrome\Linker\Linker_Interface'));

        // this list needs 7 renderables
        $htmlList = new \Chrome\Renderable\RenderableList();
        $html = new \Chrome\Renderable\Composition\TemplateComposition($template, $exceptionHandler);
        $html->setRenderableList($htmlList);

        $this->_design->setRenderable($html);

        $head = new \Chrome\Renderable\Composition\Composition();
        $preBodyIn = new \Chrome\Renderable\Composition\Composition();
        $leftBox = new \Chrome\Renderable\Composition\Composition();
        $rightBox = new \Chrome\Renderable\Composition\Composition();
        $body = new \Chrome\Renderable\Composition\Composition();
        $footer = new \Chrome\Renderable\Composition\Composition();
        $postBodyIn = new \Chrome\Renderable\Composition\Composition();

        if($this->_controller !== null && (($view = $this->_controller->getView()) instanceof \Chrome\Renderable))
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

        $option = new \Chrome\Renderable\Option\StaticOption();

        // apply loaders
        foreach($compositions as $key => $composition)
        {
            $option->setPosition($key);
            $composition->setOption($option);

            $loader = $diContainer->get('\Chrome\Design\Loader_Interface');
            $loader->setTheme('chrome');
            $loader->addComposition($composition);
            $loader->load();

            $htmlList->addRenderable($composition);
        }
    }
}
