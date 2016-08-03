<?php

class FilterInputTest extends PHPUnit_Framework_TestCase
{
    public function testTrim()
    {
        $middleware = new \FewAgency\Reformulator\Middleware\FilterInput();
        $request = new \Illuminate\Http\Request();
        $request['a'] = ' test ';

        $result_request = $middleware->handle($request, function ($request) {
            return $request;
        }, 'trim', 'a');

        $this->assertEquals('test', $result_request->a);
    }

    public function testKeepingOtherField()
    {
        $middleware = new \FewAgency\Reformulator\Middleware\FilterInput();
        $request = new \Illuminate\Http\Request();
        $request['a'] = ' test ';
        $request['b'] = ' test ';

        $result_request = $middleware->handle($request, function ($request) {
            return $request;
        }, 'trim', 'a');

        $this->assertEquals('test', $result_request->a);
        $this->assertEquals(' test ', $result_request->b);
    }

}
