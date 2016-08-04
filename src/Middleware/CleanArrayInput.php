<?php

namespace FewAgency\Reformulator\Middleware;

use Closure;
use FewAgency\Reformulator\Support\ModifiesRequestInputTrait;

/*
 * Clean empty strings from array request inputs (keeping strings of 0).
 * String inputs will be converted to array of that item.
 * Empty inputs will be converted to empty array. This helps when using empty string hidden input to denote field presence.
 * Define fields to clean out with : and comma-separated list of fieldnames (dot-notated).
 * Arrays will *not* be cleaned recursively.
 *
 * Example for route definition:
 * 'middleware' => 'reformulator.clean_array:tags,user.nicknames'
 *
 * Example for controller:
 * $this->middleware('reformulator.clean_array:tags,user.nicknames');
 *
 */

class CleanArrayInput
{
    use ModifiesRequestInputTrait;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  string ,... $field_name
     * @return mixed
     */
    public function handle($request, Closure $next, $field_name)
    {
        $field_names = array_slice(func_get_args(), 2);
        foreach ($field_names as $field_name) {
            $array = (array)$request->input($field_name);
            $array = array_filter($array, 'strlen');
            $this->setRequestInput($request, $field_name, $array);
        }

        return $next($request);
    }
}
