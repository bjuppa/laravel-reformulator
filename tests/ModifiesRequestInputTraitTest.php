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

    public function testOverwritingNonArray()
    {
        $request = new \Illuminate\Http\Request();

        $o = new ModifiesRequestInputTester();
        $o->testSetRequestInput($request, 'a', 'a value');

        $this->assertInternalType('string', $request->a);

        $o->testSetRequestInput($request, 'a.b', 'another value');

        $this->assertInternalType('array', $request->a);
        $this->assertCount(1, $request->a);
        $this->assertEquals('another value', $request->input('a.b'));
    }

    public function testDotOverwritingNonArray()
    {
        $request = new \Illuminate\Http\Request();

        $o = new ModifiesRequestInputTester();
        $o->testSetRequestInput($request, 'a.b', 'a value');

        $o->testSetRequestInput($request, 'a.b.c', 'another value');

        $this->assertEquals('another value', $request->input('a.b.c'));
    }

    public function testUnset()
    {
        $request = new \Illuminate\Http\Request();
        $request->merge(['a' => 'a']);

        $this->assertEquals('a', $request->input('a'));

        $o = new ModifiesRequestInputTester();
        $o->testUnsetRequestInput($request, 'a');

        $this->assertNull($request->input('a'));
    }

    public function testDotUnset()
    {
        $request = new \Illuminate\Http\Request();
        $request->merge(['a' => ['b' => 'c']]);

        $this->assertEquals('c', $request->input('a.b'));

        $o = new ModifiesRequestInputTester();
        $o->testUnsetRequestInput($request, 'a.b');

        $this->assertNull($request->input('a.b'));
        $this->assertInternalType('array', $request->input('a'));
    }
}
