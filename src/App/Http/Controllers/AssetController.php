<?php

namespace Topdot\Grapesjs\App\Http\Controllers;

use Topdot\Media\App\Models\TempMedia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Topdot\Grapesjs\App\Editor\AssetRepository;
use Illuminate\Foundation\Validation\ValidatesRequests;

class AssetController extends Controller
{
    use ValidatesRequests;

    public function index(AssetRepository $assetRepository)
    {
        return response()->json(
            $assetRepository->getAllMediaLinks()
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'file' => 'bail|required|array|min:1',
            'file.*' => 'bail|required|max:2048'
        ]);

        $media = TempMedia::create()->addMediaFromRequest('file')->toMediaCollection('default');
        return response()->json([
            'data' => [
                route('media.show', $media)
            ]
        ]);
    }
}
