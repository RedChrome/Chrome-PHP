<?php

namespace Test\Chrome\Validator\Form\Element;

use Mockery as M;

class YearBirthdayTest extends \PHPUnit_Framework_TestCase
{
    public function testAcceptOnlyValidData()
    {
        $validator = new \Chrome\Validator\Form\Element\YearBirthdayValidator(0, 10);

        // data is not valid
        $validator->setData('12.03.'.date('Y'));

        $validator->validate();

        $this->assertFalse($validator->isValid());

        $error = $validator->getError();

        $this->assertNotNull($error);
        $this->assertNotEmpty($error->getMessage());
    }

    public function testIsInValidRange()
    {
        $min = 4;
        $max = 10;

        for($years = $min; $years <= $max; ++$years) {
            $validator = new \Chrome\Validator\Form\Element\YearBirthdayValidator($min, $max);

            $date = new \DateTime();
            $date = $date->sub(new \DateInterval('P'.$years.'Y'));
            $validator->setData($date);
            $validator->validate();
            $this->assertTrue($validator->isValid(), $years.' and '.$validator->getError());
        }
    }

    public function testIsInInvalidRange()
    {
        $min = 15;
        $max = 130;

        $array = array($min - 10, $min-15, $min-1, $max + 10, $max + 130, $max + 1000);

        foreach($array as $years) {
            $validator = new \Chrome\Validator\Form\Element\YearBirthdayValidator($min, $max);

            $date = new \DateTime();
            $date = $date->sub(new \DateInterval('P'.$years.'Y'));
            $validator->setData($date);
            $validator->validate();

            $this->assertFalse($validator->isValid(), $years);

            $error = $validator->getError();

            $this->assertNotNull($error);
            $this->assertNotEmpty($error->getMessage());
        }

    }

}