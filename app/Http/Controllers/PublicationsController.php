<?php

namespace App\Http\Controllers;

use App\Models\Profiles\Profile;
use App\Models\Publications\Publication;
use App\Models\Publications\PublicationAsset;
use App\Models\Publications\PublicationType;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class PublicationsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $response = [];
        $news = [];
        
        $data = Publication::all();

        if ($data == null) {
            return response($response, Response::HTTP_NO_CONTENT);
        }
        
        //Format Response
        foreach ($data as $value) {

            $d = [
                "publishId" => $value->publish_id,
                "title" => $value->title,
                "description" => $value->description,
                "publishType" => $value->publish_type,
                "publishDate" => $value->publish_date,
                "authorId" => $value->author_id
            ];
            array_push($response, $d);
        }
        
        return response($response, Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!$request->hasAny('authorId')) {
            return response([
                "authorId is required"
            ], Response::HTTP_BAD_REQUEST);
        }

        $author = Profile::where('profile_id', '=', $request->input('authorId'))->first();

        if ($author == null) {
            return response([
                "Author Not found."
            ], Response::HTTP_NOT_FOUND);
        }

        $pubType = PublicationType::where('value', '=', $request->input('publishType'))->first();

        if (!$pubType) {
            return response([
                "publishType Not found."
            ], Response::HTTP_NOT_FOUND);
        }
        //
        $data = new Publication([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'publish_type' => intval($pubType->value),
            'publish_date' =>  $request->input('publishDate'),
            'author_id' => $author->profile_id,
        ]);

        $data->save();

        return response([
            "publishId" => $data->publish_id
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $publishId)
    {
        //
        $response = [];
        $data = Publication::where('publish_id', '=', $publishId)->first();

        if ($data == null) {
            return response($response, Response::HTTP_NOT_FOUND);
        }

        //format response
        $response = [
            "publishId" => $data->publish_id,
            "title" => $data->title,
            "description" => $data->description,
            "publishType" => $data->publish_type,
            "publishDate" => $data->publish_date,
            "authorId" => $data->author_id
        ];

        return response($response, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $publishId)
    {
        //
        $author = Profile::where('profile_id', '=', $request->input('authorId'))->first();

        if ($author == null) {
            return response([
                "Author Not found."
            ], Response::HTTP_NOT_FOUND);
        }

        $pubType = PublicationType::where('value', '=', $request->input('publishType'))->first();

        if (!$pubType) {
            return response([
                "publishType Not found."
            ], Response::HTTP_NOT_FOUND);
        }

        $data = Publication::where('publish_id', '=', $publishId)->first();

        if ($data == null) {
            return response(["Publication Not found"], Response::HTTP_NOT_FOUND);
        }

        $data->title = $request->input('title') ?? $data->title;
        $data->description = $request->input('description') ?? $data->description;
        $data->publish_type = $pubType->value ?? $data->publish_type;
        $data->publish_date = $request->input('publishDate')  ?? $data->publish_date;
        $data->author_id = $author->profile_id ?? $data->author_id;

        $data->save();

        return response([], Response::HTTP_CREATED);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $publishId)
    {
        //
        $data = Publication::where('publish_id', '=', $publishId)->first();

        if ($data == null) {
            return response(["Publication Not found"], Response::HTTP_NOT_FOUND);
        }

        //Remove related Assets

        $assets = PublicationAsset::where('publish_id', '=', $data->publish_id)->get();

        foreach ($assets as $asset) {
            Storage::delete($asset->asset_url);
        }

        $data->delete();

        return response([], Response::HTTP_CREATED);
    }

    //Assets Endpoints
    /**
     * Display a listing of the resource.
     */
    public function assets_index(string $publishId)
    {
        //
        $assetResponse = [];

        if ($publishId == null) {
            return response(["publishId is required."], Response::HTTP_BAD_REQUEST);
        }

        $assets = PublicationAsset::where('publish_id', '=', $publishId)->get();
        if($assets == null){
            return response(["No Assests"], Response::HTTP_NO_CONTENT);
        }

        foreach ($assets as $asset) {

            $data = [
                "publishId" => $asset->publish_id,
                "title" => $asset->title,
                "assetId" => $asset->publish_asset_id,
                "assetUrl" => asset(Storage::url($asset->asset_url)) ?? null
            ];

            array_push($assetResponse, $data);
        }

        return response($assetResponse, Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function assets_store(Request $request)
    {
        //
        $publishId = $request->input('publishId') ?? null;

        if ($publishId == null) {
            return response(["publishId is required."], Response::HTTP_BAD_REQUEST);
        }

        $pub = Publication::where('publish_id', '=', $publishId)->first();

        if ($pub == null) {
            return response([
                "No publication found."
            ], Response::HTTP_NOT_FOUND);
        }

        $path = '';
        $file = null;

        if ($request->hasFile('doc')) {
            $file = $request->file('doc');

            if (!$file->isValid()) {
                return response()->json(['invalid_file_upload'], Response::HTTP_BAD_REQUEST);
            }

            $path = Storage::putFile('public/publication_assets', $file);
        }

        $asset = new PublicationAsset([
            "title" => $request->input('title'),
            "publish_id" => $pub->publish_id,
            "asset_url" => $path
        ]);

        $asset->save();

        return response([
            "assetId" => $asset->publish_asset_id
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource./Download
     */
    public function assets_show(string $assetId)
    {
        //
        $data = PublicationAsset::where('publish_asset_id','=',$assetId)->first();

        if ($data == null) {
            return response([
                "Asset not found"
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->download($data->asset_url);
    }

    /**
     * Update the specified resource in storage.
     */
    public function assets_update(Request $request, string $assetId)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function assets_destroy(string $assetId)
    {
        //
        $data = PublicationAsset::where('publish_asset_id','=',$assetId)->first();

        if ($data == null) {
            return response([
                "Asset not found"
            ], Response::HTTP_NOT_FOUND);
        }

        $data->delete();

        return response([], Response::HTTP_CREATED);

    }
}
