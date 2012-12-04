<?php

require_once 'Tests/testsetup.php';


class DatabaseCompositionTest extends PHPUnit_Framework_TestCase
{
    public function testMergeReturnsRequiredCompositionIfOptionsCompIsNull() {

        $requiredComp = new Chrome_Database_Composition();
        $defaultComp = null;

        $this->assertSame($requiredComp, $requiredComp->merge($requiredComp, $defaultComp));
    }


}
