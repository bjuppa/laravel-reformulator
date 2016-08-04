<?php

namespace FewAgency\Reformulator\Middleware;

use Closure;
use FewAgency\Reformulator\Support\ModifiesRequestInputTrait;
use Illuminate\Support\Arr;

/*
 * Concatenate strings from request inputs or arrays.
 * The field to set is the first parameter (dot-notated).
 * The concatenating string is the second parameter (defaults to comma-sign if omitted)
 * The rest of the arguments are the fields (dot-notated) to concatenate into the first field.
 * If no other fields are specified, the target field will be concatenated if it's an array.
 * If any of the fields to concatenate is empty, that and the rest of the fields will be left out of concatenation.
 * Empty values inside arrays will *not* stop the concatenation, so make sure you've done clean_array/remove_empty on any arrays.
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
     * @param  string,... $source_field
     * @return mixed
     */
    public function handle($request, Closure $next, $target_field, $glue = 'COMMA', $source_field = null)
    {
        //Commas can’t be used by \Illuminate\Pipeline\Pipeline::parsePipeString
        $glue = str_replace('COMMA', ',', $glue);
        $source_fields = array_slice(func_get_args(), 4);

        $concat_values = [];
        if (count($source_fields)) {
            foreach ($source_fields as $source_field) {
                $source_value = $request->input($source_field);
                if (is_array($source_value) or strlen($source_value)) {
                    $concat_values[] = $source_value;
                } else {
                    //This value is empty so stop here and only concatenate the collected values
                    break;
                }
            }
        } else {
            $concat_values = (array)$request->input($target_field);
        }

        $concat_values = Arr::flatten($concat_values);
        $this->setRequestInput($request, $target_field, implode($glue, $concat_values));

        return $next($request);
    }
}
