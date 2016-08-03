<?php

use FewAgency\Reformulator\Testing\ModifiesRequestInputTester;

class ModifiesRequestInputTraitTest extends PHPUnit_Framework_TestCase
{
    public function testSet()
    {
        $request = new \Illuminate\Http\Request();

        $this->assertEmpty($request->all());

        $o = new ModifiesRequestInputTester();
        $o->testSetRequestInput($request, 'a', 'a value');

        $this->assertEquals('a value', $request->a);
    }

    public function testOverwrite()
    {
        $request = new \Illuminate\Http\Request();

        $o = new ModifiesRequestInputTester();
        $o->testSetRequestInput($request, 'a', 'a value');
        $o->testSetRequestInput($request, 'b', 'a second value');

        //Overwrite a value
        $o->testSetRequestInput($request, 'a', 'another value');

        $this->assertEquals('another value', $request->a);
        $this->assertEquals('a second value', $request->b);
    }

    public function testDotSet()
    {
        $request = new \Illuminate\Http\Request();

        $o = new ModifiesRequestInputTester();
        $o->testSetRequestInput($request, 'a.b', 'a value');

        $this->assertInternalType('array', $request->a);
        $this->assertEquals('a value', $request->input('a.b'));
    }

    public function testDotOverwrite()
    {
        $request = new \Illuminate\Http\Request();

        $o = new ModifiesRequestInputTester();
        $o->testSetRequestInput($request, 'a.b', 'a value');
        $o->testSetRequestInput($request, 'a.c', 'a second value');

        //Overwrite a value
        $o->testSetRequestInput($request, 'a.b', 'another value');

        $this->assertEquals('another value', $request->input('a.b'));
        $this->assertEquals('a second value', $request->input('a.c'));
    }

    //TODO: test setting a second-level value on a non-array (first set a to a string, then set a.b to another string)
}
