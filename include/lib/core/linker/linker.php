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
 * @subpackage Chrome.Linker
 */
namespace Chrome\Linker;

use \Chrome\Resource\Resource_Interface;

/**
 * Interface to link resources
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Linker
 */
interface Linker_Interface
{
    /**
     * Returns the link to a resource.
     *
     * @param string $resourceId a resource identificator
     * @return string
     */
    public function getLink($resourceId);

    /**
     * Wrapper for {@link Linker_Interface::getLink}, always returns the link relatively
     *
     * @param Resource_Interface $resource
     * @return string
     */
    public function get(Resource_Interface $resource);
}

namespace Chrome\Linker\HTTP;

use \Chrome\Linker\Linker_Interface;
use \Chrome\Resource\Resource_Interface;
use \Chrome\Resource\Model_Interface;
use \Chrome\URI\URI_Interface;

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Linker
 */
class Linker implements Linker_Interface
{
    /**
     * @var boolean
     */
    const DEFAULT_LINK_TYPE = null;

    protected $_absolutePrefix = '';

    protected $_relativePrefix = '';

    protected $_model = null;

    protected $_resourceHelper = array();

    protected $_resourceIdHelper = array();

    public function __construct(URI_Interface $requestedURL, Model_Interface $model)
    {
        $this->setRelative($requestedURL);
        $this->setAbsolute($requestedURL);
        $this->_model = $model;
    }

    public function setAbsolute(URI_Interface $currentURL)
    {
        $authority = $currentURL->getAuthority();
        $host = $authority[URI_Interface::CHROME_URI_AUTHORITY_HOST];

        // do not add http: or https:, since // is for both
        $this->_absolutePrefix = '//'.$host.'/'.trim(ROOT_URL, '/').'/';
    }

    public function setRelative(URI_Interface $currentURL)
    {
        $remainingPath = ltrim(str_replace(ROOT_URL, '', '/'.$currentURL->getPath()), '/');

        $relativeLevel = substr_count($remainingPath, '/');

        $this->_relativePrefix = str_repeat('../', $relativeLevel);
    }

    protected function _noLinkFound($resource, $relative)
    {
        return $this->_absolutePrefix.'404?'.$resource;
    }

    protected function _processUrl($url, $relative)
    {
        if($relative === self::DEFAULT_LINK_TYPE || $relative === true ) {
            return $this->_relativePrefix.ltrim($url, '/');
        } else {
            return $this->_absolutePrefix.ltrim($url, '/');
        }
    }

    public function getLink($resourceId, $relative = self::DEFAULT_LINK_TYPE)
    {
        if(!is_int($resourceId)) {
            throw new \Chrome\Exception('$resourceId must be of type integer, given '.$resourceId);
        }

        foreach($this->_resourceIdHelper as $helper)
        {
            if( ($link = $helper->linkByResourceId($resourceId)) !== false) {

                if(isset($link['skip']) AND $link['skip'] === true) {
                    return $link['link'];
                } else {
                    return $this->_processUrl($link['link'], $relative);
                }
            }
        }

        return $this->_noLinkFound($resource, $relative);
    }

    public function addResourceHelper(\Chrome\Linker\HTTP\Helper_Interface $helper)
    {
        $this->_resourceHelper[] = $helper;
    }

    public function addResourceIdHelper(\Chrome\Linker\HTTP\Helper_Interface $helper)
    {
        $this->_resourceIdHelper[] = $helper;
    }

    public function get(Resource_Interface $resource, $relative = self::DEFAULT_LINK_TYPE)
    {
        foreach($this->_resourceHelper as $helper)
        {
            if( ($link = $helper->linkByResource($resource)) !== false) {

                if(isset($link['skip']) AND $link['skip'] === true) {
                    return $link['link'];
                } else {
                    return $this->_processUrl($link['link'], $relative);
                }
            }
        }

        if($resource->getResourceId() !== null) {
            return $this->getLink($resource->getResourceId(), $relative);
        }

        return $this->_noLinkFound($resource, $relative);
    }
}


namespace Chrome\Linker\HTTP;

use \Chrome\Resource\Resource_Interface;

interface Helper_Interface
{
    /**
     * Returns an array, if the resource could get linked,
     * false if the resource could not get linked.
     *
     * If this methods returns an array, then it is assumed, that
     * the resource can get accessed via this array (URL)
     *
     * Structure of the array
     * array('link' => $link, 'skip' => $boolean),
     * 'skip' symbolizes, whether $link will be returned (if its true) or
     * a relativ/absolute prefix will be added (skip = false)
     *
     * @param Resource_Interface $resource
     * @return array|false
     */
    public function linkByResource(Resource_Interface $resource);

    /**
     * Returns an array, if the resource could get linked,
     * false if the resource could not get linked.
     *
     * If this methods returns an array, then it is assumed, that
     * the resource can get accessed via this array (URL)
     *
     * Structure of the array
     * array('link' => $link, 'skip' => $boolean),
     * 'skip' symbolizes, whether $link will be returned (if its true) or
     * a relativ/absolute prefix will be added (skip = false)
     *
     * @param int $resource
     * @return array|false
     */
    public function linkById($resourceId);
}