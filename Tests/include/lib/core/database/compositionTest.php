<?php

;
require_once LIB . 'core/database/database.php';
//require_once LIB . 'core/database/interface/simple.php';

class DatabaseCompositionTest extends PHPUnit_Framework_TestCase
{
    public function testMergeReturnsRequiredCompositionIfOptionsCompIsNull()
    {
        $requiredComp = new \Chrome\Database\Composition();
        $defaultComp = null;

        $this->assertSame($requiredComp, $requiredComp->merge($requiredComp, $defaultComp));
    }

    public function testMergeInterfaceWithPreferredInterface()
    {
        $requiredComp = new \Chrome\Database\Composition();
        $defaultComp = new \Chrome\Database\Composition();
        $this->assertNull($requiredComp->merge($requiredComp, $defaultComp)->getInterface());


        $requiredComp = new \Chrome\Database\Composition('abstract');
        $defaultComp = new \Chrome\Database\Composition();

        $this->assertEquals('Abstract', $defaultComp->merge($requiredComp, $defaultComp)->getInterface());


        $requiredComp = new \Chrome\Database\Composition();
        $defaultComp = new \Chrome\Database\Composition('abstract');

        $this->assertEquals('Abstract', $defaultComp->merge($requiredComp, $defaultComp)->getInterface());


        $requiredComp = new \Chrome\Database\Composition('simple');
        $defaultComp = new \Chrome\Database\Composition('abstract');

        $this->assertEquals('Simple', $defaultComp->merge($requiredComp, $defaultComp)->getInterface());

        $requiredComp = new \Chrome\Database\Composition('abstract');
        $defaultComp = new \Chrome\Database\Composition('simple');

        $this->assertEquals('Simple', $defaultComp->merge($requiredComp, $defaultComp)->getInterface());
    }

    public function testMergeAdapterWithPreferredAdapter()
    {
        $requiredComp = new \Chrome\Database\Composition();
        $defaultComp = new \Chrome\Database\Composition();
        $this->assertNull($requiredComp->merge($requiredComp, $defaultComp)->getAdapter());

        $requiredComp = new \Chrome\Database\Composition(null, null, 'dummy');
        $defaultComp = new \Chrome\Database\Composition(null);

        $this->assertEquals('Dummy', $defaultComp->merge($requiredComp, $defaultComp)->getAdapter());


        $requiredComp = new \Chrome\Database\Composition(null, null, 'abstract');
        $defaultComp = new \Chrome\Database\Composition(null, null, 'dummy');

        $this->assertEquals('Dummy', $defaultComp->merge($requiredComp, $defaultComp)->getAdapter());


        $requiredComp = new \Chrome\Database\Composition(null, null, 'dummy');
        $defaultComp = new \Chrome\Database\Composition(null, null, 'abstract');

        $this->assertEquals('Dummy', $defaultComp->merge($requiredComp, $defaultComp)->getAdapter());
    }

    public function testMergeResultWithPreferredResult()
    {
        $requiredComp = new \Chrome\Database\Composition();
        $defaultComp = new \Chrome\Database\Composition();
        $this->assertNull($requiredComp->merge($requiredComp, $defaultComp)->getResult());

        $requiredComp = new \Chrome\Database\Composition(null, 'dummy');
        $defaultComp = new \Chrome\Database\Composition(null, null);

        $this->assertEquals('Dummy', $defaultComp->merge($requiredComp, $defaultComp)->getResult());

        $requiredComp = new \Chrome\Database\Composition(null, 'dummy');
        $defaultComp = new \Chrome\Database\Composition(null, 'abstract');

        $this->assertEquals('Dummy', $defaultComp->merge($requiredComp, $defaultComp)->getResult());

        $requiredComp = new \Chrome\Database\Composition(null, 'abstract');
        $defaultComp = new \Chrome\Database\Composition(null, 'dummy');

        $this->assertEquals('Dummy', $defaultComp->merge($requiredComp, $defaultComp)->getResult());
    }

    public function testMergeConnection() {

        $requiredComp = new \Chrome\Database\Composition(null, null, null, null);
        $defaultComp = new \Chrome\Database\Composition(null, null, null, 'null');
        $this->assertEquals('null', $requiredComp->merge($requiredComp, $defaultComp)->getConnection());

        $requiredComp = new \Chrome\Database\Composition(null, null, null, 'test');
        $defaultComp = new \Chrome\Database\Composition(null, null, null, 'null');
        $this->assertEquals('test', $requiredComp->merge($requiredComp, $defaultComp)->getConnection());

        $requiredComp = new \Chrome\Database\Composition(null, null, null, 'test2');
        $defaultComp = new \Chrome\Database\Composition(null, null, null, null);
        $this->assertEquals('test2', $requiredComp->merge($requiredComp, $defaultComp)->getConnection());

    }


}
