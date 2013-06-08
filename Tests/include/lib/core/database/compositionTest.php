<?php

;
require_once LIB . 'core/database/database.php';
//require_once LIB . 'core/database/interface/simple.php';

class DatabaseCompositionTest extends PHPUnit_Framework_TestCase
{
    public function testMergeReturnsRequiredCompositionIfOptionsCompIsNull()
    {
        $requiredComp = new Chrome_Database_Composition();
        $defaultComp = null;

        $this->assertSame($requiredComp, $requiredComp->merge($requiredComp, $defaultComp));
    }

    public function testMergeInterfaceWithPreferredInterface()
    {
        $requiredComp = new Chrome_Database_Composition();
        $defaultComp = new Chrome_Database_Composition();
        $this->assertNull($requiredComp->merge($requiredComp, $defaultComp)->getInterface());


        $requiredComp = new Chrome_Database_Composition('abstract');
        $defaultComp = new Chrome_Database_Composition();

        $this->assertEquals('Abstract', $defaultComp->merge($requiredComp, $defaultComp)->getInterface());


        $requiredComp = new Chrome_Database_Composition();
        $defaultComp = new Chrome_Database_Composition('abstract');

        $this->assertEquals('Abstract', $defaultComp->merge($requiredComp, $defaultComp)->getInterface());


        $requiredComp = new Chrome_Database_Composition('simple');
        $defaultComp = new Chrome_Database_Composition('abstract');

        $this->assertEquals('Simple', $defaultComp->merge($requiredComp, $defaultComp)->getInterface());

        $requiredComp = new Chrome_Database_Composition('abstract');
        $defaultComp = new Chrome_Database_Composition('simple');

        $this->assertEquals('Simple', $defaultComp->merge($requiredComp, $defaultComp)->getInterface());
    }

    public function testMergeAdapterWithPreferredAdapter()
    {
        $requiredComp = new Chrome_Database_Composition();
        $defaultComp = new Chrome_Database_Composition();
        $this->assertNull($requiredComp->merge($requiredComp, $defaultComp)->getAdapter());

        $requiredComp = new Chrome_Database_Composition(null, null, 'dummy');
        $defaultComp = new Chrome_Database_Composition(null);

        $this->assertEquals('Dummy', $defaultComp->merge($requiredComp, $defaultComp)->getAdapter());


        $requiredComp = new Chrome_Database_Composition(null, null, 'abstract');
        $defaultComp = new Chrome_Database_Composition(null, null, 'dummy');

        $this->assertEquals('Dummy', $defaultComp->merge($requiredComp, $defaultComp)->getAdapter());


        $requiredComp = new Chrome_Database_Composition(null, null, 'dummy');
        $defaultComp = new Chrome_Database_Composition(null, null, 'abstract');

        $this->assertEquals('Dummy', $defaultComp->merge($requiredComp, $defaultComp)->getAdapter());
    }

    public function testMergeResultWithPreferredResult()
    {
        $requiredComp = new Chrome_Database_Composition();
        $defaultComp = new Chrome_Database_Composition();
        $this->assertNull($requiredComp->merge($requiredComp, $defaultComp)->getResult());

        $requiredComp = new Chrome_Database_Composition(null, 'dummy');
        $defaultComp = new Chrome_Database_Composition(null, null);

        $this->assertEquals('Dummy', $defaultComp->merge($requiredComp, $defaultComp)->getResult());

        $requiredComp = new Chrome_Database_Composition(null, 'dummy');
        $defaultComp = new Chrome_Database_Composition(null, 'abstract');

        $this->assertEquals('Dummy', $defaultComp->merge($requiredComp, $defaultComp)->getResult());

        $requiredComp = new Chrome_Database_Composition(null, 'abstract');
        $defaultComp = new Chrome_Database_Composition(null, 'dummy');

        $this->assertEquals('Dummy', $defaultComp->merge($requiredComp, $defaultComp)->getResult());
    }

    public function testMergeConnection() {

        $requiredComp = new Chrome_Database_Composition(null, null, null, null);
        $defaultComp = new Chrome_Database_Composition(null, null, null, 'null');
        $this->assertEquals('null', $requiredComp->merge($requiredComp, $defaultComp)->getConnection());

        $requiredComp = new Chrome_Database_Composition(null, null, null, 'test');
        $defaultComp = new Chrome_Database_Composition(null, null, null, 'null');
        $this->assertEquals('test', $requiredComp->merge($requiredComp, $defaultComp)->getConnection());

        $requiredComp = new Chrome_Database_Composition(null, null, null, 'test2');
        $defaultComp = new Chrome_Database_Composition(null, null, null, null);
        $this->assertEquals('test2', $requiredComp->merge($requiredComp, $defaultComp)->getConnection());

    }


}
