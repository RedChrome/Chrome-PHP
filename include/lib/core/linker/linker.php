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

interface Link_Interface
{
    public function asRelative();

    public function asAbsolute();
}

class Link implements Link_Interface
{
    public function __construct($basepath, $relative)
    {
        //@todo implement Link.
    }

    public function asRelative()
    {

    }

    public function asAbsolute()
    {

    }
}

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
     * @param Resource_Interface $resource
     * @return Link_Interface
     */
    public function get(Resource_Interface $resource);

    /**
     * Returns the base path. Every linked resource is relative to
     * the base path.
     *
     * @return string
     */
    public function getBasepath();


    public function diff($serverPath, $clientPath);

    public function normalize($norm, $toBeNormalized);
}

namespace Chrome\Linker\HTTP;

use \Chrome\Linker\Linker_Interface;
use \Chrome\Resource\Resource_Interface;
use \Chrome\URI\URI_Interface;

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Linker
 */
class Linker implements Linker_Interface
{
    const PATH_LIMIT = 15;

    protected $_absolutePrefix = '';

    protected $_relativePrefix = '';

    protected $_resourceHelper = array();

    protected $_basepath = '';

    public function __construct(URI_Interface $requestedURL)
    {
        $this->setRelative($requestedURL);
        $this->setAbsolute($requestedURL);
    }

    public function setBasepath($basepath)
    {
        $this->_basepath = $basepath;
    }

    public function getBasepath()
    {
        return $this->_basepath;
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

    public function addResourceHelper(\Chrome\Linker\HTTP\Helper_Interface $helper)
    {
        $this->_resourceHelper[] = $helper;
    }

    public function get(Resource_Interface $resource)
    {
        foreach($this->_resourceHelper as $helper)
        {
            if( ($link = $helper->linkByResource($resource, $this)) !== false) {
                // TODO: FINISH!
                #var_dump($link);
                return $link['link'];
            }
        }

        return $this->_noLinkFound($resource);
    }

    public function diff($server, $client)
    {
        $serverPaths = explode('/', $this->_norm(strtolower($server)), self::PATH_LIMIT);
        $clientPaths = explode('/', $this->_norm(strtolower($client)), self::PATH_LIMIT);

        return implode('/', $this->_diff($serverPaths, $clientPaths));
    }

    public function normalize($norm, $toBeNormalized)
    {
        $normPaths = explode('/', $this->_norm(strtolower($norm)), self::PATH_LIMIT);
        $toBeNormalizedPaths = explode('/', $this->_norm(strtolower($toBeNormalized)), self::PATH_LIMIT);

        $diff = $this->_diff($toBeNormalizedPaths, $normPaths);
        $count = count($diff);

        return str_repeat('../', $count - 1).implode('/', $this->_diff($normPaths, $toBeNormalizedPaths));
    }

    protected function _diff(array $serverPaths, array $clientPaths)
    {
        foreach($serverPaths as $key => $value)
        {
            if(!isset($clientPaths[$key]) OR $value !== $clientPaths[$key]) {
                return array_slice($serverPaths, $key);
            }
        }

        return array();
    }

    protected function _norm($path)
    {
        return $this->_stripResource($this->_stripBasepath($path));
    }

    protected function _stripBasepath($path)
    {
        return substr($path, stripos($path, $this->_basepath));
    }

    protected function _stripResource($path)
    {
        if(substr($path, -1) !== '/') {
            $end = strrpos($path, '/');

            if($end === false) {
                return '/';
            }

            return substr($path, 0, $end+1);
        } else {
            return $path;
        }
    }
}

interface Helper_Interface
{
    public function linkByResource(Resource_Interface $resource, Linker_Interface $linker);

    public function linkById($resourceId);
}