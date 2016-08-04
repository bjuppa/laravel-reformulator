<?php

namespace FewAgency\Reformulator\Middleware;

use Closure;
use FewAgency\Reformulator\Support\ModifiesRequestInputTrait;

/*
 * Concatenate strings from request inputs or arrays.
 * The field to modify is the first parameter (dot-notated).
 * The concatenating string is the second parameter (defaults to comma-sign if omitted)
 * The rest of the arguments are other fields (dot-notated) to concatenate into the first field.
 * If no other fields are specified, the target field will be concatenated if it's an array.
 *
 * Example for route definition:
 * 'middleware' => 'reformulator.concatenate:tags'
 *
 * Example for controller:
 * $this->middleware('reformulator.concatenate:time,:,hour,minute,second');
 *
 */

class ConcatenateInput
{
    use ModifiesRequestInputTrait;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  string $target_field
     * @param  string $glue
     * @param  string ,... $source_field
     * @return mixed
     */
    public function handle($request, Closure $next, $target_field, $glue = 'COMMA', $source_field = null)
    {
        $source_fields = array_slice(func_get_args(), 4);
        $source_values = [];
        if (count($source_fields)) {
            foreach ($source_fields as $source_field) {
                $source_value = $request->input($source_field);
                if (is_array($source_value)) {
                    $source_values = array_merge($source_values, $source_value);
                }
                $source_values[] = $source_value;
            }
        } else {
            $source_values = (array)$request->input($target_field);
        }
        $glue = str_replace('COMMA', ',', $glue);
        $this->setRequestInput($request, $target_field, implode($glue, $source_values));

        return $next($request);
    }
}
