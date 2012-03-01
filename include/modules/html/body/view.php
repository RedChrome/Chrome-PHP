<?php

class Chrome_Controller_Header_HTML_JS extends Chrome_Controller_Header_Abstract
{
    public function __construct()
    {
        Chrome_Design_Composite_Body::getInstance()->getPostComposite()->addView(new Chrome_View_Header_HTML_JS($this));
    }
}

class Chrome_View_Header_HTML_JS extends Chrome_View_Abstract
{
    public function render() {
       return '
<!-- JS -->
<!--<script type="text/javascript" src="'._PUBLIC.'javascript/Framework/dojo.js" djConfig="parseOnLoad:true, isDebug: true"></script>-->
<script src="http://ajax.googleapis.com/ajax/libs/dojo/1.6.1/dojo/dojo.xd.js" type="text/javascript"></script>
<script type="text/javascript" src="'._PUBLIC.'javascript/dojo.js"></script>
<script type="text/javascript" src="'._PUBLIC.'javascript/ganalytics.js"></script>
<script type="text/javascript" src="'._PUBLIC.'javascript/chrome.js"></script>
'.$this->getJS().'<!-- JS -->';
    }
}

$controller = new Chrome_Controller_Header_HTML_JS();