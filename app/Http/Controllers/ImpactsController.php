<?php

namespace App\Http\Controllers;

use App\Models\Impacts\Impact;
use App\Models\Impacts\ImpactAsset;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class ImpactsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $response =  [];

        $limit = 0;

        $datas = Impact::latest()->get();


        if ($datas == null) {
            return response($response, Response::HTTP_NO_CONTENT);
        }

        if ($request->query('limit')) {
            $limit = intval($request->query('limit'));
            $datas = $datas->take($limit);
        }


        foreach ($datas as $data) {

            $assetThumb = ImpactAsset::where('impact_id', '=', $data->impact_id)->first();

            $asset_url = null;

            if($assetThumb){
                $asset_url = asset(Storage::url($assetThumb->asset_url));
            }
            $dataRes = [
                "impactId" => $data->impact_id,
                "assetUrl" => $asset_url,
                "title" => $data->title,
                "description" => $data->description,
                "schoolName" => $data->school_name,
                "schoolRegion" => $data->school_region,
                "schoolsTotal" => $data->school_reached_total,
                "schoolDistrict" => $data->school_district,
                "studentGirls" => $data->student_girls,
                "studentBoys" => $data->student_boys,
                "studentsTotal" => $data->student_total,
                "limit" => $request->query('limit'),
            ];

            array_push($response, $dataRes);
        }

        return response($response);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate(['title' => ['required']]);

        $data = new Impact([
            "title" => $request->input('title'),
            "description" => $request->input('description'),
            "school_name" => $request->input('schoolName'),
            "school_region" => $request->input('schoolRegion'),
            "school_district" => $request->input('schoolDistrict'),
            "school_reached_total" => intval($request->input('schoolsTotal')) ?? 0,
            "student_boys" => intval($request->input('studentBoys') ?? 0),
            "student_girls" => intval($request->input('studentGirls') ?? 0),
        ]);

        $data->student_total =
            $data->student_boys + $data->student_girls;

        $data->save();



        $response = [
            "impactId" => $data->impact_id
        ];

        return response($response, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //

        $data = Impact::where('impact_id', '=', $id)->first();

        if ($data == null) {
            return response([], Response::HTTP_NOT_FOUND);
        }

        $assetThumb = ImpactAsset::where('impact_id', '=', $data->impact_id)->first();

        $asset_url = null;

        if($assetThumb){

            $asset_url = asset(Storage::url($assetThumb->asset_url)) ?? null;
        }



        $response = [
            "impactId" => $data->impact_id,
            "assetUrl" => $asset_url,
            "title" => $data->title,
            "description" => $data->description,
            "schoolName" => $data->school_name,
            "schoolRegion" => $data->school_region,
            "schoolDistrict" => $data->school_district,
            "studentGirls" => $data->student_girls,
            "studentBoys" => $data->student_boys,
            "studentsTotal" => $data->student_total,
            "schoolsTotal" => $data->school_reached_total,
            //"school_reached_total" => intval($request->input('schoolsTotal')) ?? 0,
        ];

        return response($response);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(string $id, Request $request)
    {
        //
        $data = Impact::where('impact_id', '=', $id)->first();

        if ($data == null) {
            return response([], Response::HTTP_NOT_FOUND);
        }

        $data->title = $request->input('title') ?? $data->title;
        $data->description = $request->input('description') ?? $data->description;
        $data->school_name = $request->input('schoolName') ?? $data->school_name;
        $data->school_region = $request->input('schoolRegion') ?? $data->school_region;
        $data->school_district = $request->input('schoolDistrict') ?? $data->school_district;
        $data->student_boys = intval($request->input('studentBoys') ?? $data->student_boys);
        $data->school_reached_total = intval($request->input('schoolsTotal') ?? $data->school_reached_total);
        $data->student_girls = intval($request->input('studentGirls') ?? $data->student_girls);
        $data->student_total = intval($data->student_boys + $data->student_girls);



        $data->update();

        $response = [
            "impactId" => $data->impact_id
        ];

        return response($response, Response::HTTP_CREATED);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $data = Impact::where('impact_id', '=', $id)->first();

        if ($data == null) {
            return response([], Response::HTTP_NOT_FOUND);
        }

        $dataAssets = ImpactAsset::where('impact_id', '=', $data->impact_id)->get();

        foreach ($dataAssets as $asset) {
            $asset->delete();
        }

        $data->delete();

        return response([], Response::HTTP_CREATED);
    }

    //Impact Assets

    public function assets_index(string $impactId)
    {

        $response = [];
        //$request->validate(['impactId'=>'required']);

        if ($impactId == '' or $impactId == null) {
            return response(['ImpactId is required'], Response::HTTP_BAD_REQUEST);
        }

        $datas = ImpactAsset::where('impact_id', '=', $impactId)->latest()->get();

        if ($datas == null) {
            return response([], Response::HTTP_NO_CONTENT);
        }

        foreach ($datas as $asset) {
            $imgUrl = null;
            if ($asset->asset_url != '' or $asset->asset_url != null) {
                $imgUrl = asset(Storage::url($asset->asset_url));
            }
            $data = [
                'assetId' => $asset->impact_asset_id,
                'impactId' => $asset->impact_id,
                'assetUrl' => $imgUrl,
            ];

            array_push($response, $data);
        }

        return response($response, Response::HTTP_OK);
    }

    public function assets_show(string $assetId)
    {

        $data = ImpactAsset::where('impact_asset_id', '=', $assetId)->first();

        if ($data == null) {
            return response([
                "Asset not found"
            ], Response::HTTP_NOT_FOUND);
        }

        $response = [
            "assetId" => $data->impact_asset_id,
            "assetUrl" => asset(Storage::url($data->asset_url))
        ];
        return response($response, Response::HTTP_OK);
    }

    public function assets_store(Request $request, string $impactId)
    {
        $impact = Impact::where('impact_id', '=', $impactId)->first();

        if ($impact == null) {
            return response([
                "Impact not found."
            ], Response::HTTP_NOT_FOUND);
        }

        $path = null;
        $file = null;

        if ($request->hasFile('image')) {

            $file = $request->file('image');

            if (!$file->isValid()) {
                return response()->json(['invalid_file_upload'], Response::HTTP_BAD_REQUEST);
            }

            $path = Storage::putFile('public/impact_assets', $file);
        }

        $impactAsset =  new ImpactAsset([
            "impact_id" => $impact->impact_id,
            "asset_url" => $path
        ]);

        $impactAsset->save();

        return response([
            "assetId" => $impactAsset->impact_asset_id
        ], Response::HTTP_CREATED);
    }

    public function assets_destroy(string $assetId)
    {
        $asset = ImpactAsset::where('impact_asset_id', '=', $assetId)->first();

        if ($asset == null) {
            return response([], Response::HTTP_NOT_FOUND);
        }

        $asset->delete();

        return response([], Response::HTTP_CREATED);
    }
}
