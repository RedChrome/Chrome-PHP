<?php

require_once LIB.'core/authentication/chain/database.php';

class ModelDatabaseTest extends Chrome_TestCase
{
    protected $_model;

    public function setUp()
    {
        $comp = new Chrome_Database_Composition(null, null, null, Chrome_Database_Registry_Connection_Interface::DEFAULT_CONNECTION);

        $this->_model = new Chrome_Model_Authentication_Database($this->_appContext->getModelContext(), array(), $comp);
    }

    /**
     * @dataProvider modelHashesUserPasswordProvider
     */
    public function testModelHashesUserPassword($pw, $salt, $expected)
    {
        $this->assertEquals($expected, $this->_model->hashUserPassword($pw, $salt), 'password was wrong hashed');
    }

    public function modelHashesUserPasswordProvider()
    {
        return array(array(
                'test',
                'ahFB319VKaD',
                '4c85bf07d5d7c1ee8a6edba0f7646a58b6cb6ce9ea88b08d'), array(
                'Ophiqu6c',
                'Aeviwae6',
                '26d0e045c2d385a1ad4a455d09700919c7779d0a3760d06d'));
    }

    /**
     * @dataProvider getPasswordAndSaltByIdentityProvider
     */
    public function testGetPasswordAndSaltByIdentity($id, $pw, $salt)
    {
        if($pw === false) {
            $this->assertEquals(false, $this->_model->getPasswordAndSaltByIdentity($id));
        } else {
            $this->assertEquals(array('password' => $pw, 'password_salt' => $salt), $this->_model->getPasswordAndSaltByIdentity($id), 'got wrong array with id '.$id);
        }
    }

    public function getPasswordAndSaltByIdentityProvider()
    {
        return array(array(
                '2',
                '4c85bf07d5d7c1ee8a6edba0f7646a58b6cb6ce9ea88b08d',
                'ahFB319VKaD'), array(
                '1234567890123',
                false,
                false));
    }
}
