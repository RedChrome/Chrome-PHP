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

namespace Chrome\Design;

require_once 'renderable.php';

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Design
 */
interface Design_Interface extends \Chrome\Renderable
{
    public function setRenderable(\Chrome\Renderable $renderable);

    public function getRenderable();
}
/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Design
 */
interface Loader_Interface
{
    public function setTheme($theme);

    public function addComposition(\Chrome\Renderable\Composition\Composition_Interface $composition);

    public function getCompositions();

    public function load();
}


/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Design.Theme
 */
interface Theme_Interface
{
    public function setApplicationContext(\Chrome\Context\Application_Interface $appContext);

    public function setDesign(Design_Interface $design);

    public function setController(\Chrome\Controller\Controller_Interface $controller);

    /**
     * Applies the theme to the given design
     *
     * @return void
     */
    public function apply();

    // TODO: refine this method
    //public function initDesign(Design_Interface $design, \Chrome\Controller\Controller_Interface $controller);
}

abstract class AbstractTheme implements Theme_Interface
{

    /**
     * @var \Chrome\Context\Application_Interface
     */
    protected $_appContext = null;

    /**
     * @var Design_Interface
     */
    protected $_design = null;

    /**
     * @var \Chrome\Controller\Controller_Interface
     */
    protected $_controller = null;

    public function setDesign(Design_Interface $design)
    {
        $this->_design = $design;
    }

    public function setController(\Chrome\Controller\Controller_Interface $controller)
    {
        $this->_controller = $controller;
    }

    public function setApplicationContext(\Chrome\Context\Application_Interface $appContext)
    {
        $this->_appContext = $appContext;
    }
}

/**
 * @package    CHROME-PHP
 * @subpackage Chrome.Design
 */
interface Factory_Interface
{
    public function __construct(\Chrome\Context\Application_Interface $appContext);

    public function build();
}

abstract class AbstractFactory implements Factory_Interface
{
    protected $_applicationContext = null;

    public function __construct(\Chrome\Context\Application_Interface $appContext)
    {
        $this->_applicationContext = $appContext;
    }
}

// @todo remove those includes, place them anywhere else
require_once 'renderable/composition.php';
require_once 'renderable/template.php';
require_once 'design/default.php';