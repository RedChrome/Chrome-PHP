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

namespace Chrome\Validator\Form\Element;

use Chrome\Validator\AbstractValidator;

/**
 * A validator which ensures that, if the user must only send one input, he actually does that.
 *
 * @package		CHROME-PHP
 * @subpackage  Chrome.Validator
 */
class SelectMultipleValidator extends AbstractValidator
{
    protected $_option = null;

    public function __construct(\Chrome\Form\Option\MultipleElement_Interface $option)
    {
        $this->_option = $option;
    }

    protected function _validate()
    {
        $this->_namespace = 'plugins/validate/form/element';

        // user can only select one item, but has sent more than one item
        if($this->_option->getSelectMultiple() === false and is_array($this->_data) and count($this->_data) > 1)
        {
            $this->_setError('cannot_select_more_than_one_item');
            return false;
        }

        return true;

    }
}