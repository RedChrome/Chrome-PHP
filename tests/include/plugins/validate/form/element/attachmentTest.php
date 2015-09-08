<?php

namespace Test\Chrome\Validator\Form\Element;

use Mockery as M;
use Chrome\Validator\Composition_Interface;

class AttachmentTest extends \PHPUnit_Framework_TestCase
{
    protected function _getValidator(\Chrome\Form\Option\AttachableElement_Interface $option, Composition_Interface $composition)
    {
        return new \Chrome\Validator\Form\Element\AttachmentValidator($option, $composition);
    }

    protected function _getAttachmentOption()
    {
        return M::mock('\Chrome\Form\Option\AttachableElement_Interface');
    }

    protected function _getAttachment()
    {
        return M::mock('\Chrome\Form\Element\BasicElement_Interface');
    }

    protected function _getComposition()
    {
        return M::mock('\Chrome\Validator\Composition_Interface');
    }

    public function testValidator()
    {
        $data = 123;

        $option = $this->_getAttachmentOption();
        $option->shouldReceive('getAttachments')->withNoArgs()->once()->andReturn(array($this->_getAttachment(), $this->_getAttachment(), $this->_getAttachment(), $this->_getAttachment()));

        $composition = $this->_getComposition()->shouldIgnoreMissing(null);
        $composition->shouldReceive('addValidator')->times(4)->andReturn(null);
        $composition->shouldReceive('setData')->once()->with($data)->andReturn(null);
        $composition->shouldReceive('isValid')->once()->andReturn(false);

        $validator = $this->_getValidator($option, $composition);
        $validator->setData($data);
        $validator->validate();

        $this->assertFalse($validator->isValid());
        $this->assertNull($validator->getError());
    }
}