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

        $response = [
            'id' => $data->id,
            'companyName' => $data->companyName,
            'companyAddress' => $data->companyAddress,
            'companyEmail' =>  $data->companyEmail,
            'companyMobile' => $data->companyMobile,
            'companyBiography' =>  $data->companyBiography,
            'companyMission' => $data->companyMission,
            'companyVision' => $data->companyVision,
            'logoUrl' => asset(Storage::url($data->logoUrl)),
            'introVideoUrl' => asset(Storage::url($data->introVideoUrl))
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

        if ($data == null) {
            return response([], Response::HTTP_NOT_FOUND);
        }

        $path = '';
        $file = null;

        if ($request->input('image')) {

            $file = $request->file('image');
            if (!$file->isValid()) {
                return response()->json(['invalid_file_upload'], Response::HTTP_BAD_REQUEST);
            }

            $path = Storage::putFile('public/company_info/logo', $file);
        }

        $videopath = '';
        $videofile = null;

        if ($request->input('video')) {

            $videofile = $request->file('video');
            if (!$file->isValid()) {
                return response()->json(['invalid_file_upload'], Response::HTTP_BAD_REQUEST);
            }

            $videopath = Storage::putFile('public/company_info/intro', $videofile);
        }

        $dataUpdate = [
            'companyName' => $request->input('companyName') ?? $data->companyName,
            'companyAddress' => $request->input('companyAddress') ?? $data->companyAddress,
            'companyEmail' => $request->input('companyEmail') ?? $data->companyEmail,
            'companyMobile' => json_encode($request->input('companyMobile'))  ?? $data->companyMobile,
            'companyBiography' => $request->input('companyBiography') ?? $data->companyBiography,
            'companyMission' => $request->input('companyMission') ?? $data->companyMission,
            'companyVision' => $request->input('companyVision') ?? $data->companyVision,
            'logoUrl' => $path ?? $data->logoUrl,
            'introVideoUrl' => $videopath ?? $data->introVideoUrl,
        ];

        $data->save($dataUpdate);

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
