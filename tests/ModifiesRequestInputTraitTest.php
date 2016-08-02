<?php

use FewAgency\Reformulator\Testing\ModifiesRequestInputTester;

class ModifiesRequestInputTraitTest extends PHPUnit_Framework_TestCase
{
    public function testSet()
    {
        $request = new \Illuminate\Http\Request();

        $o = new ModifiesRequestInputTester();
        $o->testSetRequestInput($request, 'a', 'a value');

        $this->assertEquals('a value', $request->a);
    }

    //TODO: test overwriting existing single-level value

    public function testDotSet()
    {
        $request = new \Illuminate\Http\Request();

        $o = new ModifiesRequestInputTester();
        $o->testSetRequestInput($request, 'a.b', 'a value');

        $this->assertInternalType('array', $request->a);
        $this->assertEquals('a value', $request->input('a.b'));
    }

    //TODO: test overwriting existing second-level value

    //TODO: test setting a second-level value on a non-array (first set a to a string, then set a.b to another string)
}
