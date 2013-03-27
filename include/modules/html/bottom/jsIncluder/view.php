<?php

// TODO: consider moving these js includes to a template

class Chrome_View_HTML_Bottom_JsIncluder extends Chrome_View
{
    public function render() {
       return "\n".$this->getJS()."\n";
    }
}

