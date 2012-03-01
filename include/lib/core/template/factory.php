<?php

/**
 * CHROME-PHP CMS
 *
 * LICENSE
 *
 * This source file is subject to the Creative Commons license that is bundled
 * with this package in the file LICENSE.
 * It is also available through the world-wide-web at this URL:
 * http://creativecommons.org/licenses/by-nc-sa/3.0/
 * If you did not receive a copy of the license AND are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@chrome-php.de so we can send you a copy immediately.
 *
 * @package    CHROME-PHP
 * @subpackage Chrome.Template
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [10.08.2011 14:51:06] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true)
    die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Template
 */ 
class Chrome_Template_Engine_Factory
{
    private static $_instance = null;

    const CHROME_TEMPLATE_FACTORY_DEFAULT_ENGINE = 'plain';

    const CHROME_TEMPLATE_FACTORY_INCLUDE_PATH = 'lib/core/template/engine/';

    /**
     * Chrome_Template_Factory::factory()
     *
     * Creates a new object of an engine,
     * wrapper for create()
     *
     * @param Chrome_Template_Abstract $obj
     * @param string $engine Name of the engine
     * @return Chrome_Template_Engine_Abstract
     */
    public static function factory(Chrome_Template_Abstract $obj, $engine = CHROME_TEMPLATE_FACTORY_DEFAULT_ENGINE)
    {
        if($engine === null) {
            $engine = self::CHROME_TEMPLATE_FACTORY_DEFAULT_ENGINE;
        }

        return self::getInstance()->create($obj, $engine);
    }

    /**
     * Chrome_Template_Factory::getInstance()
     *
     * @return Chrome_Template_Factory
     */
    public static function getInstance()
    {
        if(self::$_instance === null) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Chrome_Template_Factory::create()
     *
     * Creates a new object of an engine
     *
     * @param Chrome_Template_Abstract $obj
     * @param string $engine Name of the engine
     * @return Chrome_Template_Engine_Abstract
     */
    public function create(Chrome_Template_Abstract $obj, $engine)
    {
        if(!_isFile(BASEDIR.self::CHROME_TEMPLATE_FACTORY_INCLUDE_PATH.$engine.'.php')) {
            throw new Chrome_Exception('Cannot create template engine ("'.$engine.'")! File '.BASEDIR.self::CHROME_TEMPLATE_FACTORY_INCLUDE_PATH.$engine.'.php does not exist in Chrome_Template_Factory::create()!');
        }

        // load engine file
        require_once BASEDIR.self::CHROME_TEMPLATE_FACTORY_INCLUDE_PATH.$engine.'.php';

        // create class name
        $class = 'Chrome_Template_Engine_'.ucfirst($engine);

        // create the class
        return new $class($obj);
    }
}