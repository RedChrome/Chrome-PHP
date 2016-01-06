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
 * @subpackage Chrome.Core
 */

/**
 * load attribute interfaces
 */
require_once 'misc/attribute.php';

/**
 * load registry for objects
 */
require_once 'registry/object.php';

/**
 * load Logger
 */
require_once 'log/log.php';

/**
 * load error & exception classes
 */
require_once 'error/error.php';

/**
 * load mime class to get mime info of a file
 */
require_once 'mime.php';

/**
 * load date class
 */
require_once 'date.php';

/**
 * load Chrome_File, Chrome_Directory classes, for easy file and dir manipulation
 */
require_once 'file/file.php';
require_once 'file/directory.php';

/**
 * load Chrome_Hash for easy hashing
 */
require_once 'hash/hash.php';

/**
 * load database
 */
require_once 'database/database.php';

/**
 * load cache classes
 */
require_once 'cache/cache.php';

/**
 * load model classes
 */
require_once 'model/model.php';

/**
 * load config class
 */
require_once 'config/config.php';

/**
 * load view helper
 */
require_once 'view/plugin.php';

/**
 * load design classes, needed for view
 */
require_once 'design/design.php';

/**
 * load view
 */
require_once 'view/view.php';

/**
 * load controller
 */
require_once 'controller/controller.php';

/**
 * load request factory
 */
require_once 'request/request.php';

/**
 * load response factory
 */
require_once 'response/response.php';

/**
 * load URI class
 */
require_once 'uri.php';

/**
 * load filter class
 */
require_once 'filter/filter.php';

/**
 * load router classes
 */
require_once 'router/router.php';

/**
 * load validator class
 */
require_once 'validator/validator.php';

/**
 * load language class
 */
require_once 'localization/localization.php';

/**
 * load require class to load other files
 */
require_once 'classloader/classloader.php';

/**
 * load application interfaces
 */
require_once 'application.php';

/**
 * load interactor interface
 */
require_once 'interactor/result.php';

/**
 * load resource interface
 */
require_once 'resource.php';

/**
 * load dependency injection container
 */
require_once 'dependency_injection/container.php';

