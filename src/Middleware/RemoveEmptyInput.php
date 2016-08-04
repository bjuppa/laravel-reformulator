<?php

namespace FewAgency\Reformulator\Middleware;

use Closure;
use FewAgency\Reformulator\Support\ModifiesRequestInputTrait;
use Illuminate\Http\Request;

/*
 * Recursively clean empty strings from request inputs (keeping strings of 0).
 * Define fields to clean out with : and comma-separated list of fieldnames (dot-notated).
 * Arrays will be cleaned recursively, but empty arrays will be kept.
 * If no fieldnames are supplied, all empty input data will be removed from request.
 *
 * Example for route definition:
 * 'middleware' => 'reformulator.remove_empty:name,address.line2,nicknames'
 *
 * Example for controller:
 * $this->middleware('reformulator.remove_empty:name,address.line2,nicknames');
 *
 */

class RemoveEmptyInput
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
            $this->removeEmptyRecursive($request, explode('.', $field_name), $request->input($field_name));
        }

        return $next($request);
    }

    /**
     * Support method to recursively clean request input from empty strings
     * @param Request $request to modify
     * @param array $key_parts split key parts for the current level
     * @param mixed $value for the current level to check
     */
    protected function removeEmptyRecursive(Request $request, array $key_parts, $value)
    {
        if (is_array($value)) {
            foreach ($value as $next_level_key => $next_level_value) {
                $this->removeEmptyRecursive($request, array_merge($key_parts, [$next_level_key]), $next_level_value);
            }
        } elseif (!strlen($value)) {
            $this->unsetRequestInput($request, implode('.', $key_parts));
        }
    }
}
