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
 * @subpackage Chrome.Modules
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [03.03.2013 15:05:10] --> $
 * @author     Alexander Book
 */
if(CHROME_PHP !== true)
    die();

/**
 * Chrome_Controller_Footer_VarDump
 *
 * @package
 * @author CHROME-PHP
 * @copyright Alexander Book
 * @version 2010
 * @access public
 */
class Chrome_Controller_Footer_VarDump extends Chrome_Controller_Module_Abstract
{
    protected function _execute() {
        $this->_view = new Chrome_View_Footer_VarDump($this);
    }

    public function addViews(Chrome_Design_Renderable_Container_List_Interface $list) {
        // add the view to output
        $list->add($this->_view);
    }
}

/**
 * Chrome_View_Footer_VarDump
 *
 * @package
 * @author CHROME-PHP
 * @copyright Alexander Book
 * @version 2010
 * @access public
 */
class Chrome_View_Footer_VarDump extends Chrome_View_Abstract
{
    public function render(Chrome_Controller_Interface $controller) {

        $data = $this->_controller->getRequestHandler()->getRequestData()->getData();

        $requestData = $this->_controller->getRequestHandler()->getRequestData();

        return $return = '<table align="center" border="1">
            <tr><td>GET</td><td>'.$this->exportArray($data['GET']).'</td></tr>
            <tr><td>POST</td><td>'.$this->exportArray($data['POST']).'</td></tr>


            <tr><td>FILES</td><td>'.$this->exportArray($data['FILES']).'</td></tr>
            <tr><td>COOKIE</td><td>'.$this->exportArray($requestData->getCookie()->getAllCookies()).'</td></tr>
            <tr><td>SESSION</td><td>'.$this->exportArray($requestData->getSession()->get(null)).'</td></tr>
            <tr><td>SERVER</td><td>'.$this->exportArray($data['SERVER']).'</td></tr>

            <tr><td>REQUEST</td><td>'.$this->exportArray($data['REQUEST']).'</td></tr>

            <tr><td>ENV</td><td>'.$this->exportArray($data['ENV']).'</td></tr>

        </table>';
    }

    protected function exportArray(array $array) {

        $return = '<table align="left" border="1" width="100%">';

        foreach($array as $key => $value) {

            if(is_array($value)) {
                $return .= '<tr><td>'.$key.'</td><td>'.$this->exportArray($value).'</td></tr>';
            } else {

                $return .= '<tr><td>'.$key.'</td><td>'.$value.'</td></tr>';

            }
        }

        $return .= '</table>';

        return $return;
    }
}