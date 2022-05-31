<?php

namespace Dotlogics\Grapesjs\App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;
use Dotlogics\Grapesjs\App\Traits\EditorTrait;
use Illuminate\Support\Facades\View;

class EditorController extends Controller
{
    use EditorTrait;

    public function __construct(Request $request){
        $model = $request->route()->parameters['model'] ?? null;
        
        if(!empty($model)){
            $request->route()->setParameter('model',  str_replace('-', '\\', $model));
        }
    }

    public function editor(Request $request, $model, $id)
    {
        return $this->show_gjs_editor($request, $model::findOrFail($id));
    }
    
    public function store(Request $request, $model, $id)
    {
        return $this->store_gjs_data($request, $model::findOrFail($id));
    }

    public function templates(Request $request, $model, $id)
    {
        $model = $model::findOrFail($id);

        return collect([
                'templates',
                'gjs-blocks',
            ])
            ->map(function($type) use ($model){
                $type = Str::of($type);
                $base_path_package_views = __DIR__ . '/../../../resources/views/';
                $base_path_project_views = resource_path('views/vendor/laravel-grapesjs/');
                
                $path_getter_method = "get" . $type->studly() . 'Path';

                if(method_exists($model, $path_getter_method)){
                    $path = $model->{$path_getter_method}();

                    if(!empty($path)){
                        $path = $base_path_project_views . $path;
                    }
                }else{
                    $path = $base_path_project_views . $type;

                    if(!File::exists($path)) {
                        $path = $base_path_package_views . $type;
                    }
                }

                if(empty($path) || !File::exists($path)) return;

                $type_name = $type->replace('gjs-', '');

                $id_prefix = (string)$type_name->singular() . '-';
                $category = (string)$type_name->title();

                $templates = [];
                foreach (File::allFiles($path) as $fileInfo) {
                    $file_name = Str::of($fileInfo->getBasename())->replace(".blade.php", "");
                    $view_base = Str::of($fileInfo->getPath())->replace([
                        $base_path_package_views,
                        $base_path_project_views,
                        rtrim($base_path_package_views, '/'),
                        rtrim($base_path_project_views, '/'),
                    ], '');
                    
                    if(!empty('' . $view_base)){
                        $view_base .= '.';
                    }

                    $content = view("laravel-grapesjs::{$view_base}{$file_name}")->render();

                    // dd($content);
                    $templates [] = [
                        'id' => $id_prefix . $fileInfo->getFilename(),
                        'category' => $category,
                        'label' => $file_name->replace('-', ' ')->title(),
                        'media' => app('template-icon')->url(),
                        'content' => $content,
                    ];
                }

                return $templates;
            })
            ->flatten(1)
            ->filter()
            ->values();
    }
}
