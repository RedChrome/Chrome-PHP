<?php

/**
 * CHROME-PHP CMS
 *
 * PHP version 5
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
 * @category   CHROME-PHP
 * @package    CHROME-PHP
 * @subpackage Chrome.Authentication
 * @author     Alexander Book <alexander.book@gmx.de>
 * @copyright  2012 Chrome - PHP <alexander.book@gmx.de>
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [01.11.2012 22:46:23] --> $
 * @link       http://chrome-php.de
 */

if(CHROME_PHP !== true) die();

/**
 * @package    CHROME-PHP
 * @subpackage Chrome.Authentication
 */
class Chrome_Authentication_Chain_Session extends Chrome_Authentication_Chain_Abstract
{
    protected $_options = array('session_namespace' => '_AUTH_SESSION');

    public function __construct(array $options = array())
    {
        $this->_options = array_merge($this->_options, $options);
    }

    protected function _update(Chrome_Authentication_Data_Container_Interface $return)
    {
        $session = Chrome_Session::getInstance();

        $array = array('id' => $return->getID());

        $session->set($this->_options['session_namespace'], $array);

    }

    public function authenticate(Chrome_Authentication_Resource_Interface $resource = null)
    {
        if($resource !== null) {
            return $this->_chain->authenticate($resource);
        }

        $session = Chrome_Session::getInstance();

        $data = $session->get($this->_options['session_namespace']);

        if($data === null or empty($data) === true or !isset($data['id'])) {
            return $this->_chain->authenticate($resource);
        }

        $container = new Chrome_Authentication_Data_Container();

        $container->setID($data['id']);

        return $container;
    }

    protected function _deAuthenticate()
    {
        $session = Chrome_Session::getInstance();

        $session->set($this->_options['session_namespace'], null);
    }

    protected function _createAuthentication(Chrome_Authentication_Create_Resource_Interface $resource)
    {
        // do nothing
    }
}
