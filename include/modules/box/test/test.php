<?php

if( CHROME_PHP !== true ) die();

class Chrome_View_Box_Test extends Chrome_View
{
	public function __construct(  )
	{
        $this->setViewTitle( 'Right Box' );
	}

	public function render()
	{
		return 'box....';
	}
}
