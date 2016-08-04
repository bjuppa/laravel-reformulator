<?php

class CleanArrayInputTest extends PHPUnit_Framework_TestCase
{
    public function testCleanArray()
    {
        $middleware = new \FewAgency\Reformulator\Middleware\CleanArrayInput();
        $request = new \Illuminate\Http\Request();
        $request->merge(['a' => ['A', '', 'B', '', 'C'], 'b' => '', 'c' => 'C']);

        $result_request = $middleware->handle($request, function ($request) {
            return $request;
        }, 'a', 'b', 'c');

        $this->assertNotContains('', $result_request->a);
        $this->assertContains('A', $result_request->a);
        $this->assertContains('B', $result_request->a);
        $this->assertContains('C', $result_request->a);
        $this->assertEquals([], $result_request->b);
        $this->assertEquals(['C'], $result_request->c);
    }
}