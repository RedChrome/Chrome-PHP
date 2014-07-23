<?php

/**
 * CHROME-PHP CMS
 *
 * PHP version 5
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
 * @subpackage Chrome.Database
 */

namespace Chrome\Database\Facade;

/**
 * DB Facade for executing multiple sql queries at once
 *
 * @package    CHROME-PHP
 * @subpackage Chrome.Database
 */
class Multiple extends AbstractFacade
{
    /**
     * Default query separator is ';'.PHP_EOL
     *
     * @var string
     */
    protected $_querySeparator = null;

    /**
     * A flag, used for keeping the parameters after calling clear()
     *
     * @var boolean
     */
    private $_keepParameters = false;

    /**
     * Constructor as in AbstractFacade, does only set querySeparator, since PHP does not allow to set concatenated values in class definition.
     *
     * @param \Chrome\Database\Adapter\Adapter_Interface $adapter
     * @param \Chrome\Database\Result\Result_Interface $result
     * @param \Chrome\Database\Registry\Statement_Interface $statementRegistry
     */
    public function __construct(\Chrome\Database\Adapter\Adapter_Interface $adapter, \Chrome\Database\Result\Result_Interface $result, \Chrome\Database\Registry\Statement_Interface $statementRegistry)
    {
        parent::__construct($adapter, $result, $statementRegistry);
        $this->_querySeparator = ';'.PHP_EOL;
    }

    /**
     * Does a clear, but if the keepParameters flag is set, then the parameters
     * wont get cleared.
     *
     * @see \Chrome\Database\Facade\AbstractFacade::clear()
     */
    public function clear()
    {
        if($this->_keepParameters == true) {
            $params = $this->_params;
        } else {
            $params = array();
        }

        parent::clear();

        $this->_params = $params;

        return $this;
    }

    public function setQuerySeparator($separator)
    {
        $this->_querySeparator = $separator;
    }

    public function queries($multipleQueries)
    {
        $this->_keepParameters = true;

        $queries = explode(';' . PHP_EOL, $multipleQueries);

        foreach($queries as $singleQuery)
        {
            if(trim($singleQuery) == '')
            {
                continue;
            }

            $this->query($singleQuery);
            $this->clear();
        }

        $this->_keepParameters = false;
    }
}
