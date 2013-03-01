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
 * @category   CHROME-PHP
 * @package    CHROME-PHP
 * @subpackage Chrome.Design
 * @author     Alexander Book <alexander.book@gmx.de>
 * @copyright  2012 Chrome - PHP <alexander.book@gmx.de>
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [01.03.2013 17:45:19] --> $
 * @link       http://chrome-php.de
 */

if(CHROME_PHP !== true)
    die();

interface Chrome_Design_Mapper_Interface
{
    public function mapAll(Chrome_Design_Renderable_Container_List_Interface $containerList);

    public function map(Chrome_Design_Renderable_Container_Interface $container);
}

abstract class Chrome_Design_Mapper_Database_Static_Abstract implements Chrome_Design_Mapper_Interface
{
    const DEFAULT_PRIORITY = 0;

    protected $_model = null;

    public function mapAll(Chrome_Design_Renderable_Container_List_Interface $containerList) {

        $containerArray = array();

        foreach($containerList as $key => $container) {

            // container does not want to get mapped
            if($container->shallMap() === false) {
                continue;
            }

            $isPrioritySet = ($container->getPriority() !== null);
            $isPositionSet = ($container->getPosition() !== null);

            // container has already everything set.
            if($isPositionSet AND $isPositionSet) {
                $containerArray[$container->getPosition()][] = $container;
                continue;
            }

            $data = $this->_model->getPositionAndPriorityById($container->getID());

            // couldnt map this container, discard it
            if($data === null OR !isset($data['position']) OR $data['position'] === null) {
                continue;
            }

            // no priority given, use default
            if(!isset($data['priority']) OR $data['priority'] === null) {
                $data['priority'] = self::DEFAULT_PRIORITY;
            }

            if($isPositionSet === false) {
                $container->setPosition($data['position']);
            }

            if($isPrioritySet === false) {
                $container->setPriority($data['priority']);
            }

            // use here $container->getPrio()! not $data['prio']
            $containerArray[$container->getPriority()][] = $container;
        }

        if(count($containerArray) == 0) {
            return;
        }

        // sort, to get the right order to map
        ksort($containerArray);

        foreach($containerArray as $containers) {
            foreach($containers as $container) {
                $this->_map($container);
            }
        }
    }

    public function map(Chrome_Design_Renderable_Container_Interface $container) {
        //TODO: implement
        throw new Chrome_Exception('not implemented yet');
    }

    abstract protected function _map(Chrome_Design_Renderable_Container_Interface $container);
}

class Chrome_Model_Design_Mapper_Static extends Chrome_Model_Database_Abstract
{
    public function __construct() {
        $this->_dbInterface = 'simple';
        $this->_dbResult = 'assoc';
    }

    public function getPositionAndPriorityById($id) {

        $db = $this->_getDBInterface(true);

        $result = $db->query('SELECT `position`, `priority` FROM cpp_design_mapper_static WHERE `view_id` = "?"', array($id) );

        if($result->isEmpty()) {
            return null;
        } else {
            return $result->getNext();
        }
    }
}