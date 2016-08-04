# Reformulator
Laravel middleware to filter, sanitize, parse and transform request input data.

## Installation & Configuration
> composer require fewagency/laravel-reformulator

Register the middleware you want to use in the `$routeMiddleware` array
in `app/Http/Kernel.php` of your Laravel app: 
```php
'reformulator.trim' => \FewAgency\Reformulator\Middleware\TrimInput::class,
'reformulator.remove_empty' => \FewAgency\Reformulator\Middleware\RemoveEmptyInput::class,
'reformulator.filter' => \FewAgency\Reformulator\Middleware\FilterInput::class,
```
Read more in the [Laravel docs for middleware](https://laravel.com/docs/middleware#registering-middleware).

## Principles
Some would argue that it's not a good idea to mutate the Request object
(https://github.com/laravel/framework/issues/10725)
My opinion is that it makes sense to modify data in the request input,
as long as the same transformations could have been done client side before submitting.

## Authors
I, Björn Nilsved, work at the largest communication agency in southern Sweden.
We call ourselves [FEW](http://fewagency.se) (oh, the irony).
From time to time we have positions open for web developers/programmers/UXers in the Malmö/Copenhagen area,
so please get in touch!

## License
FEW Agency's Reformulator is open-sourced software licensed under the
[MIT license](http://opensource.org/licenses/MIT)
