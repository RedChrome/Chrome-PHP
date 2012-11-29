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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [29.11.2012 20:53:33] --> $
 * @link       http://chrome-php.de
 */

if(CHROME_PHP !== true) die();

/**
 * @package    CHROME-PHP
 * @subpackage Chrome.Authentication
 * @todo move some code to separate model and refactor methods (split some up)
 */
class Chrome_Authentication_Chain_Cookie extends Chrome_Authentication_Chain_Abstract
{
    protected $_options = array(
                           'cookie_namespace'         => '_AUTH',
                           'dbComposition'            => null,
                           'dbTable'                  => 'authenticate',
                           'cookie_renew_probability' => 10,
                          );

    protected $_dbComposition = null;

    /**
     *
     * @var array $options:
     *                  - cookie_namespace: (string) Namespace of the cookie, default: _AUTH
     *                  - dbComposition: (Chrome_DB_Interface_Abstract) Instance of an db connection, default: null (creates a default connection)
     *                  - dbTable: (string) Name of the db-table, containing the cookie-token and id, default: authenticate
     *                  - cookie_renew_probability: (int) probability (1:x) when the cookie gets renewed, e.g.
     *                                              probability is set to 20, then the probability is 5% = 1/20, default: 10
     *
     * @return Chrome_Authentication_Chain_Cookie
     */
    public function __construct(array $options = array())
    {
        $this->_dbComposition = new Chrome_Database_Composition('model');
        $this->_options = array_merge($this->_options, $options);
    }

    protected function _update(Chrome_Authentication_Data_Container_Interface $return)
    {
        $cookie = Chrome_Cookie::getInstance();

        $data = $cookie->get($this->_options['cookie_namespace']);

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

        $cookie = Chrome_Cookie::getInstance();

        $data = $cookie->get($this->_options['cookie_namespace']);

        if($data === null or $data == false) {
            return $this->_chain->authenticate($resource);
        }

        $array = $this->_decodeCookieString($data);

        $id = $array['id'];
        $token = $array['token'];

        // cookie is invalid
        if(empty($id) or empty($token) or $id == false or $token == false) {
            return $this->_clearCookie();
        }

        // todo: move to model and refactor
        $dbInterface = Chrome_Database_Facade::initInterface($this->_dbComposition, $this->_options['dbComposition']);

        // user has sent an invalid token
        if($token !== ($tokenEscaped = $dbInterface->escape($token))) {
            return $this->_clearCookie();
        }


        $dbInterface = Chrome_Database_Facade::initComposition('model', 'assoc');
        $dbInterface->prepare('authenticationDoesIdAndTokenExist')
            ->execute(array($this->_options['dbTable'], $id, $tokenEscaped));

        $result = $dbInterface->getResult();



        // no entry found
        if($result->isEmpty() === true) {
            return $this->_chain->authenticate($resource);
        } else {

            $container = new Chrome_Authentication_Data_Container();
            $container->setID($id)->setAutoLogin(true);

            return $container;
        }
    }

    protected function _deAuthenticate()
    {
        $cookie = Chrome_Cookie::getInstance();

        $cookie->unsetCookie($this->_options['cookie_namespace']);
    }

    private function _clearCookie()
    {
        $this->_deAuthenticate();

        return new Chrome_Authentication_Data_Container();
    }


    // todo: to model
    private function _renewCookie($id)
    {
        $id = (int) $id;

        $dbInterface = Chrome_Database_Facade::initComposition($this->_dbComposition, $this->_options['dbComposition']);

        // create token
        $hash  = Chrome_Hash::getInstance();
        $token = $hash->hash($hash->randomChars(12));

        // set cookie with data: ID.TOKEN
        //Chrome_Cookie::getInstance()->setCookie($this->_options['cookie_namespace'], base64_encode('1.'.$token));
        Chrome_Cookie::getInstance()->setCookie($this->_options['cookie_namespace'], $this->_encodeCookieString($id, $token));

        // update database
        $dbInterface->prepare('authenticationUpdateTokenById')
            ->execute(array($this->_options['dbTable'], $token, $id));
    }

    private function _encodeCookieString($id, $token)
    {
        $id = (int) $id;

        return base64_encode($id . '.' . $token);
    }

    private function _decodeCookieString($string)
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

    protected function _createAuthentication(Chrome_Authentication_Create_Resource_Interface $resource)
    {
        // do nothing
    }
}
