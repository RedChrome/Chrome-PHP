<?php

namespace Chrome\Module\Captcha\Renderer;

class Simple
{
    public function getImage(\Chrome_Context_Application_Interface $appContext)
    {
        require_once LIB.'captcha/captcha.php';

        $requestData =  $appContext->getRequestHandler()->getRequestData();
        $session = $requestData->getSession();

        $key = $session['CAPTCHA_'.$requestData->getGETData('name')];

        if($key === null) {
            return null;
        }

        if($requestData->getGETData('renew') !== null) {
            $captcha = new \Chrome_Captcha($requestData->getGETData('name'), $applicationContext, array(), array());
            $captcha->renew();
            $key = $session['CAPTCHA_'.$requestData->getGETData('name')];

            if($key === null) {
                return null;
            }
        }

        $length = strlen($key['key']);
        define('SPACE', 32);
        define('TRIM', 10);
        // with a captcha of length 6, this will be 212
        define('X', $length*SPACE + 2*TRIM);
        define('Y', 60);

        $img = imagecreatetruecolor(X, Y);

        $white = imagecolorexact($img, 255, 255, 255);

        imagefill($img, 0, 0, $white);

        for($i=0;$i<$length;++$i) {
            $color = imagecolorexact($img, mt_rand(0, 200), mt_rand(0, 200), mt_rand(0, 200));
            imagefttext($img, 36, mt_rand(-30, 30), SPACE*$i+TRIM, 45+mt_rand(-5, 5), $color, dirname(__FILE__).'/font.ttf', $key['key']{$i});
        }

        ob_start();
        imagepng($img);
        $image = ob_get_contents();
        imagedestroy($img);
        ob_end_clean();

        return $image;
    }
}


