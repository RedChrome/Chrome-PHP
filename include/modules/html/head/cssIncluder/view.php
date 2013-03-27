<?php

class Chrome_View_Html_Head_CssIncluder extends Chrome_View
{
    public function render() {
       return '<title>'.$this->getTitle().'</title>'."\n    "
                .'<meta http-equiv="Content-Type" content="text/html; charset=uft-8" />'."\n    "
                .'<meta name="description" content="{DESCRIPTION}" />'."\n    "
                .'<meta name="keywords" content="{KEYWORDS}" />'."\n    "
                .'<meta name="robots" content="index, follow" />'."\n    "
                .'<meta name="language" content="de" />'."\n\n    "

                .'<!-- CSS -->'."\n    "
                .'<link href="'._PUBLIC.'css/yaml/flexible-grids.css" rel="stylesheet" type="text/css"/>'."\n    "
                .$this->getCSS().'<!-- CSS -->'."\n\n    "

                .'<script type="text/javascript">'."\n    "
                .'  var RecaptchaOptions = {'."\n    "
                .'      theme : \''.Chrome_Config::getConfig('Captcha', 'recaptcha_theme').'\''."\n    "
                .'  };'."\n    "
                .'</script>'."\n";

    }
}
