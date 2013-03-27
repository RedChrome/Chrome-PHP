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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [27.03.2013 15:45:10] --> $
 */

if(CHROME_PHP !== true)
   die();

/**
 * @package    CHROME-PHP
 * @subpackage Chrome.Design
 */
interface Chrome_Renderable_List_Interface extends Iterator
{
    public function addRenderable(Chrome_Renderable $obj);

    public function getRenderables();

    public function setRenderables(array $renderables);

    public function getRenderable($index);
}
// dummy interface, gets deleted after migration to new design
interface Chrome_Design_Renderable {

}
/**
 * @package    CHROME-PHP
 * @subpackage Chrome.Design
 */
interface Chrome_Renderable
{
    /**
     * @return mixed
     */
    public function render();
}

/**
 * @package    CHROME-PHP
 * @subpackage Chrome.Design
 */
interface Chrome_Renderable_Composition_Interface extends Chrome_Renderable
{
    public function getRequiredRenderables(Chrome_Renderable_Options_Interface $options);

    public function getRenderableList();

    public function setRenderableList(Chrome_Renderable_List_Interface $list);
}


/**
 * @package    CHROME-PHP
 * @subpackage Chrome.Design
 */
interface Chrome_Design_Interface extends Chrome_Renderable
{
    //public function __construct(Chrome_Application_Context_Interface $appContext);

    public function getApplicationContext();

    public function setRenderable(Chrome_Renderable $renderable);

    public function getRenderable();
}
/**
 * @package    CHROME-PHP
 * @subpackage Chrome.Design
 */
interface Chrome_Design_Loader_Interface
{
    public function addComposition(Chrome_Renderable_Composition_Interface $composition);

    public function getCompositions();

    public function load();
}

// todo: finish interface
interface Chrome_Renderable_Options_Interface {

}

// remove those includes, place them anywhere else
require_once 'theme.php';
require_once 'renderable/composition.php';
require_once 'renderable/template.php';
require_once 'design/default.php';
require_once 'list/list.php';
