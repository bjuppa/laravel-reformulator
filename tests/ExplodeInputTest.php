<?php

class ExplodeInputTest extends PHPUnit_Framework_TestCase
{
    public function testExplode()
    {
        $middleware = new \FewAgency\Reformulator\Middleware\ExplodeInput();
        $request = new \Illuminate\Http\Request();
        $request->merge(['a' => "1, 2\n \t 3,4 ,  5, , Art director"]);

        $result_request = $middleware->handle($request, function ($request) {
            return $request;
        }, 'a');

        $this->assertEquals(['1', '2', '3', '4', '5', 'Art director'], $result_request->a);
    }

    public function testExplodeRegexpChar()
    {
        $middleware = new \FewAgency\Reformulator\Middleware\ExplodeInput();
        $request = new \Illuminate\Http\Request();
        $request->merge(['a' => "1+2 3"]);

        $result_request = $middleware->handle($request, function ($request) {
            return $request;
        }, 'result', 'a', '+');

        $this->assertEquals(['1', '2 3'], $result_request->result);
    }

    public function testExplodePattern()
    {
        $middleware = new \FewAgency\Reformulator\Middleware\ExplodeInput();
        $request = new \Illuminate\Http\Request();
        $request->merge(['a' => "1a2b3c4"]);

        $result_request = $middleware->handle($request, function ($request) {
            return $request;
        }, 'result', 'a', '/[a-b]/');

        $this->assertEquals(['1', '2', '3c4'], $result_request->result);
    }

    public function testExplodeSlash()
    {
        $middleware = new \FewAgency\Reformulator\Middleware\ExplodeInput();
        $request = new \Illuminate\Http\Request();
        $request->merge(['a' => "1/2/3"]);

        $result_request = $middleware->handle($request, function ($request) {
            return $request;
        }, 'result', 'a', '/');

        $this->assertEquals(['1', '2', '3'], $result_request->result);
    }
}