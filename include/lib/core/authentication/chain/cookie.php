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
class CookieChain extends Chain_Abstract
{
    protected $_options = array(
                           'cookie_namespace'         => '_AUTH',
                           'cookie_renew_probability' => 10
                          );

    protected $_model   = null;

    protected $_cookie  = null;

    protected $_hash    = null;

    /**
     * @param \Chrome\Model\AbstractModel $model model which implements the methods of Chrome_Model_Authentication_Cookie
     * @param \Chrome\Request\Cookie_Interface $cookie
     * @param Chrome\Hash\Hash_Interface $hash
     * @param array $options:
     *                  - cookie_namespace: (string) Namespace of the cookie, default: _AUTH
     *                  - cookie_renew_probability: (int) probability (1:x) when the cookie gets renewed, e.g.
     *                                              probability is set to 20, then the probability is 5% = 1/20, default: 10
     *
     * @return Chrome_Authentication_Chain_Cookie
     */
    public function __construct(\Chrome\Model\Model_Interface $model, \Chrome\Request\Cookie_Interface $cookie, \Chrome\Hash\Hash_Interface $hash)
    {
        $this->_model         = $model;
        $this->_cookie        = $cookie;
        $this->_hash          = $hash;
    }

    public function setOptions(array $options)
    {
        $this->_options = array_merge($this->_options, $options);
    }

    protected function _update(Container_Interface $return)
    {
        $data = $this->_cookie->getCookie($this->_options['cookie_namespace']);

        if($data !== null) {

            if(mt_rand(1, $this->_options['cookie_renew_probability']) === 1) {
                $this->_renewCookie($return->getID());
            }
        } else
            if($return->getAutoLogin() === true) {

                $this->_renewCookie($return->getID());
            }
    }

    public function authenticate(Resource_Interface $resource = null)
    {
        if($resource !== null) {
            $this->_chain->authenticate($resource);
        }

        $data = $this->_cookie->getCookie($this->_options['cookie_namespace']);

        if($data === null or $data == false) {
            return $this->_chain->authenticate($resource);
        }

        $array = $this->_decodeCookieString($data);

        $id = $array['id'];
        $token = $array['token'];

        // cookie is invalid
        if(empty($id) or empty($token)) {
            return $this->_clearCookie();
        }

        $result = $this->_model->doesIdAndTokenExist($id, $token);

        // id and token not valid
        if($result === null) {
            return $this->_clearCookie();
        } else {

            // no entry found
            if($result->isEmpty()) {
                return $this->_chain->authenticate($resource);
            } else {
                $container = new Container(__CLASS__);
                $container->setID($id)->setAutoLogin(true);
                $container->setStatus(Container_Interface::STATUS_USER);

                return $container;
            }
        }
    }

    protected function _deAuthenticate()
    {
        $this->_cookie->unsetCookie($this->_options['cookie_namespace']);
    }

    private function _clearCookie()
    {
        $this->_deAuthenticate();

        return new Container(__CLASS__);
    }

    private function _renewCookie($id)
    {
        $id = (int) $id;

        $token = $this->_createNewToken();

        $this->_cookie->setCookie($this->_options['cookie_namespace'], $this->_encodeCookieString($id, $token));

        $this->_model->updateIdAndToken($id, $token);
    }

    protected function _createAuthentication(CreateResource_Interface $resource)
    {
        // do nothing
    }

    protected function _encodeCookieString($id, $token)
    {
        $id = (int) $id;

        return base64_encode($id . '.' . $token);
    }

    protected function _decodeCookieString($string)
    {
        // data is base64 encoded
        $data = base64_decode($string);
        // data structure: ID.TOKEN
        $array = explode('.', $data, 2);

        if(count($array) !== 2) {
            return array('id' => null, 'token' => null);
        }

        return array(
                'id'    => (int) $array[0],
                'token' => $array[1],
        );
    }

    protected function _createNewToken()
    {
        return $this->_hash->createKey();
    }
}

namespace Chrome\Model\Authentication;

class Cookie extends \Chrome\Model\AbstractDatabaseStatement
{
    /**
     * @var array
     */
    protected $_options = array(
                           'dbTable' => 'authenticate',
                          );

    /**
     * @param array $options:
     *                  - dbTable: (string) Name of the db-table, containing the cookie-token and id, default: authenticate
     * @return void
     */
    public function setOptions(array $options)
    {
        $this->_options = array_merge($this->_options, $options);
    }

    public function doesIdAndTokenExist($id, $token)
    {
        $db = $this->_getDBInterface();

        // user has sent an invalid token
        if($token !== ($tokenEscaped = $db->escape($token))) {
            return null;
        }

        $db->loadQuery('authenticationDoesIdAndTokenExist')
            ->execute(array($this->_options['dbTable'], $id, $tokenEscaped));

        return $db->getResult();
    }

    public function updateIdAndToken($id, $token)
    {
        $db = $this->_getDBInterface();

        // update database
        $db->loadQuery('authenticationUpdateTokenById')
            ->execute(array($this->_options['dbTable'], $token, $id));
    }
}
