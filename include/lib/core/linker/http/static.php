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

require_once 'staticInterface.php';

use \Chrome\Resource\Resource_Interface;
use \Chrome\Linker\HTTP\Model\Static_Interface;

class StaticHelper implements Helper_Interface
{
    protected $_resourceModel = null;

    public function __construct(Static_Interface $resourceModel)
    {
        $this->_resourceModel = $resourceModel;
    }

    public function linkByResource(Resource_Interface $resource)
    {
        if(strpos($resource->getResourceName(), 'static:') === 0) {

            $resourceClone = clone $resource;

            // remove "static:"
            // strlen("static:") = 7
            $resourceClone->setResourceName(substr($resourceClone->getResourceName(), 7));

            $link = $this->_resourceModel->getLinkByResource($resourceClone);

            if(is_string($link)) {
                return array('link' => $link);
            }

            return false;

        } else {
            return false;
        }
    }

    public function linkById($resourceId)
    {
        return false;
    }
}
