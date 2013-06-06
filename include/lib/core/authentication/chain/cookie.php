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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [30.05.2013 22:07:36] --> $
 * @link       http://chrome-php.de
 */

if(CHROME_PHP !== true) die();

/**
 * @package    CHROME-PHP
 * @subpackage Chrome.Authentication
 */
class Chrome_Authentication_Chain_Cookie extends Chrome_Authentication_Chain_Abstract
{
    protected $_options = array(
                           'cookie_namespace'         => '_AUTH',
                           'cookie_renew_probability' => 10,
                           'cookie_instance'          => null,
                          );

    protected $_model   = null;

    protected $_cookie  = null;

    /**
     * @param Chrome_Model_Abstract $model model which implements the methods of {@see Chrome_Model_Authentication_Cookie}
     * @param Chrome_Cookie_Interface $this->_cookie
     * @param array $options:
     *                  - cookie_namespace: (string) Namespace of the cookie, default: _AUTH
     *                  - cookie_renew_probability: (int) probability (1:x) when the cookie gets renewed, e.g.
     *                                              probability is set to 20, then the probability is 5% = 1/20, default: 10
     *
     * @return Chrome_Authentication_Chain_Cookie
     */
    public function __construct(Chrome_Model_Abstract $model, Chrome_Cookie_Interface $cookie, array $options = array())
    {
        $this->_model         = $model;
        $this->_options       = array_merge($this->_options, $options);

        $this->_cookie        = $cookie;
    }

    protected function _update(Chrome_Authentication_Data_Container_Interface $return)
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

    public function authenticate(Chrome_Authentication_Resource_Interface $resource = null)
    {
        if($resource !== null) {
            $this->_chain->authenticate($resource);
        }

        $data = $this->_cookie->getCookie($this->_options['cookie_namespace']);

        if($data === null or $data == false) {
            return $this->_chain->authenticate($resource);
        }

        $array = $this->_model->decodeCookieString($data);

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
                $container = new Chrome_Authentication_Data_Container(__CLASS__);
                $container->setID($id)->setAutoLogin(true);
                $container->setStatus(Chrome_Authentication_Data_Container_Interface::STATUS_USER);

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

        return new Chrome_Authentication_Data_Container(__CLASS__);
    }

    private function _renewCookie($id)
    {
        $id = (int) $id;

        $token = $this->_model->createNewToken();

        $this->_cookie->setCookie($this->_options['cookie_namespace'], $this->_model->encodeCookieString($id, $token));

        $this->_model->updateIdAndToken($id, $token);
    }

    protected function _createAuthentication(Chrome_Authentication_Create_Resource_Interface $resource)
    {
        // do nothing
    }
}

class Chrome_Model_Authentication_Cookie extends Chrome_Model_Database_Abstract
{
    protected $_options = array(
                           'dbTable'       => 'authenticate',
                          );

    /**
     * @var array $options:
     *                  - dbTable: (string) Name of the db-table, containing the cookie-token and id, default: authenticate
     *
     * @return Chrome_Model_Authentication_Cookie
     */
    public function __construct(Chrome_Context_Model_Interface $model, array $options = array(), Chrome_Database_Composition_Interface $dbComposition = null)
    {
        parent::__construct($model);
        $this->_options = array_merge($options, $this->_options);
        $this->_dbComposition = new Chrome_Database_Composition('model');
        $this->_dbDIComposition = $dbComposition;
    }

    public function encodeCookieString($id, $token)
    {
        $id = (int) $id;

        return base64_encode($id . '.' . $token);
    }

    public function decodeCookieString($string)
    {
        // data is base64 encoded
        $data = base64_decode($string);
        // data structure: ID.TOKEN
        $array = explode('.', $data, 2);
        return array(
                'id'    => (int) $array[0],
                'token' => $array[1],
               );
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

    public function createNewToken()
    {
        $hash  = Chrome_Hash::getInstance();
        return $hash->hash($hash->randomChars(12));
    }
}
