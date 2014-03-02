<?php

class Chrome_View_Html_Head_CssIncluder extends Chrome_View
{
    public function render()
    {
       return '<title>'.$this->getTitle().'</title>'."\n"
                .'<meta http-equiv="content-type" content="text/html; charset=UTF-8">'."\n"
                .'<meta name="description" content="{DESCRIPTION}" />'."\n"
                .'<meta name="keywords" content="{KEYWORDS}" />'."\n"
                .'<meta name="robots" content="index, follow" />'."\n"
                .'<meta name="generator" content="chrome-php" />'."\n\n"

                .'<!-- CSS -->'."\n"
                .$this->getCSS().'<!-- CSS -->'."\n\n"

                .'<script type="text/javascript">'."\n"
                .'  var RecaptchaOptions = {'."\n"
                .'      theme : \''.$this->_viewContext->getConfig()->getConfig('Captcha/Recaptcha', 'recaptcha_theme').'\''."\n"
                .'  };'."\n"
                .'</script>'."\n";

    }
}
