<?php

// TODO: consider moving these js includes to a template

class Chrome_View_HTML_Bottom_JsIncluder extends Chrome_View
{
    public function render(Chrome_Controller_Interface $controller) {
       return '
<!-- JS -->
<!--<script type="text/javascript" src="'._PUBLIC.'javascript/Framework/dojo.js" djConfig="parseOnLoad:true, isDebug: true"></script>-->
<script src="http://ajax.googleapis.com/ajax/libs/dojo/1.6.1/dojo/dojo.xd.js" type="text/javascript"></script>
<script type="text/javascript" src="'._PUBLIC.'javascript/dojo.js"></script>
<script type="text/javascript" src="'._PUBLIC.'javascript/ganalytics.js"></script>
<script type="text/javascript" src="'._PUBLIC.'javascript/chrome.js"></script>
<script type="text/javascript" src="'._PUBLIC.'javascript/form_utility.js"></script>
'.$this->getJS().'<!-- JS -->';
    }
}

