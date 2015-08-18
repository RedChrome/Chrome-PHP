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
 * @subpackage Chrome.DB
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [27.11.2012 19:54:34] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true)
    die();

/**
 * Chrome_Database_Right_Handler_Interface
 *
 * TODO: modify this interface. deprecated
 *
 *
 * Interface of all Right_Handlers
 * The differenece between addHasRight and _addHasRight is, that _addHasRight gets called before the db adapter generates
 * the sql statement. So _addHasRight can manipulated the statementOptions in order to implement a right check. On the other
 * hand, addHasRight gets called after the sql statement was generated, so it can only manipulate the sqlStatement.
 * Only one of the two methods should implement a manipulation, because both methods are getting called. (Because sometimes it's
 * easier to manipulate a string or to manipulate before the generation is done ;) )
 * If addHasRight should do nothing, then just return $sqlStatement, else just return $sqlStatementOptions (in _addHasRight),
 * so that the db adapter works properly.
 *
 * @package    CHROME-PHP
 * @subpackage Chrome.DB
 */
interface Chrome_Database_Right_Handler_Interface
{
    /**
     * Manipulates the $sqlStatement, so that it checkes whether the user has the right to access it
     *
     * @param string $sqlStatement the generated sql statement
     * @param Chrome_Authorisation_Resource_Interface $resource Resource object
     * @param string $dbColumn column which referes to the resource_id
     * @return string $sqlStatement manipulated
     */
    public function addHasRight($sqlStatement, Chrome_Authorisation_Resource_Interface $resource, $dbColumn);

    /**
     * Manipulates the $sqlStatementOptions, so that the generated sql statement checkes the right
     *
     * You can access the resource-column (as in addHasRight) via $sqlStatementOptions['hasRight']['column']
     *
     * @param array $sqlStatementOptions options from database adapter
     * @param Chrome_Authorisation_Resource_Interface $resource Resource object
     * @return array $sqlStatementOptions manipulated
     */
    public function _addHasRight($sqlStatementOptions, Chrome_Authorisation_Resource_Interface $resource);
}