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
 * @subpackage Chrome.Form
 */

/**
 * Implementation of {@link Chrome_Form_Storage_Interface}
 *
 * This storage uses the implementation of {@link Chrome_Cache_Interface} to store data.
 */
class Chrome_Form_Storage_Cache implements Chrome_Form_Storage_Interface
{
    const FORM_NAMESPACE = 'FORM';

    protected $_cache = null;

    protected $_form = null;

    public function __construct(Chrome_Form_Interface $form, \Chrome\Cache\Cache_Interface $cache)
    {
        $this->_form = $form;
        $this->_cache = $cache;
    }

    protected function _getData()
    {
        return $this->_cache->get(self::FORM_NAMESPACE);
    }

    protected function _setData($data)
    {
        $this->_cache->set(self::FORM_NAMESPACE, $data);
    }

    public function get($elementName)
    {
        $data = $this->_getData();

        if(!is_array($data) OR !isset($data[$this->_form->getID()][$elementName]))
        {
            return null;
        }

        return $data[$this->_form->getID()][$elementName];
    }

    public function set($elementName, $toBeStored)
    {
        $data = $this->_getData();

        if(!is_array($data))
        {
            $data = array();
        }

        $data[$this->_form->getID()][$elementName] = $toBeStored;

        $this->_setData($data);

    }

    public function remove($elementName)
    {
        $data = $this->_getData();

        unset($data[$this->_form->getID()][$elementName]);

        $this->_setData($data);
    }

    public function has($elementName)
    {
        $data = $this->_getData();

        return isset($data[$this->_form->getID()][$elementName]);
    }
}
