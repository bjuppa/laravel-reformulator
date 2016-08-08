<?php

class StripRepeatNonWordCharsFromInputTest extends PHPUnit_Framework_TestCase
{
    public function testStripRepeats()
    {
        $middleware = new \FewAgency\Reformulator\Middleware\StripRepeatNonWordCharsFromInput();
        $request = new \Illuminate\Http\Request();
        $request['a'] = '  test--test-test   ';

        $result_request = $middleware->handle($request, function ($request) {
            return $request;
        }, 'a');

        $this->assertEquals(' test-test-test ', $result_request->a);
    }

    public function testTrimAllFields()
    {
        $middleware = new \FewAgency\Reformulator\Middleware\StripRepeatNonWordCharsFromInput();
        $request = new \Illuminate\Http\Request();
        $request['a'] = 'test A  -- test  A';
        $request['b'] = 'test B --  test  B';

        $result_request = $middleware->handle($request, function ($request) {
            return $request;
        });

        $this->assertEquals('test A - test A', $result_request->a);
        $this->assertEquals('test B - test B', $result_request->b);
    }
}
