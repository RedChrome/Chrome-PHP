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
 * @subpackage Chrome.View
 */

namespace Chrome\View\Footer;

/**
 * Chrome_View_Footer_VarDump
 *
 * @package    CHROME-PHP
 * @subpackage Chrome.View
 */
class VarDump extends \Chrome\View\AbstractView
{
    protected $_data = array();

    protected $_cookie, $_session;

    public function setData($data, \Chrome\Request\Cookie_Interface $cookie, \Chrome\Request\Session_Interface $session)
    {
        $this->_data = $data;
        $this->_cookie = $cookie;
        $this->_session = $session;
    }

    public function render()
    {
        $data = $this->_data;

        return $return = '<table border="1">
            <tr><td>GET</td><td>'.$this->exportArray($data['GET']).'</td></tr>
            <tr><td>POST</td><td>'.$this->exportArray($data['POST']).'</td></tr>


            <tr><td>FILES</td><td>'.$this->exportArray($data['FILES']).'</td></tr>
            <tr><td>COOKIE</td><td>'.$this->exportArray($this->_cookie->getAllCookies()).'</td></tr>
            <tr><td>SESSION</td><td>'.$this->exportArray($this->_session->get(null)).'</td></tr>
            <tr><td>SERVER</td><td>'.$this->exportArray($data['SERVER']).'</td></tr>

            <tr><td>HEADERS</td><td>'.$this->exportArray($data['HEADERS']).'</td></tr>

        </table>';
    }

    protected function exportArray(array $array)
    {
        $return = '<table border="1">';

        foreach($array as $key => $value) {

            if(is_array($value)) {
                $return .= '<tr><td>'.$key.'</td><td>'.$this->exportArray($value).'</td></tr>';
            } else {

                $return .= '<tr><td>'.$key.'</td><td>'.wordwrap($value, 75, ' ', true).'</td></tr>';
            }
        }

        $return .= '</table>';

        return $return;
    }
}
