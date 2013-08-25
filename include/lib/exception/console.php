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
 * @subpackage Chrome.FrontController
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [22.04.2013 12:07:31] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true) die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.FrontController
 */
class Chrome_Exception_Handler_Console implements Chrome_Exception_Handler_Interface
{
    public function exception(Exception $e)
    {
        echo 'Uncaught exception of type '.get_class($e).' with message:'."\n\n";
        echo $e->getMessage();
        echo "\n\n";
        echo 'Printing stack trace:'."\n";

        $trace = $e->getTrace();

        foreach($trace as $key => $call) {

            if(!isset($call['file']) || !isset($call['line'])) {
                continue;
            }

            if(strlen($call['file']) > 65) {
                $call['file'] = $this->_fileWrap($call['file'], 65, DIRECTORY_SEPARATOR);
            }

            $toEcho = sprintf('#%-2s %-69s :%-4s', $key, $call['file'], $call['line'])."\n|--> ";

            if(isset($call['class'])) {
                $toEcho .= $call['class'].'::';
            }

            if(isset($call['function'])) {
                $toEcho .= $call['function'].'()';
            }

            echo $toEcho;
            echo "\n\n";
        }
    }

    /**
     * Cuts the $file at the beginning as long as the length of $file is longer than $length. Tries not to split
     * any dir up. Result string beginns with '...'.
     *
     * E.g:
     *  _fileWrap("C:\PHP\htdocs\root\include\lib\exception\console.php", 40, '\\')
     *  _fileWrap("Users/anyReallyLongFileNameWhichCannotGetTrimmedCorrectly.php", 24, '/')
     *
     * Output:
     *  ...\include\lib\exception\console.php
     *  ...GetTrimmedCorrectly.php.php
     *
     * Note that the output may be longer than $length. The maximum output length is $length + 3 + strlen($separator).
      * The 3 comes from the "...".
     *
     * @param string $file any file name
     * @param int $length the length to cut the $file
     * @param string $separator the separator to cut off
     * @return the "trimmed" string
     */
    protected function _fileWrap($file, $length, $separator = DIRECTORY_SEPARATOR)
    {
        $paths = explode($separator, $file);

        $reversePaths = array_reverse($paths);

        $lengthSummed = strlen($reversePaths[0]);
        $separatorLength = strlen($separator);

        $output = array();
        $output[] = $reversePaths[0];

        // the filename is too long..
        if($lengthSummed > $length) {
            return '...'.substr($file, $lengthSummed - $length, -1).$file{$lengthSummed - 1};
        }

        for($i = 1; $i < count($reversePaths); ++$i) {

            $currentLength = strlen($reversePaths[$i]);

            if($currentLength + $lengthSummed < $length) {
                $output[] = $reversePaths[$i];
                $lengthSummed += $currentLength + $separatorLength;
            } else {
                $output[] = '...';
                break;
            }
        }

        return implode($separator, array_reverse($output));
    }
}
