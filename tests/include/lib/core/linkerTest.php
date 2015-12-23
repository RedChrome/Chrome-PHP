<?php
namespace Test\Chrome\Linker;

use Chrome\Linker\HTTP\Linker;

class LinkerTest extends \Test\Chrome\TestCase
{
    public function testDiff()
    {
        $faker = \Faker\Factory::create();

        $linker = new \Chrome\Linker\HTTP\Linker(new \Chrome\URI\URI());

        $linker->setBasepath('localhost');

        $server = 'http://localhost/dir/to/any/thing/other/resource';
        $client = 'https://localhost/dir/to/some/thing/else/resource';

        $this->assertEquals('any/thing/other/', $linker->diff($server, $client));
        $this->assertEquals('some/thing/else/', $linker->diff($client, $server));

        $this->assertEquals('../../../any/thing/other/', $linker->normalize($server, $client));
        $this->assertEquals('../../../../../asd/', $linker->normalize('localhost/asd/', $server));

    }

}