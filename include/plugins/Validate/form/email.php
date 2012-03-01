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
 * @subpackage Chrome.Validator
 * @copyright  Copyright (c) 2008-2009 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://chrome-php.de/license/new-bsd		New BSD License
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [15.08.2011 21:48:24] --> $
 */

if(CHROME_PHP !== true)
	die();

/**
 * Chrome_Validator_Email
 * 
 * @package		CHROME-PHP
 * @subpackage  Chrome.Validator
 */
class Chrome_Validator_Form_Email extends Chrome_Validator
{
    const CHROME_VALIDATOR_EMAIL_MAX_LENGTH = 'EMAILMAXLENGTH',
          CHROME_VALIDATOR_EMAIL_MIN_LENGTH = 'EMAILMINLENGTH';    
    
    const CHROME_VALIDATOR_EMAIL_TOO_SHORT  = 'EMAILTOOSHORT',
          CHROME_VALIDATOR_EMAIL_TOO_LONG   = 'EMAILTOOLONG',
          CHROME_VALIDATOR_EMAIL_NOT_VALID  = 'EMAILNOTVALID';
    
    protected $_options = array(self::CHROME_VALIDATOR_EMAIL_MAX_LENGTH => 200,
                                self::CHROME_VALIDATOR_EMAIL_MIN_LENGTH => 10);
    
	public function __construct() {}
    
	protected function _validate() {

		$len = strlen($this->_data);

        // email too short
		if($len < $this->_options[self::CHROME_VALIDATOR_EMAIL_MIN_LENGTH]) {
			$this->_setError(self::CHROME_VALIDATOR_EMAIL_TOO_SHORT);
		}
        
        // email too long
		if($len > $this->_options[self::CHROME_VALIDATOR_EMAIL_MAX_LENGTH]) {
			$this->_setError(self::CHROME_VALIDATOR_EMAIL_TOO_LONG);
		}
        
        // email not valid
		$regex = '/^([a-z0-9])(([-a-z0-9._])*([a-z0-9]))*\@([a-z0-9])(([a-z0-9-])*([a-z0-9]))+(\.([a-z0-9])([-a-z0-9_-])?([a-z0-9])+)+$/i';
		if(!preg_match($regex, $this->_data)) {
			$this->_setError(self::CHROME_VALIDATOR_EMAIL_NOT_VALID);
		}
	}
}