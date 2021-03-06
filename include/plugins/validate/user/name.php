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
 * @package CHROME-PHP
 * @subpackage Chrome.Module.User
 */
namespace Chrome\Validator\User;

class NameValidator extends \Chrome\Validator\Composer\AbstractComposer
{
    protected $_uniqueVal = null;

    public function __construct(UniqueNameValidator $validator)
    {
        $this->_uniqueVal = $validator;
    }

    protected function _getValidator()
    {
        $and = new \Chrome\Validator\Composition\AndComposition();
        $and->addValidator(new NicknameValidator());
        $and->addValidator($this->_uniqueVal);

        return $and;
    }
}
