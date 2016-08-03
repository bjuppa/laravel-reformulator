<?php

namespace FewAgency\Reformulator\Middleware;

use Closure;
use FewAgency\Reformulator\Support\ModifiesRequestInputTrait;

/*
 * Filter existing input data in the request, usually using a string function.
 * The function is the first parameter and the rest are the input field names (dot-notated) to filter using that function.
 * If a field name corresponds to an array in the request data,
 * the function will be called on each leaf of the (multi-dimensional) array.
 * If no fieldnames are supplied, all input data will be filtered.
 *
 * Example for route definition:
 * 'middleware' => 'reformulator.filter:ucfirst,name,address.city,nicknames'
 *
 * Example for controller:
 * $this->middleware('reformulator.filter:ucfirst,name,address.city,nicknames');
 *
 */

class FilterInput
{
    use ModifiesRequestInputTrait;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param mixed $filter_function
     * @param string,... $field_name
     * @return mixed
     */
    public function handle($request, Closure $next, callable $filter_function, $field_name = null)
    {
        $field_names = array_slice(func_get_args(), 3);
        if (!count($field_names)) {
            $field_names = array_keys($request->input());
        }
        foreach ($field_names as $field_name) {
            if ($request->has($field_name)) {
                $value = $request->input($field_name);
                if (is_array($value)) {
                    array_walk_recursive($value, function (&$value) use ($filter_function) {
                        $value = call_user_func($filter_function, $value);
                    });
                } else {
                    $value = call_user_func($filter_function, $value);
                }
                $this->setRequestInput($request, $field_name, $value);
            }
        }

        return $next($request);
    }
}
