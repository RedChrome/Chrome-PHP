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
 */

namespace Chrome\Filter\Chain;
/**
 * @package CHROME-PHP
 * @subpackage Chrome.Filter
 */
abstract class AbstractChain
{
    protected $_filters = array();

    /**
     * \Chrome\Filter\Chain\AbstractChain::addFilter()
     *
     * Adds a filter to filter chain
     *
     * @param Chrome_Filter_Abstract $filter
     * @return void
     */
    public function addFilter(\Chrome\Filter\Filter_Interface $filter)
    {
        $this->_filters[] = $filter;
    }

    /**
     * \Chrome\Filter\Chain\AbstractChain::processFilters()
     *
     * run through all filters
     *
     * @param \Chrome\Request\Data_Interface $req
     * @param \Chrome\Response\Response_Interface $res
     * @return void
     */
    public function processFilters(\Chrome\Request\Data_Interface $req, \Chrome\Response\Response_Interface $res)
    {
        // loop through every filter
        foreach($this->_filters as $filter) {
            $filter->execute($req, $res);
        }
    }
}

namespace Chrome\Filter;


/**
 * @package CHROME-PHP
 * @subpackage Chrome.Filter
 */
interface Filter_Interface
{
    /**
     * execute()
     *
     * @param Chrome_Request_Abstract $req
     * @param Chrome_Response_Abstract $res
     * @return void
     */
    public function execute(\Chrome\Request\Data_Interface $req, \Chrome\Response\Response_Interface $res);
}