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
 * @package CHROME-PHP
 * @subpackage Chrome.Router
 */

namespace Chrome\Router\Route;

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Router
 */
class DirectoryRoute extends AbstractRoute
{
    protected $_prefix = '';

    public function match(\Psr\Http\Message\ServerRequestInterface $request, $normalizedPath)
    {
        $modules = $this->_model->getModules();


        // TODO: finish.
        return false;


        $path = $this->_prefix.$url->getPath();

        var_dump(array_search($path, $modules));

        var_dump($url->getPath());
    }
}

namespace Chrome\Model\Route\DirectoryRoute;

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Router
 */
class Cache extends \Chrome\Model\AbstractCache
{

}

class Model extends \Chrome\Model\AbstractModel
{
    const CONTROLLER_FILE = 'controller.php';

    protected $_dirIterator = null;

    /**
     * @var array
     */
    protected $_return = array();

    protected $_prefix = '';

    /**
     * @param \Iterator $directoryIterator
     *
     * The Iterator should only contain \Chrome\Directory_Interface objects
     */
    public function __construct(\Iterator $directoryIterator, $prefix = '')
    {
        $this->_dirIterator = $directoryIterator;
        $this->_prefix = $prefix;
    }

    public function getModules()
    {
        $this->_return = array();

        foreach($this->_dirIterator as $directory)
        {
            $this->_recursiveDirectoryWalk($directory);
        }

        return str_replace($this->_prefix, '', $this->_return);

        #return $this->_return;
    }

    protected function _recursiveDirectoryWalk(\Chrome\Directory_Interface $directory)
    {
        $controller = $directory->file(self::CONTROLLER_FILE, true);

        if($controller->exists()) {
            $this->_return[] = $controller->getDirname();
        }

        $iterator = $directory->getDirectoryObjectIterator();

        foreach($iterator as $dir)
        {
            $this->_recursiveDirectoryWalk($dir);
        }
    }
}