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
 * @subpackage Chrome.View
 */

namespace Chrome\View;

/**
 * @package CHROME-PHP
 * @subpackage Chrome.View
 */
interface Factory_Interface
{
    /**
     *
     * @param unknown $view
     * @return \Chrome\Renderable
     */
    public function get($view);
}

/**
 * @package CHROME-PHP
 * @subpackage Chrome.View
 */
class Factory implements Factory_Interface
{
    protected $_diContainer = null;

    public function __construct(\Chrome\DI\Container_Interface $diContainer)
    {
        $this->_diContainer = $diContainer;
    }

    public function get($view)
    {
        $return = $this->_diContainer->get($view);

        // this is necessary. In order to not allow views to access other object
        if(!($return instanceof \Chrome\Renderable)) {
            throw new \Chrome\Exception('Views are only able to retrieve \Chrome\Renderable objects!');
        }

        return $return;
    }

}
