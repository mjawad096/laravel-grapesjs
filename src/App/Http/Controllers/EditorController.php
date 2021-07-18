<?php

namespace Topdot\Grapesjs\App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;
use Topdot\Grapesjs\App\Editor\EditorFactory;
use Topdot\Grapesjs\App\Traits\EditorTrait;

class EditorController extends Controller
{
    use EditorTrait;

    public function editor(Request $request, $model, $id)
    {
        $modelClass = config('grapesjs.model' . $model);
        if (is_null($modelClass))
            abort(404);

        $editable = $modelClass::findOrFail($id);

        return $this->show_gjs_editor($request, $editable);
    }
    
    public function store(Request $request, $model, $id)
    {
        $modelClass = config('grapesjs.model' . $model);
        if (is_null($modelClass))
            abort(404);

        $editable = $modelClass::findOrFail($id);

        return $this->store_gjs_data($request, $editable);
    }

    public function templates(Request $request)
    {
        $templatesPath = resource_path('views/vendor/grapesjs/templates');
        $otherBlocks = resource_path('views/vendor/grapesjs/gjs-blocks');

        if(!File::exists($templatesPath)) {
            $templatesPath = __DIR__ . '/../../../resources/views/templates';
        }

        if(!File::exists($otherBlocks)) {
            $otherBlocks = __DIR__ . '/../../../resources/views/gjs-blocks';
        }
        
        $templates = []; 

        foreach (File::allFiles($templatesPath) as $fileInfo) {
            $file_name = str_replace(".blade.php", "", $fileInfo->getBasename());
            $templates [] = [
                'category' => 'Templates',
                'id' => 'template-' . $fileInfo->getFilename(),
                'label' => Str::title(str_replace(["-"], " ", $file_name)),
                'content' => view("grapesjs::templates.{$file_name}")->render()
            ];
        }
        
        foreach (File::allFiles($otherBlocks) as $fileInfo) {
            $file_name = str_replace(".blade.php", "", $fileInfo->getBasename());
            $templates [] = [
                'category' => 'Blocks',
                'id' => 'block-' . $fileInfo->getFilename(),
                'label' => Str::title(str_replace(["-"], " ", $file_name)),
                'content' => view("grapesjs::gjs-blocks.{$file_name}")->render()
            ];
        }

        return $templates;
    }
}
