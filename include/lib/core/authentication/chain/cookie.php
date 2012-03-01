<?php

/**
 * CHROME-PHP CMS
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://chrome-php.de/license/new-bsd
 * If you did not receive a copy of the license AND are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@chrome-php.de so we can send you a copy immediately.
 *
 * @package    CHROME-PHP
 * @subpackage Chrome.Authentication
 * @copyright  Copyright (c) 2008-2009 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://chrome-php.de/license/new-bsd		New BSD License
 * @version   $Id: 0.1 beta <!-- phpDesigner :: Timestamp [23.10.2011 14:02:52] --> $
 */
 
if(CHROME_PHP !== true)
    die();

/**
 * @package    CHROME-PHP
 * @subpackage Chrome.Authentication
 */
class Chrome_Authentication_Chain_Cookie extends Chrome_Authentication_Chain_Abstract
{
    protected $_options = array('cookie_namespace' => '_AUTH', 'dbInterface' => null, 'dbTable' => 'authenticate', 'cookie_renew_probability' => 10);

    /**
     * 
     * @var array $options:
     *                  - cookie_namespace: (string) Namespace of the cookie, default: _AUTH
     *                  - dbInterface: (Chrome_DB_Interface_Abstract) Instance of an db connection, default: null (creates a default connection)
     *                  - dbTable: (string) Name of the db-table, containing the cookie-token and id, default: authenticate
     *                  - cookie_renew_probability: (int) probability (1:x) when the cookie gets renewed, e.g.
     *                                              probability is set to 10, then the probability is 10%, default: 10
     * 
     * @return Chrome_Authentication_Chain_Cookie
     */
    public function __construct(array $options = array())
    {
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

        // data is base64 encoded
        $data = base64_decode($data);
        // data structure: ID.TOKEN
        $array = explode('.', $data, 2);
        $id = (int)$array[0];
        $token = $array[1];

        // cookie is invalid
        if(empty($id) or empty($token) or $id == false or $token == false) {
            return $this->_clearCookie();
        }

        if($this->_options['dbInterface'] !== null) {
            $dbInterface = $this->_options['dbInterface'];
        } else {
            $dbInterface = Chrome_DB_Interface_Factory::factory('interface');
        }

        // could not connect to db, try another chain, maybe it can authenticate
        if($dbInterface === null or $dbInterface == false) {
            return $this->_chain->authenticate($resource);
        }

        // user has sent an invalid token
        if($token !== ($tokenEscaped = $dbInterface->escape($token))) {
            return $this->_clearCookie();
        }

        // search in the db for token and id
        $dbInterface->select(array('id', 'cookie_token'), null, false, 'SQL_NO_CACHE')
                    ->from($this->_options['dbTable'])
                    ->where('id = "'.$id.'" AND cookie_token = "'.$tokenEscaped.'"')
                    ->limit(0, 1)
                    ->execute();
        

        $result = $dbInterface->next();
        
        // no entry found
        if($result == false or empty($result)) {
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

    private function _renewCookie($id)
    {
        $id = (int)$id;

        if($this->_options['dbInterface'] !== null) {
            $dbInterface = $this->_options['dbInterface'];
        } else {
            $dbInterface = Chrome_DB_Interface_Factory::factory('interface');
        }

        // could not connect to db, try another chain, maybe it can authenticate
        if($dbInterface === null or $dbInterface == false) {
            return $this->_chain->authenticate($resource);
        }

        // create token
        $hash = Chrome_Hash::getInstance();
        $token = $hash->hash($hash->randomChars(12));

        // set cookie with data: ID.TOKEN
        Chrome_Cookie::getInstance()->setCookie($this->_options['cookie_namespace'], base64_encode('1.'.$token));

        // update db with this token
        $dbInterface->update($this->_options['dbTable'])
                    ->set(array('cookie_token' => $token))
                    ->where('id = "'.$id.'"')
                    ->limit(0, 1)
                    ->execute();
    }
}
