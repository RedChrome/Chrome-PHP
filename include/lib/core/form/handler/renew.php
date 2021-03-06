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
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@chrome-php.de so we can send you a copy immediately.
 *
 * @package    CHROME-PHP
 * @subpackage Chrome.Form
 */

namespace Chrome\Form\Handler;

/**
 * This should be set as ReceivingHandler. It renews the form, if isSent() returns false.
 * This should be used to renew the form token. In order to be consistent with the form, it should only
 * renew the form, if the user has sent nothing to the server. This is important, because if we'd renew the form
 * and the user would have sent sth., then the form is invalid, because we replaced the token by a new one.
 * => the tokens wouldn't match => form invalid.
 *
 * USE THIS ONLY AS RECEIVING HANDLER!!
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Form
 */
class Renew implements \Chrome\Form\Handler\Handler_Interface
{
    protected $_renewProbability = 100;

    /**
     * if no renewProbability is given, then the form is renewed every time
     *
     * @param mixed $renewProbability [optional] given as double[0.0-1.0]: in percentage, given as int[0-100]: number of hits within 100 requests
     */
    public function __construct($renewProbability = null)
    {
        if($renewProbability !== null) {
            if(is_double($renewProbability)) {
                $this->_renewProbability = (int) ($renewProbability*100);
            } else if(is_int($renewProbability)) {
                $this->_renewProbability = $renewProbability;
            }
        }
    }

    public function is(\Chrome\Form\Form_Interface $form)
    {
        // do nothing
    }

    public function isNot(\Chrome\Form\Form_Interface $form)
    {
        if(mt_rand(1, 100) <= $this->_renewProbability) {
            // renew, if isSent() returns false
            $form->renew();
        }
    }
}