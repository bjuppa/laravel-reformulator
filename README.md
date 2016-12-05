# Reformulator
Laravel middleware to filter, sanitize, parse and transform request input data.

## Installation & Configuration
> composer require fewagency/laravel-reformulator

Register each middleware you want to use in the `$routeMiddleware` array
in `app/Http/Kernel.php` of your Laravel app: 
```php
'reformulator.trim' => \FewAgency\Reformulator\Middleware\TrimInput::class,
'reformulator.strip_repeats' => \FewAgency\Reformulator\Middleware\StripRepeatNonWordCharsFromInput::class,
'reformulator.filter' => \FewAgency\Reformulator\Middleware\FilterInput::class,

'reformulator.remove_empty' => \FewAgency\Reformulator\Middleware\RemoveEmptyInput::class,
'reformulator.clean_array' => \FewAgency\Reformulator\Middleware\CleanArrayInput::class,

'reformulator.concatenate' => \FewAgency\Reformulator\Middleware\ConcatenateInput::class,
'reformulator.explode' => \FewAgency\Reformulator\Middleware\ExplodeInput::class,

'reformulator.datetime-local' => \FewAgency\Reformulator\Middleware\DatetimeLocalInput::class,
'reformulator.datetime' => \FewAgency\Reformulator\Middleware\DatetimeInput::class,
```

Usage and parameters to each middleware is currently documented in [each of the classes](src/Middleware)
but in general, most of them take a list of field names as middleware parameters.

Read more in the [Laravel docs for middleware](https://laravel.com/docs/middleware#registering-middleware).

## Principles
Some would argue that it's **not** a good idea to mutate the `Request` object, for examples see
[GrahamCampbell's comments on Laravel issue 10725](https://github.com/laravel/framework/issues/10725).

My opinion is that it makes sense to modify data in the request when:
- The same transformations *could* instead have been done on the client side before submitting,
or substitutes a shortcoming in the transmission method
(e.g. it's not possible to submit an empty array)
- The transformations absolutely can't go against the user's intention
(as we're not giving the user a chance to approve the change)
- The transformations doesn't break repopulating the view (i.e. form)
  
Listen to
[Full Stack Radio ep. 54 with Jonathan Reinink and host Adam Wathan](http://www.fullstackradio.com/54)
for an interesting conversation about form handling.
Adam then went on and created
[a gist of macros to add filtering functions to Laravel `Request`s](https://gist.github.com/adamwathan/610a9818382900daac6d6ecdf75a109b).

## Authors
I, Björn Nilsved, work at the largest communication agency in southern Sweden.
We call ourselves [FEW](http://fewagency.se) (oh, the irony).
From time to time we have positions open for web developers/programmers/UXers in the Malmö/Copenhagen area,
so please get in touch!

## License
FEW Agency's Reformulator is open-sourced software licensed under the
[MIT license](http://opensource.org/licenses/MIT)
