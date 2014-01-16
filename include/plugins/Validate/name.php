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
 * @subpackage Chrome.Validator
 */

/**
 * Chrome_Validator_Name
 *
 * @package		CHROME-PHP
 * @subpackage  Chrome.Validator
 * @todo 		Check if nickname is forbidden? -> This will be an extra validator, see email_blacklist
 */
class Chrome_Validator_Name extends Chrome_Validator_Configurable
{
    protected function _validate()
    {
        // nickname contains only a-z, 0-9 AND "-", "_"
        if(preg_match('#[^a-z_\-0-9]#i', $this->_data)) {
            $this->_setError('name_contains_forbidden_chars');
        }

        $length = strlen($this->_data);

        if($length < $this->_config->getConfig('user', 'name_min_length')) {
            $this->_setError('name_too_short');
        }
        if($length > $this->_config->getConfig('user', 'name_max_length')) {
            $this->_setError('name_too_long');
        }
    }
}