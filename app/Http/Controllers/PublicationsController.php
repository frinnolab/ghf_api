<?php

namespace App\Http\Controllers;

use App\Models\Profiles\Profile;
use App\Models\Publications\Publication;
use App\Models\Publications\PublicationAsset;
use App\Models\Publications\PublicationSubscriber;
// use App\Models\publications\PublicationSubscriber;
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

        $data = Publication::latest()->get();

        if ($data == null) {
            return response($response, Response::HTTP_NO_CONTENT);
        }




        //Format Response
        foreach ($data as $value) {
            //get asset

            $pubAsset = PublicationAsset::where('publish_id', '=', $value->publish_id)->first();

            $filePath = null;
            if ($pubAsset != null) {
                //continue;
                if ($pubAsset->asset_url) {
                    $filePath = asset(Storage::url($pubAsset->asset_url));
                }
            }
            $d = [
                "publishId" => $value->publish_id,
                "title" => $value->title,
                "description" => $value->description,
                "publishType" => $value->publish_type,
                "publishDate" => $value->publish_date,
                "assetUrl" => $filePath,
                "authorId" => $value->author_id
            ];
            array_push($response, $d);
        }

        //dump($response);


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

        // //get asset file

        // $pubAsset = PublicationAsset::where('publish_id', '=', $data->publish_id)->first();


        // $filePath = null;

        // if ($pubAsset->asset_url) {
        //     $filePath = asset(Storage::url($pubAsset->asset_url));
        // }

        //format response
        $response = [
            "publishId" => $data->publish_id,
            "title" => $data->title,
            "description" => $data->description,
            "publishType" => $data->publish_type,
            "publishDate" => $data->publish_date,
            "authorId" => $data->author_id,
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

        $assets = PublicationAsset::where('publish_id', '=', $publishId)->latest()->get();
        if ($assets == null) {
            return response(["No Assests"], Response::HTTP_NO_CONTENT);
        }


        $filePath = null;

        foreach ($assets as $asset) {

            if ($asset->asset_url) {
                $filePath = asset(Storage::url($asset->asset_url));
            }

            $data = [
                "publishId" => $asset->publish_id,
                "title" => $asset->title,
                "assetId" => $asset->publish_asset_id,
                "assetUrl" => $filePath,
                "assetType" => $asset->type ?? null
            ];

            array_push($assetResponse, $data);
        }

        return response($assetResponse, Response::HTTP_OK);
        // ->header('Access-Control-Allow-Origin', 'http://localhost:5173')
        // ->header('Access-Control-Allow-Origin', 'https://ghftz.or.tz')
        // ->header('Access-Control-Allow-Origin', 'http://ghftz.or.tz');
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

        $path = null;
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
            "asset_url" => $path,
            "type" => $request->input('type'),
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

        dump($assetId);
        $data = PublicationAsset::where('publish_asset_id', '=', $assetId)->first();
        if ($data == null) {
            return response([
                "Asset not found"
            ], Response::HTTP_NOT_FOUND);
        }

        dd($data);

        return response([$data]);


        //return response()->download(storage_path("app/" . $data->asset_url));

        // if (!Storage::exists($data->asset_url)) {
        //     return response()->json(['message' => 'File not found'], Response::HTTP_NOT_FOUND);
        // }

        // $DATA1 = [
        //     $assetId,
        //     $data
        // ];

        // dd($data);

        // // Get the file content
        // $file = Storage::get($data->asset_url);
        // $mimeType = Storage::mimeType($file);

        // $data2 = [
        //     $file,
        //     $mimeType
        // ];

        // dd($data2);

        // return response($file, 200)
        //     ->header('Content-Type', $mimeType)
        //     ->header('Content-Disposition', 'inline; filename="' . $assetId . '.pdf"');

        //return response()->storage_path("app/" . $data->asset_url);
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
        $data = PublicationAsset::where('publish_asset_id', '=', $assetId)->first();

        if ($data == null) {
            return response([
                "Asset not found"
            ], Response::HTTP_NOT_FOUND);
        }

        $data->delete();

        return response([], Response::HTTP_CREATED);
    }

    //Subscriptions Endpoints

    /**
     * Display a listing of the resource.
     */
    public function subs_index(Request $request)
    {
        //
        $subsResponse = [];

        $pubs = PublicationSubscriber::latest()->get();
        if ($pubs == null) {
            return response(["No Subscribers found"], Response::HTTP_NO_CONTENT);
        }

        foreach ($pubs as $asset) {

            $data = [
                "subscriberId" => $asset->subscriberId,
                "email" => $asset->email,
                "isSubscribed" => boolval($asset->isSubscribed ?? false),
            ];

            array_push($subsResponse, $data);
        }

        return response($subsResponse, Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function subs_store(Request $request)
    {

        // $asset = new PublicationSubscriber([
        //     "email" => $request->input('email'),
        //     "isSubscribed" => boolval($request->input('isSubscribed') ?? false),
        // ]);

        $pubSub = new PublicationSubscriber([
            "email" => $request->input('email'),
            "isSubscribed" => boolval($request->input('isSubscribed') ?? false),
        ]);

        $pubSub->save();

        return response([
            "subscriberId" => $pubSub->subscriberId
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource./Download
     */
    public function subs_show(string $subscriberId)
    {
        $data = PublicationSubscriber::where('subscriberId', '=', $subscriberId)->first();
        if ($data == null) {
            return response([
                "Subscriber not found"
            ], Response::HTTP_NOT_FOUND);
        }

        $response = [
            "subscriberId" => $data->subscriberId,
            "email" => $data->email,
            "isSubscribed" => boolval($data->isSubscribed ?? false),
        ];
        return response($response, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function subs_update(Request $request, string $subscriberId)
    {
        //
        $data = PublicationSubscriber::where('subscriberId', '=', $subscriberId)->first();
        if ($data == null) {
            return response([
                "Subscriber not found"
            ], Response::HTTP_NOT_FOUND);
        }

        $data->email = $request->input('email') ?? $data->email;
        $data->isSubscribed = boolval($request->input('isSubscribed') ?? $data->isSubscribed ?? false);

        $data->save();

        $response = [
            'subscriberId' => $data->subscriberId
        ];

        return response($response, Response::HTTP_CREATED);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function subs_destroy(string $subscriberId)
    {
        //
        $data = PublicationSubscriber::where('subscriberId', '=', $subscriberId)->first();
        $data->delete();

        return response([], Response::HTTP_CREATED);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function subs_publish_store(Request $request, string $newsletterId)
    {

        //Newsletter
        $newLetter = Publication::where('publish_id', '=', $newsletterId)->first();

        if ($newLetter == null) {
            return response([
                "Newsletter not found"
            ], Response::HTTP_NOT_FOUND);
        }

        //Newsletter type
        if ($newLetter->publish_type != 1) {
            //If publication not Newsletter
            return response(["Publication not newsletter."], Response::HTTP_BAD_REQUEST);
        }

        //Get Subscribers

        $subs = PublicationSubscriber::where('isSubscribed', '=', true)->latest()->get();

        if ($subs == null) {
            return response(["No Subscribers found"], Response::HTTP_NOT_FOUND);
        }

        //Bulk Email Send here.

        return response([
            // "subscriberId" => $asset->subscriberId
        ], Response::HTTP_CREATED);
    }
}
