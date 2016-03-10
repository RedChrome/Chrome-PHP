<?php

//require_once LIB.'core/authentication/chain/null.php';

require_once 'exception/dummy.php';

require_once 'cookie.php';
require_once 'session.php';
require_once 'resource.php';

require_once LIB.'core/authorisation/authorisation.php';
require_once 'authorisation/simple/model.php';
require_once 'authorisation/adapter.php';

require_once 'authentication/authentication.php';
require_once 'authentication/resource.php';
require_once 'authentication/chain.php';
require_once 'authentication/fail.php';

require_once 'database/connection/dummy.php';
require_once 'database/adapter.php';
require_once 'database/interfaceModel.php';
require_once 'database/result.php';

require_once 'model/dummy.php';
require_once 'model/null.php';

