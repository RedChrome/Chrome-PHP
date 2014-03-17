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
 * @subpackage Chrome.Exception
 */

namespace Chrome\Exception\Handler;

use \Chrome\Exception\Handler_Interface;

/**
 * @package CHROME-PHP
 */
class DefaultHandler implements Handler_Interface
{
    public function exception(\Exception $e)
    {
        echo '<h1>Uncaught Exception of type '.get_class($e).' </h1>';
        echo '<h3>'.$e->getMessage().'</h3>';
        echo '<h4>Caused by '.$e->getFile().'('.$e->getLine().')<br></h4>Call Stack<br>';

        echo $this->_printExceptionTrace($e);

        die();
    }

    protected function _printExceptionTrace(\Exception $e)
    {

        $trace = $e->getTrace();

        $return = '';

        foreach($trace as $key => $value) {

            $return .= sprintf('#%1$02d: ', $key+1);

            if(isset($value['file'])) {
                if(isset($value['line'])) {
                    $return .= $value['file'].'('.$value['line'].'): ';
                } else {
                    $return .= $value['file'].'(): ';
                }
            }

            if(!isset($value['class'])) {
                $return .= $value['function'].$this->_getArgs($value['args']);

            } else {
                $return .= $value['class'].$value['type'].$value['function'];

                $return .= $this->_getArgs($value['args']);
            }
            $return .= '<br>'."\n";
        }

        if($e instanceof \Chrome\Exception) {

            $prev = $e->getPrevious();

            if( ($prev instanceof Exception) AND !($prev instanceof \Chrome\Exception) ) {

                $return .= '<h4>...caused by '.$prev->getFile().'('.$prev->getLine().')</h4>';

                $return .= $this->_printExceptionTrace($prev);
            }
        }

        return $return;
    }

    protected function _getArgs($args)
    {
        if($args === null or $args === array()) {
            return '(<i>void</i>)';
        }

        $return = '(';

        foreach($args as $key => $value) {
            if(is_int($key)) {
                if($key == 0) {
                    $return .= ''.$this->_getValue($value);
                    continue;
                }
                $return .= ', '.$this->_getValue($value);

            } else {
                $return .= ' '.$key.' => '.$this->_getValue($value);
            }
        }
        $return .= ')';
        return $return;
    }

    protected function _getValue($value)
    {
        if(is_string($value)) {
            if(strlen($value) > 117) {
                return '"'.substr($value, 0, 117).'..."';
            }
            return '"'.$value.'"';

        } else
            if(is_object($value)) {
                return 'Object(<i>'.get_class($value).'</i>)';
            } else
                if(is_array($value)) {

                    $return = '<i>Array</i>( ';

                    if(count($value) !== 0) {
                        foreach($value as $key => $value) {
                            $return .= $key.' => '.$this->_getValue($value).', ';
                        }
                    } else {
                        $return .= '<i>void</i>  ';
                    }

                    return substr($return, 0, strlen($return) - 2).' )';

                } else
                    if($value !== null) {
                        if(is_bool($value)) {
                           return ($value === true) ? 'true' : 'false';
                        }
                        return gettype($value).'('.$value.')';
                    }

        return '<i>null</i>';
    }
}
