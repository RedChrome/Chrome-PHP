<?php

class Chrome_View_HTML_Head_CssIncluder extends Chrome_View
{
    public function render(Chrome_Controller_Interface $controller) {
       return '<title>'.$this->getTitle().'</title>
<meta http-equiv="Content-Type" content="text/html; charset=uft-8" />
<meta name="description" content="{DESCRIPTION}" />
<meta name="keywords" content="{KEYWORDS}" />
<meta name="robots" content="index, follow" />
<meta name="language" content="de" />

<!-- CSS -->
<link href="'._PUBLIC.'css/yaml/flexible-grids.css" rel="stylesheet" type="text/css"/>
'.$this->getCSS().'<!-- CSS -->

<script type="text/javascript">
 var RecaptchaOptions = {
    theme : \''.Chrome_Config::getConfig('Captcha', 'recaptcha_theme').'\'
 };
 </script>
';

    }
}
