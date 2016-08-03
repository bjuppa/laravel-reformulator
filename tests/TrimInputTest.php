<?php

class TrimInputTest extends PHPUnit_Framework_TestCase
{
    public function testTrim()
    {
        $middleware = new \FewAgency\Reformulator\Middleware\TrimInput();
        $request = new \Illuminate\Http\Request();
        $request['a'] = ' test ';

        $result_request = $middleware->handle($request, function ($request) {
            return $request;
        }, 'a');

        $this->assertEquals('test', $result_request->a);
    }

    public function testTrimAllFields()
    {
        $middleware = new \FewAgency\Reformulator\Middleware\TrimInput();
        $request = new \Illuminate\Http\Request();
        $request['a'] = ' test A ';
        $request['b'] = ' test B ';

        $result_request = $middleware->handle($request, function ($request) {
            return $request;
        });

        $this->assertEquals('test A', $result_request->a);
        $this->assertEquals('test B', $result_request->b);
    }
}
