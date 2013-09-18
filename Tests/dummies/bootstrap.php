<?php

//require_once LIB.'core/authentication/chain/null.php';

require_once 'exception/dummy.php';

require_once 'cookie.php';
require_once 'session.php';

require_once LIB.'core/authorisation/authorisation.php';
require_once 'authorisation/assert.php';

require_once 'authentication/authentication.php';
require_once 'authentication/resource.php';
require_once 'authentication/chain.php';
require_once 'authentication/fail.php';

require_once 'database/connection/dummy.php';
require_once 'database/adapter.php';
require_once 'database/interfaceModel.php';
require_once 'database/result.php';

require_once 'request/data.php';
require_once 'request/handler.php';

require_once 'model/dummy.php';

require_once 'cache/dummy.php';
