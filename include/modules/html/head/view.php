<?php

class Chrome_Controller_Header_HTML_Head extends Chrome_Controller_Header_Abstract
{
    public function __construct()
    {
        Chrome_Design_Composite_Head::getInstance()->getComposite()->addView(new Chrome_View_Header_HTML_Head($this));
    }
}

class Chrome_View_Header_HTML_Head extends Chrome_View_Abstract
{
    public function render() {
       return '<title>'.$this->getTitle().'</title>
<meta http-equiv="Content-Type" content="text/html; charset=uft-8" />
<meta name="description" content="{DESCRIPTION}" />
<meta name="keywords" content="{KEYWORDS}" />
<meta name="robots" content="index, follow" />
<meta name="language" content="de" />

<!-- CSS -->
'.$this->getCSS().'<!-- CSS -->';

    }
}

$controller = new Chrome_Controller_Header_HTML_Head();