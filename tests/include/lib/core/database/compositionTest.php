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
        $this->assertNull($requiredComp->merge($requiredComp, $defaultComp)->getFacade());


        $requiredComp = new \Chrome\Database\Composition('\\Chrome\\Database\\Facade\\AbstractFacade');
        $defaultComp = new \Chrome\Database\Composition();

        $this->assertEquals('\\Chrome\\Database\\Facade\\AbstractFacade', $defaultComp->merge($requiredComp, $defaultComp)->getFacade());


        $requiredComp = new \Chrome\Database\Composition();
        $defaultComp = new \Chrome\Database\Composition('\\Chrome\\Database\\Facade\\AbstractFacade');

        $this->assertEquals('\\Chrome\\Database\\Facade\\AbstractFacade', $defaultComp->merge($requiredComp, $defaultComp)->getFacade());


        $requiredComp = new \Chrome\Database\Composition('\\Chrome\\Database\\Facade\\Simple');
        $defaultComp = new \Chrome\Database\Composition('\\Chrome\\Database\\Facade\\AbstractFacade');

        $this->assertEquals('\\Chrome\\Database\\Facade\\Simple', $defaultComp->merge($requiredComp, $defaultComp)->getFacade());

        $requiredComp = new \Chrome\Database\Composition('\\Chrome\\Database\\Facade\\AbstractFacade');
        $defaultComp = new \Chrome\Database\Composition('\\Chrome\\Database\\Facade\\Simple');

        $this->assertEquals('\\Chrome\\Database\\Facade\\Simple', $defaultComp->merge($requiredComp, $defaultComp)->getFacade());
    }

    public function testMergeAdapterWithPreferredAdapter()
    {
        $requiredComp = new \Chrome\Database\Composition();
        $defaultComp = new \Chrome\Database\Composition();
        $this->assertNull($requiredComp->merge($requiredComp, $defaultComp)->getAdapter());

        $requiredComp = new \Chrome\Database\Composition(null, null, '\\Test\\Chrome\\Database\\Adapter\\Dummy');
        $defaultComp = new \Chrome\Database\Composition(null);

        $this->assertEquals('\\Test\\Chrome\\Database\\Adapter\\Dummy', $defaultComp->merge($requiredComp, $defaultComp)->getAdapter());


        $requiredComp = new \Chrome\Database\Composition(null, null, '\\Chrome\\Database\\Adapter\\AbstractAdapter');
        $defaultComp = new \Chrome\Database\Composition(null, null, '\\Test\\Chrome\\Database\\Adapter\\Dummy');

        $this->assertEquals('\\Test\\Chrome\\Database\\Adapter\\Dummy', $defaultComp->merge($requiredComp, $defaultComp)->getAdapter());


        $requiredComp = new \Chrome\Database\Composition(null, null, '\\Test\\Chrome\\Database\\Adapter\\Dummy');
        $defaultComp = new \Chrome\Database\Composition(null, null, '\\Chrome\\Database\\Adapter\\AbstractAdapter');

        $this->assertEquals('\\Test\\Chrome\\Database\\Adapter\\Dummy', $defaultComp->merge($requiredComp, $defaultComp)->getAdapter());
    }

    public function testMergeResultWithPreferredResult()
    {
        $requiredComp = new \Chrome\Database\Composition();
        $defaultComp = new \Chrome\Database\Composition();
        $this->assertNull($requiredComp->merge($requiredComp, $defaultComp)->getResult());

        $requiredComp = new \Chrome\Database\Composition(null, '\\Test\\Chrome\\Database\\Result\\Dummy');
        $defaultComp = new \Chrome\Database\Composition(null, null);

        $this->assertEquals('\\Test\\Chrome\\Database\\Result\\Dummy', $defaultComp->merge($requiredComp, $defaultComp)->getResult());

        $requiredComp = new \Chrome\Database\Composition(null, '\\Test\\Chrome\\Database\\Result\\Dummy');
        $defaultComp = new \Chrome\Database\Composition(null, '\\Chrome\\Database\\Result\\AbstractResult');

        $this->assertEquals('\\Test\\Chrome\\Database\\Result\\Dummy', $defaultComp->merge($requiredComp, $defaultComp)->getResult());

        $requiredComp = new \Chrome\Database\Composition(null, '\\Chrome\\Database\\Result\\AbstractResult');
        $defaultComp = new \Chrome\Database\Composition(null, '\\Test\\Chrome\\Database\\Result\\Dummy');

        $this->assertEquals('\\Test\\Chrome\\Database\\Result\\Dummy', $defaultComp->merge($requiredComp, $defaultComp)->getResult());
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
