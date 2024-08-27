<?php

namespace App\Http\Controllers;

use App\Models\Profiles\Profile;
use App\Models\Projects\Project;
use App\Models\Settings\CompanyInfo;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    //
    //region Company Info Endpoints
    //Get
    public function companyInfoIndex(Request $request)
    {
        $data = CompanyInfo::all()->first();

        if ($data == null) {
            return response([], Response::HTTP_NO_CONTENT);
        }

        $logoAssetUrl = null;
        $videoAssetUrl = null;

        if($data->logoUrl != null){
            $logoAssetUrl = asset(Storage::url($data->logoUrl));
        }

        if($data->introVideoUrl != null){
            $videoAssetUrl = asset(Storage::url($data->introVideoUrl));
        }

        $response = [
            'id' => $data->id,
            'companyName' => $data->companyName,
            'companyAddress' => $data->companyAddress,
            'companyEmail' =>  $data->companyEmail,
            'companyMobile' => $data->companyMobile,
            'companyMobileTelephone' => $data->companyMobile,
            'companyMobileAltenate' => $data->companyMobile,
            'companyBiography' =>  $data->companyBiography,
            'companyMission' => $data->companyMission,
            'companyVision' => $data->companyVision,
            'logoUrl' => $logoAssetUrl,
            'introVideoUrl' =>  $videoAssetUrl
        ];
        return response($response, Response::HTTP_OK);
    }

    //Post
    public function companyInfoCreate(string $adminId, Request $request)
    {
        $authorProfile = Profile::where('profile_id', '=', $adminId)->first();



        if ($authorProfile == null) {
            return response("Profile not found!.", Response::HTTP_NOT_FOUND);
        }

        $authorRoleType = intval($authorProfile->roleType);

        switch ($authorRoleType) {
            case -1:
                # code...
                break;
            case 1:
                # code...
                break;

            default:
                # code...
                return response("", Response::HTTP_UNAUTHORIZED);
                //break;
        }


        $data = CompanyInfo::all()->first();

        // if ($data == null) {
        //     return response([], Response::HTTP_NOT_FOUND);
        // }

        $path = '';
        $file = null;

        if ($request->hasFile('image')) {

            $file = $request->file('image');
            if (!$file->isValid()) {
                return response()->json(['invalid_file_upload'], Response::HTTP_BAD_REQUEST);
            }

            $path = Storage::putFile('public/company_info/logo', $file);
        }

        $videopath = '';
        $videofile = null;

        if ($request->hasFile('video')) {

            $videofile = $request->file('video');
            if (!$file->isValid()) {
                return response()->json(['invalid_file_upload'], Response::HTTP_BAD_REQUEST);
            }

            $videopath = Storage::putFile('public/company_info/intro', $videofile);
        }

        $dataUpdate = [
            'companyName' => $request->input('companyName'),
            'companyAddress' => $request->input('companyAddress'),
            'companyEmail' => $request->input('companyEmail'),
            'companyMobile' => $request->input('companyMobile'),
            'companyMobile' => $request->input('companyMobileTelephone'),
            'companyMobile' => $request->input('companyMobileAltenate'),
            'companyBiography' => $request->input('companyBiography'),
            'companyMission' => $request->input('companyMission'),
            'companyVision' => $request->input('companyVision'),
            'logoUrl' => $path ?? $data->logoUrl,
            'introVideoUrl' => $videopath ?? $data->introVideoUrl,
        ];

        $data->update($dataUpdate);

        return response([], Response::HTTP_CREATED);
    }

    public function companyInfoAssetsCreate(string $adminId, Request $request)
    {
        $authorProfile = Profile::where('profile_id', '=', $adminId)->first();

        if ($authorProfile == null) {
            return response("Profile not found!.", Response::HTTP_NOT_FOUND);
        }

        $authorRoleType = intval($authorProfile->roleType);

        switch ($authorRoleType) {
            case -1:
                # code...
                break;
            case 1:
                # code...
                break;

            default:
                # code...
                return response("", Response::HTTP_UNAUTHORIZED);
                //break;
        }

       
        $data = CompanyInfo::all()->first();

        $path = '';
        $file = null;

        if ($request->hasFile('imageAsset')) {

            $file = $request->file('imageAsset');
            if (!$file->isValid()) {
                return response()->json(['invalid_file_upload'], Response::HTTP_BAD_REQUEST);
            }

            $path = Storage::putFile('public/company_info/logo_assets', $file);
        }

        $videopath = '';
        $videofile = null;

        if ($request->hasFile('videoAsset')) {

            $videofile = $request->file('videoAsset');
            if (!$file->isValid()) {
                return response()->json(['invalid_file_upload'], Response::HTTP_BAD_REQUEST);
            }

            $videopath = Storage::putFile('public/company_info/video_assets', $videofile);
        }

        $dataUpdate = [
            'logoUrl' => $path,
            'introVideoUrl' => $videopath,
        ];

        $data->update($dataUpdate);

        return response([], Response::HTTP_CREATED);
    }
    //region Company Info Endpoints End

    //Summary Info Endpoints
    //Get
    public function summaryInfoIndex(Request $request)
    {
        $pjData = Project::all();
        $totProjects = $pjData->count() ??  0;
        $totRegions = 0;
        $totDistricts = 0;
        $totSchools = 0;
        $totStudents = 0;

        foreach ($pjData as $pj) {
            $totDistricts += $pj->districts_reached;
            $totRegions += $pj->regions_reached;
            $totSchools += $pj->schools_reached;
            $totStudents += $pj->students_reached;
        }
        $data =
            [
                "totalProjects" => $totProjects,
                "totalDistricts" => $totDistricts,
                "totalRegions" => $totRegions,
                "totalSchools" => $totSchools,
                "totalStudents" => $totStudents
            ];
        return response($data, Response::HTTP_OK);
    }
    //Summary Info Endpoints End
}
