<?php

namespace Test\Chrome\Form\Element;

use Mockery as M;

class DateElementTest extends \PHPUnit_Framework_TestCase
{
    protected $_id = 'date';

    protected function _getForm()
    {
        return M::mock('\Chrome\Form\Form_Interface');
    }

    protected function _getElement($form, $option)
    {
        return new \Chrome\Form\Element\Date($form, $this->_id, $option);
    }

    protected function _getOption()
    {
        return new \Chrome\Form\Option\Element();
    }

    public function testParsesValidDates()
    {
        $form = $this->_getForm();
        $option = $this->_getOption();

        $faker = \Faker\Factory::create();
        $date = $faker->date();
        $form->shouldReceive('getSentData')->andReturn($date);

        $element = $this->_getElement($form, $option);
        $element->create();

        $this->assertTrue($element->isCreated());
        $this->assertTrue($element->isValid());
        $this->assertSame($date, $element->getStorableData());
        $this->assertInstanceOf('\DateTime', $element->getData());
    }

    public function testParsesInValidDates()
    {
        $faker = \Faker\Factory::create();

        $invalid = array($faker->unixTime, $faker->dateTime, $faker->dateTimeAD, $faker->iso8601, $faker->time(), $faker->year(), $faker->randomNumber());

        foreach($invalid as $invalidDate) {
            $this->_parsesInValidDates($invalidDate);
        }
    }

    protected function _parsesInValidDates($invalidDate)
    {
        $form = $this->_getForm();
        $option = $this->_getOption();

        $form->shouldReceive('getSentData')->andReturn($invalidDate);

        $element = $this->_getElement($form, $option);
        $this->assertFalse($element->isValid());
        $this->assertNull($element->getStorableData());
        $this->assertNull($element->getData());
    }
}