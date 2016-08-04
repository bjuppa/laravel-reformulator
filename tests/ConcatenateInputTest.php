<?php

class ConcatenateInputTest extends PHPUnit_Framework_TestCase
{
    public function testConcatArray()
    {
        $middleware = new \FewAgency\Reformulator\Middleware\ConcatenateInput();
        $request = new \Illuminate\Http\Request();
        $request->merge(['a' => ['A', 'B', '', 'C']]);

        $result_request = $middleware->handle($request, function ($request) {
            return $request;
        }, 'a');

        $this->assertEquals('A,B,,C', $result_request->a);
    }

    public function testConcatTime()
    {
        $middleware = new \FewAgency\Reformulator\Middleware\ConcatenateInput();
        $request = new \Illuminate\Http\Request();
        $request->merge(['hours' => 10, 'minutes' => 20, 'seconds' => 30]);

        $result_request = $middleware->handle($request, function ($request) {
            return $request;
        }, 'time', ':', 'hours', 'minutes', 'seconds');

        $this->assertEquals('10:20:30', $result_request->time);
    }

    public function testConcatEmpty()
    {
        $middleware = new \FewAgency\Reformulator\Middleware\ConcatenateInput();
        $request = new \Illuminate\Http\Request();
        $request->merge(['hours' => 10, 'minutes' => 20, 'seconds' => '']);

        $result_request = $middleware->handle($request, function ($request) {
            return $request;
        }, 'time', ':', 'hours', 'minutes', 'seconds');

        $this->assertEquals('10:20', $result_request->time);
    }

    public function testConcatNotOverwriting()
    {
        $middleware = new \FewAgency\Reformulator\Middleware\ConcatenateInput();
        $request = new \Illuminate\Http\Request();
        $request->merge(['a' => ['a' => 1], 'b' => ['a' => 2]]);

        $result_request = $middleware->handle($request, function ($request) {
            return $request;
        }, 'result','COMMA','a','b');

        $this->assertEquals('1,2', $result_request->result);
    }

    public function testConcatDeepArray()
    {
        $middleware = new \FewAgency\Reformulator\Middleware\ConcatenateInput();
        $request = new \Illuminate\Http\Request();
        $request->merge(['a' => ['A', 'b' => ['B']]]);

        $result_request = $middleware->handle($request, function ($request) {
            return $request;
        }, 'a');

        $this->assertEquals('A,B', $result_request->a);
    }
}