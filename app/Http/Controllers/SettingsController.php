<?php

namespace App\Http\Controllers;

use App\Models\Profiles\Profile;
use App\Models\Projects\Project;
use App\Models\Settings\CompanyInfo;
use App\Models\Settings\StatisticsInfo;
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

    public function assetsInfoCreate(Request $request)
    {


        $data = CompanyInfo::first();

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

    //Stats Info
    public function statsInfoIndex()
    {
        $data = StatisticsInfo::first();

        if ($data == null) {
            return response([], Response::HTTP_NO_CONTENT);
        }

        $response = [
            'statId' => $data->stat_id,
            'regionsReached' => $data->regions_reached ?? 0,
            'districtsReached' => $data->districts_reached ?? 0,
            'studentsImpacted' =>  $data->students_impacted ?? 0,
            'schoolsReached' => $data->schools_reached ?? 0,
        ];
        return response($response, Response::HTTP_OK);
    }

    public function statsInfoStore(Request $request)
    {

        $newStat = new StatisticsInfo([
            "regions_reached" => intval($request->input('regionsReached')),
            "districts_reached" => intval($request->input('districtsReached')),
            "students_impacted" => intval($request->input('studentsImpacted')),
            "schools_reached" => intval($request->input('schoolsReached')),
        ]);

        $newStat->save();

        $response =
            [
                "statId" => $newStat->stat_id,
            ];


        return response($response, Response::HTTP_CREATED);
    }

    public function statsInfoUpdate(Request $request, string $statsId)
    {

        $response = [];

        $statData = StatisticsInfo::where('stat_id', '=', $statsId)->first();

        if ($statData == null) {
            return response('Statistics not found', Response::HTTP_NOT_FOUND);
        }



        $statData->regions_reached = intval($request->input('regionsReached') ?? $statData->regions_reached);
        $statData->districts_reached = intval($request->input('districtsReached') ?? $statData->districts_reached);
        $statData->students_impacted = intval($request->input('studentsImpacted') ?? $statData->students_impacted);
        $statData->schools_reached = intval($request->input('schoolsReached') ?? $statData->schools_reached);

        $statData->save();




        $response = [
            "statId" => $statData->stat_id,
        ];

        return response($response, Response::HTTP_CREATED);
    }

    public function statsInfoShow(Request $request, string $statsId)
    {
        $data = StatisticsInfo::where('stat_id', '=', $statsId)->first();

        if ($data == null) {
            return response(['Statistics data not found'], Response::HTTP_NOT_FOUND);
        }

        //format response
        $response = [
            'statId' => $data->stat_id,
            'regionsReached' => $data->regions_reached ?? 0,
            'districtsReached' => $data->districts_reached ?? 0,
            'studentsImpacted' =>  $data->students_impacted ?? 0,
            'schoolsReached' => $data->schools_reached ?? 0,
        ];
        return response($response, Response::HTTP_OK);
    }

    public function statsInfoDestroy(Request $request, string $statsId) {
        $data = StatisticsInfo::where('stat_id', '=', $statsId)->first();

        if ($data == null) {
            return response(['Alumni not found'], Response::HTTP_NOT_FOUND);
        }

        $data->delete();

        return response([
            "Statistic removed"
        ], Response::HTTP_CREATED);
    }
    //Stats Info End
}
