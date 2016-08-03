<?php
namespace FewAgency\Reformulator\Support;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;

/**
 * Class ModifiesRequestInputTrait
 * @package FewAgency\Reformulator\Support
 *
 * Adds ability to modify input values in Illuminate\Http\Request objects.
 * This trait is intended to be used in Laravel middleware, or Illuminate\Foundation\Http\FormRequest.
 */
trait ModifiesRequestInputTrait
{
    /**
     * Replaces a single item in a given Request’s input using dot-notation
     * @param Request $request to modify
     * @param string $key in dot-notation
     * @param mixed $value to set
     */
    protected function setRequestInput(Request $request, $key, $value)
    {
        if (strpos($key, '.')) {
            // The data to set is deeper than 1 level down
            // meaning the final value of the input's first level key is expected to be an array
            list($first_level_key, $key_rest) = explode('.', $key, 2);
            // Pull out the input's existing first level value to modify it as an array
            $first_level_value = $request->input($first_level_key); //Request::input() pulls from all input data using dot-notation (ArrayAccess on Request would also pull out files which is undesirable here).
            if (!is_array($first_level_value)) {
                $first_level_value = [];
            }
            Arr::set($first_level_value, $key_rest, $value);
        } else {
            // The data to set is in the first level
            $first_level_key = $key;
            $first_level_value = $value;
        }
        $request->merge([$first_level_key => $first_level_value]); // The only current alternatives for modifying Request input data are merge() and replace(), the latter replacing the whole input data.

        /*
         * It could look tempting to skip all of the above code and just
         * Arr::set() on the Request object utilizing it's ArrayAccess...
         * But it doesn't work for the second- or higher dot-levels because of the
         * non-reference return value of ArrayAccess::offsetGet()
         */
    }
}