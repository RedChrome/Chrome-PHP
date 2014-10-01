<?php

namespace Test\Chrome\View\Form\Label;

class DefaultLabelTest extends \PHPUnit_Framework_TestCase
{
    const CLASS_TO_TEST = '\Chrome_View_Form_Label_Default';


    public function testSimpleConstructor()
    {
        $class = self::CLASS_TO_TEST;
        $label = new $class();

        $this->assertNotNull($label);
        $this->assertEquals(\Chrome_View_Form_Label_Interface::LABEL_POSITION_DEFAULT, $label->getPosition());
    }

    public function testAdvancedConstructor()
    {
        $position = \Chrome_View_Form_Label_Interface::LABEL_POSITION_FRONT;

        $labels = array('1' => 'first', 2 => 'second', 'third' => 3);

        $class = self::CLASS_TO_TEST;
        $label = new $class($labels, $position);

        $this->assertEquals($position, $label->getPosition());

        foreach($labels as $key => $value) {
            $this->assertEquals($value, $label->getLabel($key));
        }
    }

    public function testSetGetLabel()
    {
        $labels = array('1st' => 'first', 2 => 'second', 'third' => 3);

        $class = self::CLASS_TO_TEST;
        $label = new $class();

        foreach($labels as $key => $value) {
            $this->assertEquals($key, $label->getLabel($key));
            $label->setLabel($key, $value);
            $this->assertEquals($value, $label->getLabel($key));
        }
    }

}