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
namespace Chrome\Linker\HTTP;

use \Chrome\Resource\Resource_Interface;
use \Chrome\Linker\Linker_Interface;

class FixedHelper implements Helper_Interface
{
    protected $_resourceModel = null;

    /**
     * The object $resourceModel must have an method findLinkByName.
     *
     * @param \Chrome\Model\Model_Interface $resourceModel
     */
    public function __construct(\Chrome\Model\Model_Interface $resourceModel)
    {
        $this->_resourceModel = $resourceModel;
    }

    public function getLink(Resource_Interface $resource, Linker_Interface $linker)
    {
        if(!($resource instanceof \Chrome\Resource\Fixed_Interface)) {
            return null;
        }

        $link = $this->_resourceModel->findLinkByName($resource->getFixed());

        if($link !== false) {
            return new \Chrome\Linker\Link($linker->appendPathToReferenceUri($link));
        } else {
            return null;
        }
    }
}

namespace Chrome\Resource;

interface Fixed_Interface extends Resource_Interface
{
    public function getFixed();
}

class Fixed extends Resource implements Fixed_Interface
{
    protected $_prefix = 'fixed:';

    public function getFixed()
    {
        return $this->_resourceId;
    }
}
