<?php

namespace Chrome\Application\Captcha;

class Application
{
    protected $_appContext = null;

    public function __construct(\Chrome\Application\Application_Interface $app)
    {
        $this->_appContext = $app->getApplicationContext();
    }

    public function execute()
    {
        require_once 'module/default/default.php';

        $renderer = new \Chrome\Module\Captcha\Renderer\Simple();

        $image = $renderer->getImage($this->_appContext);

        $response = $this->_appContext->getResponse();
        if(!($response instanceof \Chrome\Response\HTTPResponse_Interface) ) {
            exit();
        }

        if($image === null)
        {
            $image = file_get_contents(BASE.'public/image/captcha/error.gif');
            $response->setStatus('307 Temporary Redirect');
            $response->addHeader('Location', ROOT_URL.'/public/image/captcha/error.gif');
            $response->flush();
            exit();
        }

        $response->addHeader('Content-type', 'image/png');
        $response->write($image);
        $response->flush();

        exit();
    }
}