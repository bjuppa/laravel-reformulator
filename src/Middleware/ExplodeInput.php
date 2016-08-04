<?php

namespace FewAgency\Reformulator\Middleware;

use Closure;
use FewAgency\Reformulator\Support\ModifiesRequestInputTrait;
use Illuminate\Support\Str;

/*
 * Explode a string from request input to an array.
 * The field to set is the first parameter (dot-notated).
 * The field to read from is the second parameter (will use the target field if not set)
 * The third parameter is the pattern to explode by.
 * Specify a regexp pattern by enclosing it in slashes.
 * The pattern defaults to any combo of comma/space/newline characters if omitted.
 *
 * Example for route definition:
 * 'middleware' => 'reformulator.explode:path_segments,path,/'
 *
 * Example for controller:
 * $this->middleware('reformulator.explode:tags');
 *
 */

class ExplodeInput
{
    use ModifiesRequestInputTrait;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  string $target_field
     * @param  string $source_field
     * @param  string $pattern
     * @return mixed
     */
    public function handle($request, Closure $next, $target_field, $source_field = null, $pattern = '/[\s,]+/')
    {
        //Commas can’t be used by \Illuminate\Pipeline\Pipeline::parsePipeString
        $pattern = str_replace('COMMA', ',', $pattern);
        if (!Str::startsWith($pattern, '/') or !Str::endsWith($pattern, '/') or $pattern == '/') {
            $pattern = '/' . preg_quote($pattern, '/') . '/';
        }

        if (empty($source_field)) {
            $source_field = $target_field;
        }

        $this->setRequestInput($request, $target_field, preg_split($pattern, $request->input($source_field)));

        return $next($request);
    }
}
