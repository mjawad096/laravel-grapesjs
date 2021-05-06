<?php

namespace Topdot\Grapesjs\App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;
use Topdot\Grapesjs\App\Editor\EditorFactory;

class EditorController extends Controller
{
    public function __construct(Request $request){
        $model = $request->route()->parameters['model'] ?? null;
        
        if(!empty($model)){
            $request->route()->setParameter('model',  str_replace('-', '\\', $model));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request, $model, $id)
    {

        dd(str_replace('-', '\\', $model));
        $page->update([
            'components' => $request->get('laravel-editorcomponents'),
            'styles' => $request->get('laravel-editorstyles'),
            'css' => $request->get('laravel-editorcss'),
            'html' => $request->get('laravel-editorhtml'),
        ]);

        return response()->noContent(200);
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
            $templates [] = [
                'category' => 'Templates',
                'id' => $fileInfo->getFilename(),
                'label' => Str::title(str_replace([".blade.php","-"]," ",$fileInfo->getBasename())),
                'content' => $fileInfo->getContents()
            ];
        }
        
        foreach (File::allFiles($otherBlocks) as $fileInfo) {
            $templates [] = [
                'category' => 'Blocks',
                'id' => $fileInfo->getFilename(),
                'label' => Str::title(str_replace([".blade.php","-"], " ", $fileInfo->getBasename())),
                'content' => view()->file($fileInfo->getPathname())->render()
            ];
        }

        return $templates;
    }
}
