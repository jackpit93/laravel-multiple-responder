<h1 align="center">
    Specific Response For Each Request.
</h1>


<h3 align="center">
This package will help you to create specific responder for browser,mobile,spa,etc.
</h3>



## <g-emoji class="g-emoji" alias="arrow_down" fallback-src="https://github.githubassets.com/images/icons/emoji/unicode/2b07.png">⬇️</g-emoji> Installation 

You can install the package via composer:

```bash
composer require devnajjary/laravel-multiple-responder
```

```bash
php artisan responder:generate
```


## <g-emoji class="g-emoji" alias="gem" fallback-src="https://github.githubassets.com/images/icons/emoji/unicode/1f48e.png">🤔</g-emoji> How to Use?

create your responder:
```bash
php artisan make:responder Mobile
```
after that,it make file in this paths:
```bash
App/Http/Responder/Mobile.php
```
## <g-emoji class="g-emoji" alias="gem" fallback-src="https://github.githubassets.com/images/icons/emoji/unicode/1f4dd.png">📝</g-emoji> Real use example
We want to have one output for each mobile, browser and SPA. What to do?<g-emoji class="g-emoji" alias="gem" fallback-src="https://github.githubassets.com/images/icons/emoji/unicode/1f48e.png">🤔</g-emoji>
first create responders like this:
```bash
php artisan make:responder SPA
php artisan make:responder Mobile
```
now go to your controller,Suppose your controller looks like this:
```php
namespace App\Http\Controllers;

class YourController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('your-blade-file');
    }
}
```
Now what if we want to have Jason output for mobile?The answer is this. easily
rewrite your controller:
```php
namespace App\Http\Controllers;

use DevNajjary\laravelMultipleResponder\Facade\Responder;

class YourController extends Controller
{
    public function index()
    {
        $users = User::all();
        return Responder::showUsers($users);
    }
}
```
Now define the user method in any file you want
default Responder(for browser response):
```php
namespace App\Http\Responder;

class DefaultResponder
{
 public const HEADER = 'DefaultResponder';

    public function showUsers($users)
    {
        return view('your-blade',compact($users));
    }
}
```
App\Http\Responder\Mobile.php :
```php
namespace App\Http\Responder;

class Mobile
{
    public const HEADER = 'Mobile';

    public function showUsers($users)
    {
        return UserResourse::collection($users);
    }
}
```
App\Http\Responder\Spa.php
```php
namespace App\Http\Responder;

class Spa
{
    public const HEADER = 'Spa';

    public function showUsers($users)
    {
        return [
            'users' => UserResourse::collection($users),
            'additional-data' => 'whatever'
        ];
    }
}
```
### What is the use of Header?
**The point is not to forget that you have to set a const `HEADER` for each Responder**
All your requests from mobile or spa should be a header with `'Client'` key and `HEADER` value;
for SPA like this:
```js
axios.post('url', {"body":data}, {
    headers: {
    'Client': 'Spa'
    }
  }
)
```

#### how to change `'Client'` string ?
run this
```bash
php artisan vendor:publish  --tag="responder"
```
And go to `config/responder.php` change `header_key` to What you love.



## Credits

- [Mohammad](https://github.com/devNajjary)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

