<?php

namespace Chrome\Localization;

use Chrome\Localization\Translate_Simple;

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Localization
 */
class Translate_Test_XX extends Translate_Simple
{
    public function load($module)
    {
        // do nothing
    }

    public function get($key, array $params = array())
    {
        return 'XXXXXXXX';
    }
}