<?php

namespace FewAgency\Reformulator\Middleware;

use Closure;
use FewAgency\Reformulator\Support\ModifiesRequestInputTrait;
use Illuminate\Http\Request;

/*
 * Clean empty string data from the request - keeping strings of 0.
 * Define fields to clean out with : and comma-separated list of fieldnames (dot-notated).
 * Arrays will be cleaned recursively.
 * If no fieldnames are supplied, all empty input data will be removed from request.
 *
 * Example for route definition:
 * 'middleware' => 'reformulator.clean:name,address.line2,nicknames'
 *
 * Example for controller:
 * $this->middleware('reformulator.clean:name,address.line2,nicknames');
 *
 */

class CleanEmptyInput
{
    use ModifiesRequestInputTrait;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  string,... $field_name
     * @return mixed
     */
    public function handle($request, Closure $next, $field_name = null)
    {
        $field_names = array_slice(func_get_args(), 2);
        if (!count($field_names)) {
            $field_names = array_keys($request->input());
        }
        foreach ($field_names as $field_name) {
            $this->cleanEmptyRecursive($request, explode('.', $field_name), $request->input($field_name));
        }

        return $next($request);
    }

    protected function cleanEmptyRecursive(Request $request, array $key_parts, $value)
    {
        if (is_array($value)) {
            foreach ($value as $last_level_key => $last_level_value) {
                $this->cleanEmptyRecursive($request, array_merge($key_parts, [$last_level_key]), $last_level_value);
            }
        } elseif (!strlen($value)) {
            $this->unsetRequestInput($request, implode('.', $key_parts));
        }
    }
}
