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
        $request['a'] = ' test A ';
        $request['b'] = ' test B ';

        $result_request = $middleware->handle($request, function ($request) {
            return $request;
        }, 'trim', 'a');

        $this->assertEquals('test A', $result_request->a);
        $this->assertEquals(' test B ', $result_request->b);
    }

    public function testTrimArray()
    {
        $middleware = new \FewAgency\Reformulator\Middleware\FilterInput();
        $request = new \Illuminate\Http\Request();
        $request['a'] = [' 0 ',' 1 '];

        $result_request = $middleware->handle($request, function ($request) {
            return $request;
        }, 'trim', 'a');

        $this->assertEquals('0', $result_request->a[0]);
        $this->assertEquals('1', $result_request->a[1]);
    }

    public function testTrimSingleValueInArray()
    {
        $middleware = new \FewAgency\Reformulator\Middleware\FilterInput();
        $request = new \Illuminate\Http\Request();
        $request['a'] = [' 0 ',' 1 '];

        $result_request = $middleware->handle($request, function ($request) {
            return $request;
        }, 'trim', 'a.0');

        $this->assertEquals('0', $result_request->a[0]);
        $this->assertEquals(' 1 ', $result_request->a[1]);
    }

    public function testTrimMultipleFields()
    {
        $middleware = new \FewAgency\Reformulator\Middleware\FilterInput();
        $request = new \Illuminate\Http\Request();
        $request['a'] = ' test A ';
        $request['b'] = ' test B ';

        $result_request = $middleware->handle($request, function ($request) {
            return $request;
        }, 'trim', 'a', 'b');

        $this->assertEquals('test A', $result_request->a);
        $this->assertEquals('test B', $result_request->b);
    }
}
