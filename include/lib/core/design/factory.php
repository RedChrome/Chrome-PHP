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
 * If you did not receive a copy of the license AND are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@chrome-php.de so we can send you a copy immediately.
 *
 * @package    CHROME-PHP
 * @subpackage Chrome.Design
 * @copyright  Copyright (c) 2008-2009 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://chrome-php.de/license/new-bsd		New BSD License
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [14.08.2011 14:00:34] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true)
    die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Design
 */
interface Chrome_Design_Factory_Interface
{
    public static function getInstance();

    public function factory($design);
}

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Design
 */
class Chrome_Design_Factory implements Chrome_Design_Factory_Interface
{
    const CHROME_DESIGN_FACTORY_INCLUDE_PATH = 'lib/core/design/design/';

    private static $_instance = null;

    private $_model = null;

    private function __construct()
    {
        $this->_model = Chrome_Model_Design_Factory::getInstance();
    }

    public static function getInstance()
    {
        if(self::$_instance === null) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function factory($design)
    {
        $file = BASEDIR.self::CHROME_DESIGN_FACTORY_INCLUDE_PATH.$design.'.php';

        if(!_isFile($file)) {
            throw new Chrome_Exception('Cannot load design '.$design.'! File '.$file.' does not exist in Chrome_Design_Factory::factory()!');
        }

        require_once $file;

        if(!defined('DESIGN_NAME')) {
            define('DESIGN_NAME', strtolower($design));
        }

        return call_user_func_array(array('Chrome_Design_'.ucfirst($design), 'getInstance'), array());
    }

    public function getDesign()
    {
        return $this->factory($this->_model->getDesign());
    }
}

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Design
 */
class Chrome_Model_Design_Factory extends Chrome_Model_Abstract
{
    const CHROME_MODEL_DESIGN_USER_DESIGN_COOKIE_KEY = 'USER_DESIGN';

    private static $_instance = null;

    public static function getInstance()
    {
        if(self::$_instance === null) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function getUserDesign()
    {
        $design = base64_decode(Chrome_Cookie::getInstance()->getCookie(self::CHROME_MODEL_DESIGN_USER_DESIGN_COOKIE_KEY));

        if(preg_match('#[^a-z0-9_]#i', $design)) {
            $this->setUserDesign($this->getDefaultDesign());
            return $this->getDefaultDesign();
        }

        if($design == '') {
            return $this->getDefaultDesign();
        }

        return $design;
    }

    public function setUserDesign($design)
    {
        Chrome_Cookie::getInstance()->setCookie(self::CHROME_MODEL_DESIGN_USER_DESIGN_COOKIE_KEY, base64_encode($design));
    }

    public function getDefaultDesign()
    {
        return Chrome_Config::getConfig('Design', 'Default_Design');
    }

    public function getDesign()
    {
        return $this->getUserDesign();
    }
}