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
    /**
     * @return string
     */
    public function getHref();

    /**
     * @see getHref()
     */
    public function __toString();
}

class Link implements Link_Interface
{
    protected $_href = '';

    public function __construct($href)
    {
        $this->_href = $href;
    }

    public function getHref()
    {
        return $this->_href;
    }

    public function __toString()
    {
        return $this->_href;
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
     * Returns the uri, which is the reference to all Link_Interface objects
     *
     * This uri is used to calculate the relative path of all links, i.e.
     * all relative paths are relative to this uri.
     *
     * Usually this will be the uri to the requested resource from the client,
     * i.e. from $_SERVER['REQUEST_URI'] and $_SERVER['HTTP_HOST']
     *
     * @return \Psr\Http\Message\UriInterface
     */
    public function getReferenceUri();

    /**
     * Appends the given $path to the reference uri.
     *
     * @todo this should not be here. -> URI Helper?
     * @param string $path
     * @return string
     */
    public function appendPathToReferenceUri($path);

    /*
     * @todo: needed?
     */
    public function diff($serverPath, $clientPath);

    /*
     * @todo: needed?
     */
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
    protected $_resourceHelper = array();

    protected $_referenceUri = null;

    public function __construct(\Psr\Http\Message\UriInterface $referenceUri)
    {
        $this->_referenceUri = $referenceUri;
    }

    public function appendPathToReferenceUri($append)
    {
        $append = ltrim($append, '/');

        $path = $this->_referenceUri->getPath();

        $new = $this->_referenceUri->withPath($path.$append);

        return $new->__toString();
    }

    public function getReferenceUri()
    {
        return $this->_referenceUri;
    }

    public function addResourceHelper(\Chrome\Linker\HTTP\Helper_Interface $helper)
    {
        $this->_resourceHelper[] = $helper;
    }

    public function get(Resource_Interface $resource)
    {
        foreach($this->_resourceHelper as $helper)
        {
            if( ($link = $helper->getLink($resource, $this)) !== null) {
                return $link;
            }
        }

        throw new \Chrome\Exception('Could not get a link to the given resource');
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
    /**
     * @param Resource_Interface $resource
     * @param Linker_Interface $linker
     * @return \Chrome\Linker\Link_Interface|null
     */
    public function getLink(Resource_Interface $resource, Linker_Interface $linker);
}