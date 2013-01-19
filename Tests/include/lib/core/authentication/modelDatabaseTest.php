<?php
require_once 'Tests/testsetupmodules.php';
require_once LIB.'core/authentication/chain/database.php';

class ModelDatabaseTest extends PHPUnit_Framework_TestCase
{
    protected $_model;

    // index => array(password, salt, expectedHashedPW)
    protected $_passwordSet = array(array('test', 'ahFB319VKaD', '4c85bf07d5d7c1ee8a6edba0f7646a58b6cb6ce9ea88b08d'),
                       array('Ophiqu6c', 'Aeviwae6', '26d0e045c2d385a1ad4a455d09700919c7779d0a3760d06d'));

    // index => array(id, password, salt)
    protected $_idSet = array(array('2', '4c85bf07d5d7c1ee8a6edba0f7646a58b6cb6ce9ea88b08d', 'ahFB319VKaD'),
                              array('1234567890123', false, false));

    public function setUp()
    {
        $comp = new Chrome_Database_Composition(null, null, null, Chrome_Database_Facade::getDefaultConnection());

        $this->_model = new Chrome_Model_Authentication_Database(array(), $comp);
    }

    public function testModelHashesUserPassword() {


        foreach($this->_passwordSet as $key => $triple) {

            $pw = $triple[0];
            $salt = $triple[1];
            $expected = $triple[2];


            $this->assertEquals($expected, $this->_model->hashUserPassword($pw, $salt), 'password was wrong hashed');
        }
    }

    public function testGetPasswordAndSaltByIdentity() {

        foreach($this->_idSet as $key => $triple) {

            $id = $triple[0];
            $pw = $triple[1];
            $salt = $triple[2];

            if($pw === false) {
                $this->assertEquals(false, $this->_model->getPasswordAndSaltByIdentity($id));
            } else {
                $this->assertEquals(array('password' => $pw, 'password_salt' => $salt), $this->_model->getPasswordAndSaltByIdentity($id));
            }
        }

    }
}