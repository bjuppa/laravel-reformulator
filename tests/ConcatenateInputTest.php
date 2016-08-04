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

    //TODO: test concatenating hour, minute, second into time with :

    //TODO: test concat hour, minute when minute is empty string - this should not set the target field
}