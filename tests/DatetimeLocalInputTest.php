<?php

class DatetimeLocalInputTest extends PHPUnit_Framework_TestCase
{
    public function testDatetimeLocal()
    {
        $middleware = new \FewAgency\Reformulator\Middleware\DatetimeLocalInput();
        $request = new \Illuminate\Http\Request();
        $request['a'] = '2016-07-04 15:07';

        $result_request = $middleware->handle($request, function ($request) {
            return $request;
        }, 'a');

        $this->assertEquals('2016-07-04T15:07:00', $result_request->a);
    }

    public function testDatetimeRelative()
    {
        $middleware = new \FewAgency\Reformulator\Middleware\DatetimeLocalInput();
        $request = new \Illuminate\Http\Request();
        $tz = 'Europe/Stockholm';
        $time_string = 'tomorrow 12:00';
        $request['time_string'] = $time_string;

        $result_request = $middleware->handle($request, function ($request) {
            return $request;
        }, 'time', 'time_string', $tz);

        $this->assertEquals(\Carbon\Carbon::parse($time_string, $tz)->format('Y-m-d\TH:i:s'), $result_request->time);
    }

    public function testInvalidDatetimeNotWriting()
    {
        $middleware = new \FewAgency\Reformulator\Middleware\DatetimeLocalInput();
        $request = new \Illuminate\Http\Request();
        $time_string = 'fail';
        $request['a'] = $time_string;

        $result_request = $middleware->handle($request, function ($request) {
            return $request;
        }, 'a');

        $this->assertEquals($time_string, $result_request->a);
    }

    public function testDatetimeWithInputTimezone()
    {
        $middleware = new \FewAgency\Reformulator\Middleware\DatetimeLocalInput();
        $request = new \Illuminate\Http\Request();
        $request['a'] = '2016-07-04 15:07 +0100';

        $result_request = $middleware->handle($request, function ($request) {
            return $request;
        }, 'a');

        $this->assertEquals('2016-07-04T14:07:00', $result_request->a);
    }

    public function testEmptyDatetimeNotWriting()
    {
        $middleware = new \FewAgency\Reformulator\Middleware\DatetimeLocalInput();
        $request = new \Illuminate\Http\Request();
        $request['a'] = '';

        $result_request = $middleware->handle($request, function ($request) {
            return $request;
        }, 'a');

        $this->assertEquals('', $result_request->a);
    }

    public function testArrayDatetimeNotWriting()
    {
        $middleware = new \FewAgency\Reformulator\Middleware\DatetimeLocalInput();
        $request = new \Illuminate\Http\Request();
        $request['a'] = ['year' => '2016', 'month' => '07', 'day' => '04', 'time' => '15:07'];

        $result_request = $middleware->handle($request, function ($request) {
            return $request;
        }, 'a');

        $this->assertInternalType('array', $result_request->a);
    }
}
