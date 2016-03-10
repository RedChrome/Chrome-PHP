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
 * @package CHROME-PHP
 * @subpackage Chrome.Router
 */
class ApplicationRoute extends AbstractRoute
{
    public function __construct(\Chrome\Model\Model_Interface $model)
    {
        $this->_model = $model;
    }

    public function match(\Psr\Http\Message\ServerRequestInterface $request, $normalizedPath)
    {
        $apps = $this->_model->getApplicationResolves();

        foreach($apps as $app) {

            $appPath = $app[\Chrome\Model\Route\ApplicationModel\Model::PATH_INDEX];
            $appClass = $app[\Chrome\Model\Route\ApplicationModel\Model::APP_INDEX];

            if(0 === strpos($normalizedPath, $appPath)) {
                $this->_result = new \Chrome\Router\Result();
                $this->_result->setClass($appClass);
                $this->_result->setRequest($request);

                return true;
            }
        }

        return false;
    }
}

namespace Chrome\Model\Route\ApplicationModel;

use Chrome\Model\AbstractModel;

class Model extends AbstractModel
{
    const PATH_INDEX = 0;
    const APP_INDEX = 1;

    protected $_apps = array();

    public function __construct()
    {

    }

    public function addApplicationResolve($path, $applicationClass)
    {
        $this->_apps[] = array(self::PATH_INDEX => $path, self::APP_INDEX => $applicationClass);
    }

    public function getApplicationResolves()
    {
        return $this->_apps;
    }
}
