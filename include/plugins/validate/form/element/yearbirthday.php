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
 * @package CHROME-PHP
 * @subpackage Chrome.Validator
 */

namespace Chrome\Validator\Form\Element;

use Chrome\Validator\AbstractValidator;

/**
 * Validates a birthday input, by checking that the input is an given year range, including the boundary ($minYears, $maxYears)
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Validator
 */
class YearBirthdayValidator extends AbstractValidator
{
    protected $_minYears = null;

    protected $_maxYears = null;

    public function __construct($minYears = 5, $maxYears = 120)
    {
        $this->_minYears = new \DateInterval('P' . $minYears . 'Y');
        $this->_maxYears = new \DateInterval('P' . $maxYears . 'Y');
    }

    protected function _validate()
    {
        $this->_namespace = 'plugins/validate/form/element';

        $currentDate = new \DateTime();

        if(!($this->_data instanceof \DateTime) ) {
            $this->_setError('date_not_properly_converted');
        }

        if($this->_data > $currentDate->sub($this->_minYears)) {
            $this->_setError('birthday_date_too_young', array('minYears' => $this->_minYears));
        }

        // we need a new one, since ->sub manipulates $currentDate and DateTimeImmutable is available only in PHP>5.5
        $currentDate = new \DateTime();

        if($this->_data < $currentDate->sub($this->_maxYears)) {
            $this->_setError('birthday_date_too_old', array('maxYears' => $this->_maxYears));
        }
    }
}