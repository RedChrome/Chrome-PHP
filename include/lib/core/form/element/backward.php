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
 * @subpackage Chrome.Form
 * @copyright  Copyright (c) 2008-2009 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://chrome-php.de/license/new-bsd		New BSD License
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [11.02.2012 18:09:49] --> $
 */

if(CHROME_PHP !== true)
    die();


/**
 * @todo create Chrome_Form_Element_Button superclass and then this shall be a child of that...
 * then we can remove this unelegant solution!! (IMPORTANT!)
 */

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Form
 */
class Chrome_Form_Element_Backward extends Chrome_Form_Element_Abstract
{
    protected $_defaultOptions = array(self::CHROME_FORM_ELEMENT_IS_REQUIRED => false);

    protected $_int = 0;

    public function isCreated()
    {
        return true;
    }

    public function isValid()
    {
        return true;
    }

    public function isSent()
    {
        // the first check
        // just return true, so that the whole form is sent
        // but at the second check we want to know whether
        // the user has pusehd the "backward" button or not!
        // not really elegant, but it works :|
        //
        // it works because the form caches the isSent call, but only if you check all
        // it does not cache if you want to check a specific element!
        if($this->_int == 0) {
            ++$this->_int;
            return true;
        } else {
            if(($data = $this->getData()) == null) {
                return false;
            } else {
                return true;
            }
        }
    }

    public function create()
    {
        return true;
    }

    public function getData()
    {
        return $this->_form->getSentData($this->_id);
    }

    public function getDecorator()
    {
        if($this->_decorator === null) {
            $this->_decorator = new Chrome_Form_Decorator_Backward_Default($this->_options[self::CHROME_FORM_ELEMENT_DECORATOR_OPTIONS], $this->_options[self::
                CHROME_FORM_ELEMENT_DECORATOR_ATTRIBUTES]);
            $this->_decorator->setFormElement($this);
        }

        return $this->_decorator;
    }

    public function save()
    {

    }
}
