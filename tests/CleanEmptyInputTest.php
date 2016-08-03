<?php

class CleanEmptyInputTest extends PHPUnit_Framework_TestCase
{
    public function testFirstLevelClean()
    {
        $middleware = new \FewAgency\Reformulator\Middleware\CleanEmptyInput();
        $request = new \Illuminate\Http\Request();
        $request->merge(['a' => '', 'b' => 0]);

        $this->assertEquals('', $request->input('a'));

        $result_request = $middleware->handle($request, function ($request) {
            return $request;
        }, 'a', 'b');

        $this->assertNull($result_request->input('a'));
        $this->assertEquals(0, $result_request->input('b'));
    }

    public function testSecondLevelClean()
    {
        $middleware = new \FewAgency\Reformulator\Middleware\CleanEmptyInput();
        $request = new \Illuminate\Http\Request();
        $request->merge(['a' => ['b' => '']]);

        $this->assertEquals('', $request->input('a.b'));

        $result_request = $middleware->handle($request, function ($request) {
            return $request;
        }, 'a.b');

        $this->assertNull($result_request->input('a.b'));
    }

    public function testArrayClean()
    {
        $middleware = new \FewAgency\Reformulator\Middleware\CleanEmptyInput();
        $request = new \Illuminate\Http\Request();
        $request->merge(['a' => ['b' => '', 'c' => 0]]);

        $this->assertEquals('', $request->input('a.b'));

        $result_request = $middleware->handle($request, function ($request) {
            return $request;
        }, 'a');

        $this->assertNull($result_request->input('a.b'));
        $this->assertEquals(0, $result_request->input('a.c'));
    }

    public function testRecursiveArrayClean()
    {
        $middleware = new \FewAgency\Reformulator\Middleware\CleanEmptyInput();
        $request = new \Illuminate\Http\Request();
        $request->merge(['a' => ['b' => '', 'c' => ['d' => '']]]);

        $this->assertEquals('', $request->input('a.b'));
        $this->assertEquals('', $request->input('a.c.d'));

        $result_request = $middleware->handle($request, function ($request) {
            return $request;
        }, 'a');

        $this->assertInternalType('array', $result_request->input('a.c'));
        $this->assertNull($result_request->input('a.b'));
        $this->assertNull($result_request->input('a.c.d'));
    }
}
