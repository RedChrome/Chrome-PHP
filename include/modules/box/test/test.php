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
       return 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Nunc congue ipsum vestibulum libero. Aenean vitae justo. Nam eget tellus. Etiam convallis, est eu lobortis mattis, lectus tellus tempus felis, a ultricies erat ipsum at metus.';
    }
}
