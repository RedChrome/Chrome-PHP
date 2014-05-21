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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [02.04.2013 19:38:48] --> $
 */

if(CHROME_PHP !== true) die();

/**
 * @package    CHROME-PHP
 * @subpackage Chrome.Design.Theme
 */
class Chrome_Design_Theme_Console extends Chrome_Design_Theme_Abstract
{
    public function initDesign(Chrome_Design_Interface $design, \Chrome\Controller\Controller_Interface $controller)
    {
        $htmlList = new \Chrome\Renderable\RenderableList();
        $html = new Chrome\Renderable\Composition\Composition();
        $html->setRenderableList($htmlList);

        $design->setRenderable($html);

        $html->getRenderableList()->addRenderable($controller->getView());
    }
}
