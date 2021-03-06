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
 * @package    CHROME-PHP
 * @subpackage Chrome.Authentication
 */

namespace Chrome\Authentication\Chain;

use \Chrome\Authentication\Container_Interface;
use \Chrome\Authentication\Resource_Interface;
use \Chrome\Authentication\CreateResource_Interface;
use \Chrome\Authentication\Container;

/**
 * @package    CHROME-PHP
 * @subpackage Chrome.Authentication
 */
class SessionChain extends Chain_Abstract
{
    protected $_options = array('session_namespace' => '_AUTH_SESSION');

    /**
     * Implementation of \Chrome\Request\Session_Interface
     *
     * @var \Chrome\Request\Session_Interface
     */
    protected $_session = null;


    public function __construct(\Chrome\Request\Session_Interface $session, array $options = array())
    {
        $this->_session = $session;

        $this->_options = array_merge($this->_options, $options);
    }

    protected function _update(Container_Interface $return)
    {
        $array = array('id' => $return->getID());

        $this->_session->set($this->_options['session_namespace'], $array);
    }

    public function authenticate(Resource_Interface $resource = null)
    {
        if($resource !== null) {
            return $this->_chain->authenticate($resource);
        }

        $data = $this->_session[$this->_options['session_namespace']];

        if($data === null or empty($data) === true or !isset($data['id'])) {
            return $this->_chain->authenticate($resource);
        }

        $container = new Container(__CLASS__);

        $container->setID($data['id']);

        $container->setStatus(Container_Interface::STATUS_USER);

        return $container;
    }

    protected function _deAuthenticate()
    {
        $this->_session->set($this->_options['session_namespace'], null);
    }

    protected function _createAuthentication(CreateResource_Interface $resource)
    {
        // do nothing
    }
}
