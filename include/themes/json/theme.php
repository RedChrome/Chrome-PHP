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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [28.03.2013 12:50:12] --> $
 */

if(CHROME_PHP !== true) die();

/**
 * @package    CHROME-PHP
 * @subpackage Chrome.Design.Theme
 */
class Chrome_Design_Theme_Json implements Chrome_Design_Theme_Interface
{
	public function initDesign(Chrome_Design_Interface $design)
	{
		require_once LIB.'core/design/options/static.php';
		require_once LIB.'core/design/loader/static.php';

		$template = new Chrome_Template();
		$template->assignTemplate('design/chrome/design.tpl');

		// this list need 7 renderables
		$htmlList = new Chrome_Renderable_List();
		#$html = new Chrome_Renderable_Template($template);
        $html = new Chrome_Renderable_Composition_Array_Impl();
		$html->setRenderableList($htmlList);

		$design->setRenderable($html);

		$html->getRenderableList()->addRenderable($design->getController()->getView());
	}
}
