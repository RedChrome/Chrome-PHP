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
 * @subpackage Chrome.Filter
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [03.03.2013 17:28:13] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true)
    die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Filter
 */
abstract class Chrome_Filter_Chain_Abstract
{
    protected $_filters = array();

    /**
     * Chrome_Filter_Chain_Abstract::__construct()
     *
     * @param string $filterName Name of the filter, e.g. Chrome_Filter_Chain_Main ($filterName must always be unique)
     * @return Chrome_Filter_Chain_Abstract
     */
    public function __construct($filterName = null)
    {
        // if no $filterName given, use the class name
          if($filterName === null) {
              $filterName = get_class($this);
          }
    }

    /**
     * Chrome_Filter_Chain_Abstract::addFilter()
     *
     * Adds a filter to filter chain
     *
     * @param Chrome_Filter_Abstract $filter
     * @return void
     */
    public function addFilter(Chrome_Filter_Interface $filter)
    {
        $this->_filters[] = $filter;
    }

    /**
     * Chrome_Filter_Chain_Abstract::processFilters()
     *
     * run through all filters
     *
     * @param Chrome_Request_Data_Interface $req
     * @param Chrome_Response_Interface $res
     * @return void
     */
    public function processFilters(Chrome_Request_Data_Interface $req, Chrome_Response_Interface $res)
    {
        // loop through every filter
        foreach($this->_filters AS $filter) {
            $filter->execute($req, $res);
        }
    }
}

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Filter
 */
interface Chrome_Filter_Interface
{
    /**
     * execute()
     *
     * @param Chrome_Request_Abstract $req
     * @param Chrome_Response_Abstract $res
     * @return void
     */
    public function execute(Chrome_Request_Data_Interface $req, Chrome_Response_Interface $res);
}