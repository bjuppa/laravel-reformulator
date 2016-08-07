<?php

namespace FewAgency\Reformulator\Middleware;

use Carbon\Carbon;
use Closure;
use FewAgency\Carbonator\Carbonator;
use FewAgency\Reformulator\Support\ModifiesRequestInputTrait;

/*
 * Parse and set a datetime-local value in request, interpreting in a timezone.
 * The field to set is the first parameter (dot-notated).
 * The field to read from is the second parameter (will use the target field if not set)
 * The third parameter is the timezone to interpret in (useful for relative dates like 'next tuesday 12:00').
 * The timezone defaults to the Laravel app's timezone setting if omitted.
 *
 * Example for route definition:
 * 'middleware' => 'reformulator.datetime-local:booking_start'
 *
 * Example for controller:
 * $this->middleware('reformulator.datetime-local:booking_start,booking_start_parts,Europe/Stockholm');
 *
 */

class DatetimeLocalInput
{
    use ModifiesRequestInputTrait;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  string $target_field
     * @param  string $source_field
     * @param  string $timezone
     * @return mixed
     */
    public function handle($request, Closure $next, $target_field, $source_field = null, $timezone = null)
    {
        if (empty($source_field)) {
            $source_field = $target_field;
        }

        if ($request->has($source_field)) {
            if ($string = Carbonator::parseToDatetimeLocal($request->input($source_field), $timezone)) {
                $this->setRequestInput($request, $target_field, $string);
            }
        }

        return $next($request);
    }
}