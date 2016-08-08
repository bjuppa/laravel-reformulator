<?php

namespace FewAgency\Reformulator\Middleware;

use Closure;

/*
 * Strips repeated non-word characters from input data in the request.
 * Define fields with : and comma-separated list of fieldnames (dot-notated).
 *
 * Example for route definition:
 * 'middleware' => 'reformulator.strip_repeats:name,user.age,description,favourite_foods'
 *
 * Example for controller:
 * $this->middleware('reformulator.strip_repeats:name,user.age,description,favourite_foods');
 *
 */

class StripRepeatNonWordCharsFromInput
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param string,... $field_to_trim
     * @return mixed
     */
    public function handle($request, Closure $next, $field_to_strip = null)
    {
        $filter_middleware = new FilterInput();
        $field_names = array_slice(func_get_args(), 2);
        $parameters = array_merge([
            $request,
            $next,
            function ($string) {
                return preg_replace('/(\W)\1+/', '$1', $string);
            }
        ], $field_names);

        return call_user_func_array([$filter_middleware, 'handle'], $parameters);
    }
}
