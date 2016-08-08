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
```
Read more in the [Laravel docs for middleware](https://laravel.com/docs/middleware#registering-middleware).

## Principles
Some would argue that it's not a good idea to mutate the `Request` object, for example see
[GrahamCampbell's comments on Laravel issue 10725](https://github.com/laravel/framework/issues/10725).

My opinion is that it makes sense to modify data in the request when:
- The same transformations *could* instead have been done on the client side before submitting,
or substitutes a shortcoming in the transmission method
(e.g. it's not possible to submit an empty array)
- The transformations absolutely can't go against the user's intention
(as we're not giving the user a chance to approve the change)
- The transformations doesn't break repopulating the view (i.e. form)

## Authors
I, Björn Nilsved, work at the largest communication agency in southern Sweden.
We call ourselves [FEW](http://fewagency.se) (oh, the irony).
From time to time we have positions open for web developers/programmers/UXers in the Malmö/Copenhagen area,
so please get in touch!

## License
FEW Agency's Reformulator is open-sourced software licensed under the
[MIT license](http://opensource.org/licenses/MIT)
