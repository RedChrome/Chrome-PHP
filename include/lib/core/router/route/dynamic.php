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
 * @package CHROME-PHP
 * @subpackage Chrome.Router
 */

// TODO: Add a db scheme for \Chrome\Model\Route\DynamicRoute\Database::getResourcesAsArray
// TODO: Add regex support for single path, e.g. news/show/id/regex:|(\d)*?|/action/remove or something like that,
//          but in fact, thats some kind of input validation... dont know if needed

namespace Chrome\Router\Route;

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Router
 */
class DynamicRoute extends AbstractRoute
{
    /**
     * How much slashes "/" are allowed in a url?
     *
     * @var int
     */
    const CHROME_ROUTE_REGEX_MAX_LEVEL = 30;

    private $_resources = array();

    protected $_dataGet = array();

    private $_previousKey = null;

    private $_resourceID = null;

    public function match(\Psr\Http\Message\ServerRequestInterface $request, $normalizedPath)
    {


        $array = explode('/', $request->getUri()->getPath(), self::CHROME_ROUTE_REGEX_MAX_LEVEL);

        if(count($array) <= 1)
        {
            return false;
        }

        $this->_resources = $this->_model->getResourcesAsArray();

        $params = array();

        if($this->_urlMatches($array, $this->_resources, $params) !== false)
        {
            $resource = $this->_model->getResourceByID($this->_resourceID);

            if($resource === null or $resource == false)
            {
                throw new \Chrome\Exception('Path matched, but resource wasn\'t found!', 2004);
            }

            $this->_result = new \Chrome\Router\Result();
            $this->_result->setClass($resource['class']);
            $this->_result->setRequest($this->_applyGetAndPost($request, $resource['GET'], $resource['POST']));

            return true;
        } else
        {
            return false;
        }
    }

    protected function _urlMatches($urlAsArray, $availableResolutions, &$urlParams, $previousSegment = '')
    {
        $currentUrlSegment = array_shift($urlAsArray);//$urlAsArray[0];

        if($currentUrlSegment === null)
        {
            $currentUrlSegment = '';
        }

        if(!is_array($availableResolutions)) {
            if(is_int($availableResolutions)) {
                $this->_resourceID = $availableResolutions;
                return true;
            }
            // error, $availableResolutions is malformed
            return false;
        }

        foreach($availableResolutions as $urlSegment => $nextResoultions)
        {
            // matched
            if($urlSegment === $currentUrlSegment)
            {
                return $this->_urlMatches($urlAsArray, $nextResoultions, $urlParams, $currentUrlSegment);
            }

            if($urlSegment === '*')
            {
                if(!empty($currentUrlSegment) )
                {
                    $urlParams[$previousSegment] = $currentUrlSegment;
                    return $this->_urlMatches($urlAsArray, $nextResoultions, $urlParams, $currentUrlSegment);
                }

                // expected any input, but got an empty input -> not valid
                return false;
            }
        }

        return false;
    }

    private function _exist($array, array $resource)
    {
        foreach($resource as $key => $value)
        {

            // found if:
            // 1. the keys are matching
            // 2. the key(from resource) is * (so every input is allowed, but there must be an input)
            // 3. the input array is empty, but the resource isnt and there is a key ""
            if(isset($array[0]) and $key === $array[0] or ($key === '*' and $array[0] != '') or (count($array) === 0 and array_key_exists('', $resource)))
            {

                if($key === '*')
                {
                    $this->_dataGet[$this->_previousKey] = $array[0];
                }

                if(is_array($value))
                {
                    $this->_previousKey = array_shift($array);

                    return $this->_exist($array, $resource[$key]);
                } else
                {
                    $this->_resourceID = $value;
                    return true;
                }
            } else
            {
                continue;
            }
        }
        return false;
    }
}

namespace Chrome\Model\Route\DynamicRoute;

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Router
 */
class Cache extends \Chrome\Model\AbstractCache
{
    public function getResourceByID($id)
    {
        if(($return = $this->_cache->get('getResource_' . $id)) === null)
        {

            $return = $this->_decorable->getResourceByID($id);

            if($return !== false)
            {
                $this->_cache->set('getResource_' . $id, $return);
            }
        }

        return $return;
    }
}

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Router
 */
class Database extends \Chrome\Model\AbstractDatabaseStatement
{
    public function getResourcesAsArray()
    {
        // just an example
        return array(
                    'site' => array(
                                    'news' => array(
                                                    'id' => array(
                                                                '*' => array('' => 1,// '' is an alias for 'show'
                                                                             'show' => 1,
                                                                             'update' => 2)
                                                                            ))));
    }

    public function getResourceByID($id)
    {
        $id = (int) $id;

        $db = $this->_getDBInterface();

        $result = $db->loadQuery('routeDynamicGetResourceById')->execute(array($id));

        $row = $result->getNext();

        // translate key=value,key2=value2 into an array {key => value, key2=>value2}
        $get = array();
        if(!empty($row['GET']))
        {

            // input is like key=value,key2=value2,..
            $keyValuePairs = explode(',', $row['GET']);
            foreach($keyValuePairs as $keyValuePair)
            {

                $keyValue = explode('=', $keyValuePair);
                $get[$keyValue[0]] = $keyValue[1];
            }
        }
        $row['GET'] = $get;

        $post = array();
        if(!empty($row['POST']))
        {

            // input is like key=value,key2=value2,..
            $keyValuePairs = explode(',', $row['POST']);
            foreach($keyValuePairs as $keyValuePair)
            {

                $keyValue = explode('=', $keyValuePair);
                $post[$keyValue[0]] = $keyValue[1];
            }
        }
        $row['POST'] = $post;

        return $row;
    }
}
