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
 */

namespace Chrome\Template;


 // TODO: add template to DI, make static member vars un-static
 // but let the functionality working
/**
 * @package CHROME-PHP
 * @subpackage Chrome.Template
 */
interface Template_Interface extends \Chrome\Renderable
{
    public function assign($name, $value);

    public function assignArray(array $array);

    public function assignGlobal($name, $value);

    public function assignArrayGlobal(array $array);

    public function assignTemplate($name, $path = '');

    public function _isset($name);

    public function get($name, $global = true);

    public function render();
}

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Template
 */
abstract class AbstractTemplate implements Template_Interface
{
    protected $_var = array();

    protected static $_globalVar = array('ROOT' => ROOT, '_PUBLIC' => _PUBLIC, 'IMAGE' => IMAGE, 'CONTENT' => CONTENT,
            'BASEDIR' => BASEDIR, 'BASE' => BASE, 'ADMIN' => ADMIN, 'LIB' => LIB, 'TEMPLATE' => TEMPLATE, 'TMP' => TMP,
            'CACHE' => CACHE);

    protected $_file = null;

    public function assign($name, $value)
    {
        $this->_var[$name] = $value;
    }

    public function assignArray(array $array)
    {
        $this->_var += $array;
    }

    public function assignGlobal($name, $value)
    {
        self::$_globalVar[$name] = $value;
    }

    public function assignArrayGlobal(array $array)
    {
        self::$_globalVar += $array;
    }

    public function assignTemplate($name, $path = '')
    {
        if(empty($name)) {
            throw new \Chrome\Exception('No template file given!');
        }

        if(strstr($name, '.tpl') === false) {
            $name .= '.tpl';
        }

        if($path !== '') {
            // add a "/" at the end of the path
            $path .= ($path{strlen($path)-1} !== '/') ? '/' : '';

            $file = $path.$name;
        } else {
            $file = TEMPLATE.$name;
        }

        $file = new \Chrome\File($file);

        if(!$file->exists()) {
            throw new \Chrome\Exception('Cannot assign a template file '.$file.' that does not exist in Chrome_Tepmate_Engine_Abstract::assignTemplate()!');
        }

        $this->_file = $file;
    }

    public function _isset($name)
    {
        return (isset($this->_var[$name]) OR isset(self::$_globalVar[$name]));
    }

    public function get($name, $global = true)
    {
        if(isset($this->_var[$name])) {
            return $this->_var[$name];
        } elseif($global === true AND isset(self::$_globalVar[$name])) {
            return self::$_globalVar[$name];
        } else {
            return null;
        }
    }
}

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Template
 */
class PHP extends AbstractTemplate
{
    public function render()
    {
        if($this->_file === null) {
            throw new \Chrome\IllegalStateException('Did not call assignTemplate');
        }

        // here we need to set vars, so that php knows the content of the tmpl-vars!!
        foreach($this->_var as $key => $value)
        {
            $$key = $value;
        }

        ob_start();

        include($this->_file->getFileName());

        $return = ob_get_contents();

        ob_end_clean();

        // all assigned vars get destroyed automatically

        return $return;
    }
}