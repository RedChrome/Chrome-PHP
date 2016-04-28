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

    public function assignFile(\Chrome\File_Interface $file);

    public function _isset($name);

    public function injectViewContext(\Chrome\Context\View_Interface $viewContext);

    /**
     * Loads a template file, uses the currently set variables & view context and renderes immediately
     * the tempate.
     *
     * @param \Chrome\File_Interface $file
     * @return string
     */
    public function load(\Chrome\File_Interface $file);

    public function get($name, $global = true);

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

    /**
     * @var \Chrome\Directory_Interface
     */
    protected static $_templateDir = null;

    protected $_file = null;

    protected $_viewContext = null;

    public static function setTemplateDirectory(\Chrome\Directory_Interface $dir)
    {
        self::$_templateDir = $dir;
    }

    public static function getTemplateDirectory()
    {
        return self::$_templateDir;
    }

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

    public function injectViewContext(\Chrome\Context\View_Interface $viewContext)
    {
        $this->_viewContext = $viewContext;

        $this->assign('CONTEXT', $viewContext);
        $this->assign('LANG', $viewContext->getLocalization()->getTranslate());
        $this->assign('LINKER', $viewContext->getLinker());
    }

    /**
     *
     * @param \Chrome\File_Interface $file
     * @throws \Chrome\Exception
     */
    public function assignFile(\Chrome\File_Interface $file)
    {
        $file = self::$_templateDir->file($file, true);

        if(!$file->hasExtension('tpl')) {
            throw new \Chrome\Exception('Every template file must have the extension .tpl');
        }

        if(!$file->exists()) {
            throw new \Chrome\Exception('Cannot assign a template file '.$file.' that does not exist');
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
    public function __construct(\Chrome\File_Interface $file)
    {
        $this->assignFile($file);
    }

    public function render()
    {
        /* This code is equivalent to extract($this->_var);
        foreach($this->_var as $key => $value)
        {
            $$key = $value;
        }
        */

        // here we need to set vars, so that php knows the content of the tmpl-vars!!
        extract($this->_var);
        extract((array) $this);

        ob_start();

        include($this->_file->getFileName());

        $return = ob_get_contents();

        ob_end_clean();

        // all assigned vars get destroyed automatically

        return $return;
    }

    public function load(\Chrome\File_Interface $file, array $vars = array())
    {
        $template = new self($file);

        if($this->_viewContext !== null) {
            $template->injectViewContext($this->_viewContext);
        }

        $template->assignArray($this->_var);

        if(count($vars) > 0) {
            $template->assignArray($vars);
        }

        return $template->render();
    }
}