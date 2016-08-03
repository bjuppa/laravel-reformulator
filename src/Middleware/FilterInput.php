<?php

namespace FewAgency\Reformulator\Middleware;

use Closure;
use FewAgency\Reformulator\Support\ModifiesRequestInputTrait;

/*
 * Filter existing input data in the request, usually using a string function.
 * The function is the first parameter and the rest are the input field names (dot-notated) to filter using that function.
 * If a field name corresponds to an array in the request data, the function will be called on each leaf of the multi-dimensional array.
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
     * @param mixed $function
     * @param string,... $field_name
     * @return mixed
     */
    public function handle($request, Closure $next, callable $function, $field_name = null)
    {
        foreach (array_slice(func_get_args(), 3) as $field_name) {
            if ($request->has($field_name)) {
                $value = $request->input($field_name);
                if (is_array($value)) {
                    array_walk_recursive($value, function (&$value) use ($function) {
                        $value = call_user_func($function, $value);
                    });
                } else {
                    $value = call_user_func($function, $value);
                }
                $this->setRequestInput($request, $field_name, $value);
            }
        }

        return $next($request);
    }
}
