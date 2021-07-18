# Installation

>`composer require jawad-topdot/laravel-grapesjs`

Publish migrations
>`php artisan vendor:publish --provider="Topdot\Grapesjs\GrapesjsServiceProvider"`

Usage Model
````
use Topdot\Grapesjs\App\Contracts\Editable;
use Topdot\Grapesjs\App\Traits\EditableTrait;

class Page extends Model implements Editable
{
    use EditableTrait;
    
}

page -> Model Name -> config/grapesjs.php
1 -> Model Id
domain.com/editor/page/1
````

using middleware in routes
````
'middleware'       => ['web', 'auth'],
'route_path'       => 'grapesjs',
'route_name'       => 'grapesjs.',
````

models name -> strtolower
````
key -> strtolower(App\Page::class);
value -> App\Page::class

'model' => [
    'page' => "App\Page"
],

````