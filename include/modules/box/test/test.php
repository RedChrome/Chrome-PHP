<?php

if( CHROME_PHP !== true ) die();

class Chrome_View_Box_Test extends Chrome_View
{
    protected function _setUp()
    {
        $this->setViewTitle( 'Right Box' );
    }

    public function render()
    {
       return 'box....';
    }
}
