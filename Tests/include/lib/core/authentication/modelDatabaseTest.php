<?php
require_once LIB . 'core/authentication/chain/database.php';
class ModelDatabaseTest extends Chrome_TestCase
{
    protected $_model;

    public function setUp()
    {
        $this->_model = new \Chrome\Model\Authentication\Database($this->_diContainer->get('Chrome\Database\Factory\Factory_Interface'), $this->_diContainer->get('\Chrome\Model\Database\Statement_Interface'));
        $this->_model->setConnection(\Chrome\Database\Registry\Connection_Interface::DEFAULT_CONNECTION);
    }

    /**
     * @dataProvider modelHashesUserPasswordProvider
     */
    public function testModelHashesUserPassword($pw, $salt, $expected)
    {
        $this->assertEquals($expected, $this->_model->hashUserPassword($pw, $salt), 'password was hashed wrong');
    }

    public function modelHashesUserPasswordProvider()
    {
        return array(array('test', 'ahFB319VKaD', 'eec1d7d507bf854c586a64f7a0db6e8a8db088eae96ccbb6'),
                    array('Ophiqu6c', 'Aeviwae6', 'a185d3c245e0d026190970095d454aad6dd060370a9d77c7'));
    }

    /**
     * @dataProvider getPasswordAndSaltByIdentityProvider
     */
    public function testGetPasswordAndSaltByIdentity($id, $pw, $salt)
    {
        if($pw === false)
        {
            $this->assertEquals(false, $this->_model->getPasswordAndSaltByIdentity($id));
        } else
        {
            $this->assertEquals(array('password' => $pw, 'password_salt' => $salt), $this->_model->getPasswordAndSaltByIdentity($id), 'got wrong array with id ' . $id);
        }
    }

    public function getPasswordAndSaltByIdentityProvider()
    {
        return array(array('1', 'eec1d7d507bf854c586a64f7a0db6e8a8db088eae96ccbb6', 'ahFB319VKaD'), array('1234567890', false, false));
    }
}
