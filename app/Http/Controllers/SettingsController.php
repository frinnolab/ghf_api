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

        if ($data->logo_Url != null || "") {
            $logoAssetUrl = asset(Storage::url($data->logo_Url));
        }

        if ($data->intro_VideoUrl != null || "") {
            $videoAssetUrl = asset(Storage::url($data->intro_VideoUrl));
        }

        $response = [
            'id' => $data->id,
            'companyName' => $data->company_Name,
            'companyAddress' => $data->company_Address,
            'companyEmail' =>  $data->company_Email,
            'companyMobile' => $data->company_Mobile,
            'companyMobileTelephone' => $data->company_Mobile_Telephone,
            'companyMobileAltenate' => $data->company_Mobile_Altenate,
            'companyBiography' =>  $data->company_Biography,
            'companyMission' => $data->company_Mission,
            'companyVision' => $data->company_Vision,
            'logoUrl' => $logoAssetUrl,
            'introVideoUrl' =>  $videoAssetUrl
        ];
        return response($response, Response::HTTP_OK);
    }


    public function companyInfoUpdate(Request $request, string $infoId)
    {

        $data = CompanyInfo::where('id', '=', $infoId)->first();
        //$data = CompanyInfo::all()->first();

        if ($data == null) {
            return response([
                "CompanyInfo" => null
            ], Response::HTTP_NO_CONTENT);
        }

        $data->company_Name = $request->input('companyName');
        $data->company_Address = $request->input('companyAddress');
        $data->company_Email = $request->input('companyEmail');
        $data->company_Mobile = $request->input('companyMobile');
        $data->company_Mobile_Telephone = $request->input('companyMobileTelephone');
        $data->company_Mobile_Altenate = $request->input('companyMobileAltenate');
        $data->company_Biography = $request->input('companyBiography');
        $data->company_Mission = $request->input('companyMission');
        $data->company_Vision = $request->input('companyVision');

        $data->save();

        return response([], Response::HTTP_CREATED);
    }

    public function assetsInfoUpdate(Request $request, string $infoId)
    {


        $data = CompanyInfo::where('id', '=', $infoId)->first();

        $path = null;
        $file = null;

        if ($request->hasFile('imageAsset')) {

            $file = $request->file('imageAsset');
            if (!$file->isValid()) {
                return response()->json(['invalid_file_upload'], Response::HTTP_BAD_REQUEST);
            }

            $path = Storage::putFile('public/company_info/logo_assets', $file);

            $data->logo_Url = $path;
        }

        $videopath = null;
        $videofile = null;

        if ($request->hasFile('videoAsset')) {

            $videofile = $request->file('videoAsset');
            if (!$videofile->isValid()) {
                return response()->json(['invalid_file_upload'], Response::HTTP_BAD_REQUEST);
            }

            $videopath = Storage::putFile('public/company_info/video_assets', $videofile);
            $data->intro_VideoUrl = $videopath;
        }

        $data->save();

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
