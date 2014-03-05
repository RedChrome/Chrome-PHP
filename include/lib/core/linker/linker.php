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
     * @var boolean
     */
    const DEFAULT_LINK_TYPE = null;

    /**
     * Returns the link to a resource.
     *
     * If the linker is unable to return a relative link, then an absolute link will be returned.
     *
     * Never expect the linker to return a relative link!
     *
     * @param string $resourceId a resource identificator
     * @param boolean $relative [optional] if true, the link will be returned relatively, but this cannot be guaranteed!
     * @return mixed
     */
    public function getLink($resourceId, $relative = self::DEFAULT_LINK_TYPE);

    /**
     * Wrapper for {@link Linker_Interface::getLink}, always returns the link relatively
     *
     * @param string $resourceId
     */
    public function get(Resource_Interface $resource, $relative = self::DEFAULT_LINK_TYPE);
}

namespace Chrome\Linker\HTML;

use \Chrome\Linker\Linker_Interface;
use \Chrome\Resource\Resource_Interface;
use \Chrome\Resource\Model_Interface;
/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Linker
 */
class Linker implements Linker_Interface
{
    protected $_absolutePrefix = '';

    protected $_relativePrefix = '';

    protected $_model = null;

    public function __construct(\Chrome_URI_Interface $requestedURL, Model_Interface $model)
    {
        $this->setRelative($requestedURL);
        $this->setAbsolute($requestedURL);
        $this->_model = $model;
    }

    public function setAbsolute(\Chrome_URI_Interface $currentURL)
    {
        $authority = $currentURL->getAuthority();
        $host = $authority[\Chrome_URI_Interface::CHROME_URI_AUTHORITY_HOST];

        // do not add http: or https:, since // is for both ;)
        $this->_absolutePrefix = '//'.$host.'/'.trim(ROOT_URL, '/').'/';
    }

    public function setRelative(\Chrome_URI_Interface $currentURL)
    {
        $remainingPath = ltrim(str_replace(ROOT_URL, '', '/'.$currentURL->getPath()), '/');

        $relativeLevel = substr_count($remainingPath, '/');

        $this->_relativePrefix = str_repeat('../', $relativeLevel);
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
            throw new \Chrome_Exception('$resourceId must be of type integer, given '.$resourceId);
        }

        $resource = $this->_model->getResource($resourceId);
        /*
        if(strstr($resourceId, 'public/') !== false) {
            return $this->_processUrl($resourceId, $relative);
        }

        throw new \Chrome_Exception('Could not link to '.$resourceId);
        */
        return $this->get($resource, $relative);

        // TODO: Auto-generated method stub
    }

    public function get(Resource_Interface $resource, $relative = self::DEFAULT_LINK_TYPE)
    {
        if(strstr($resource->getResourceName(), 'public/') !== false) {
            return $this->_processUrl($resource->getResourceName(), $relative);
        }

        throw new \Chrome_Exception('Could not link to '.var_export($resource, true));

        #return $this->getLink($resourceId, true);
    }
}