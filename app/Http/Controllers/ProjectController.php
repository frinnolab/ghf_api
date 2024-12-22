<?php

namespace App\Http\Controllers;

use App\Models\Profiles\Profile;
use App\Models\Programmes\Programmes;
use App\Models\Projects\Project;
use App\Models\Projects\ProjectAsset;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $projects = Project::latest()->get();

        $limit = $request->query('limit');


        if ($projects == null) {
            return response([], Response::HTTP_NO_CONTENT);
        }
        $response = [];

        if($limit > 0){
            $projects = $projects->take($limit);
        }


        foreach ($projects as $project) {
            $path = null;
            if($project->thumbnail_url != null || $project->thumbnail_url != ''){

                $path = asset(Storage::url($project->thumbnail_url));
            }
            $data = [
                'projectId' => $project->project_id,
                'programmeId' => $project->programme_id,
                'thumbnailUrl' =>  $path ?? null,
                'title' => $project->title,
                'description' => $project->description,
                'status' => $project->status,
                'regionsReached' => $project->regions_reached,
                'districtsReached' => $project->districts_reached,
                'schoolsReached' => $project->schools_reached,
                'studentsReached' => $project->students_reached,
                'dateStart' => $project->date_start ?? null,
                'dateEnd' => $project->date_end ?? null,
                'publisherId' => $project->publisher_profile_id,
            ];

            array_push($response, $data);
        }

        return response($response, Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        if ($request->all() === null) {
            return response('Request cannot be empty', Response::HTTP_BAD_REQUEST);
        }

        $creatorProfile = Profile::where('profile_id', '=', $request->input('publisherProfileId'))->first();

        if (!$creatorProfile) {
            return response("Profile not found!.", Response::HTTP_NOT_FOUND);
        }

        $creatorRoleType = intval($creatorProfile->roleType);

        switch ($creatorRoleType) {
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

        $programme =  DB::table('programs')->first();

        $path = null;
        $file = null;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            if (!$file->isValid()) {
                return response()->json(['invalid_file_upload'], Response::HTTP_BAD_REQUEST);
            }

            $path = Storage::putFile('public/project_assets', $file);
        }


        $newProject = new Project([
            'programme_id' => $programme->programme_id ?? null,
            'publisher_profile_id' => $creatorProfile->profile_id,
            'thumbnail_url' => $path ?? null,
            'title' => $request->input('title'),
            'status' => intval($request->input('status')) ,
            'description' => $request->input('description'),
            'regions_reached' => intval($request->input('regionsReached')) ,
            'districts_reached' => intval($request->input('districtsReached')) ,
            'schools_reached' => intval($request->input('schoolsReached')) ,
            'students_reached' => intval($request->input('studentsReached')) ,
            'date_start' => $request->input('dateStart') ?? null,
            'date_end' => $request->input('dateEnd') ?? null,
        ]);

        $newProject->save();

        $response = [
            'projectId' => $newProject->project_id
        ];

        return response($response, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $projectId)
    {
        //
        $project = Project::where('project_id', '=', $projectId)->first();

        if (!$project) {
            return response([], Response::HTTP_NOT_FOUND);
        }

        $path = null;
        if($project->thumbnail_url != null || $project->thumbnail_url != ''){

            $path = asset(Storage::url($project->thumbnail_url));
        }

        $response = [
            "projectId" => $project->project_id,
            "thumbnailUrl" => $path,
            "title" => $project->title,
            "description" => $project->description,
            "regionsReached" => $project->regions_reached,
            "districtsReached" => $project->districts_reached,
            "schoolsReached" => $project->schools_reached,
            "studentsReached" => $project->students_reached,
            "status" => $project->status,
            "dateStart" => $project->date_start,
            "dateEnd" => $project->date_end,
            "publisherId" => $project->publisher_profile_id,
            "programmeId" => $project->programme_id,
        ];

        return response($response, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $projectId)
    {
        //
        if ($request->all() === null) {
            return response('Request cannot be empty', Response::HTTP_BAD_REQUEST);
        }

        $creatorProfile = Profile::where('profile_id', '=', $request->input('publisherProfileId'))->first();

        if (!$creatorProfile) {
            return response("Profile not found!.", Response::HTTP_NOT_FOUND);
        }

        $creatorRoleType = intval($creatorProfile->roleType);

        switch ($creatorRoleType) {
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


        $path = null;
        $file = null;

        if ($request->hasFile('image')) {

            $file = $request->file('image');

            if (!$file->isValid()) {
                return response()->json(['invalid_file_upload'], Response::HTTP_BAD_REQUEST);
            }

            $path = Storage::putFile('public/project_assets', $file);
        }

        //
        $project = Project::where('project_id', '=', $projectId)->first();

        if (!$project) {
            return response([], Response::HTTP_NOT_FOUND);
        }

        $project->programme_id = $project->programme_id;
        $project->publisher_profile_id = $creatorProfile->profile_id ?? $project->publisher_profile_id;
        $project->thumbnail_url = $path ?? $project->thumbnail_url ?? null;
        $project->title = $request->input('title') ?? $project->title;
        $project->description = $request->input('description') ?? $project->description;
        $project->regions_reached = intval($request->input('regionsReached'))  ?? $project->regions_reached;
        $project->districts_reached = intval($request->input('districtsReached'))  ?? $project->districts_reached;
        $project->schools_reached = intval($request->input('schoolsReached'))  ?? $project->schools_reached;
        $project->students_reached = intval($request->input('studentsReached'))  ?? $project->students_reached;
        $project->date_start = $request->input('dateStart') ?? $project->date_start ?? null;
        $project->date_end =  $request->input('dateEnd') ?? $project->date_end ?? null;
        $project->status = intval($request->input('status'))  ?? $project->status;

        $project->save();

        return response([], Response::HTTP_CREATED);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $projectId)
    {
        //
        $project = Project::where('project_id', '=', $projectId)->first();

        if (!$project) {
            return response([], Response::HTTP_NOT_FOUND);
        }

        $project->delete();

        return response([], Response::HTTP_OK);
    }

    //Assets
    public function assets_index(string $projectId)
    {

        $response = [];
        //$request->validate(['impactId'=>'required']);

        if ($projectId == '' or $projectId == null) {
            return response(['projectId is required'], Response::HTTP_BAD_REQUEST);
        }

        $datas = ProjectAsset::where('project_id', '=', $projectId)->latest()->get();

        if ($datas == null) {
            return response([], Response::HTTP_NO_CONTENT);
        }

        foreach ($datas as $asset) {
            $imgUrl = null;
            if ($asset->asset_url != '' or $asset->asset_url != null) {
                $imgUrl = asset(Storage::url($asset->asset_url));
            }
            $data = [
                'assetId' => $asset->project_asset_id,
                'projectId' => $asset->project_id,
                'assetUrl' => $imgUrl,
                'videoUrl' => $asset->video_url
            ];

            array_push($response, $data);
        }

        return response($response, Response::HTTP_OK);
    }

    public function assets_show(string $assetId)
    {

        $data = ProjectAsset::where('project_asset_id', '=', $assetId)->first();

        if ($data == null) {
            return response([
                "Asset not found"
            ], Response::HTTP_NOT_FOUND);
        }

        $response = [
            "assetId" => $data->project_asset_id,
            "projectId" => $data->project_id,
            "assetUrl" => asset(Storage::url($data->asset_url)),
            'videoUrl' => $data->video_url
        ];
        return response($response, Response::HTTP_OK);
    }

    public function assets_store(Request $request, string $projectId)
    {
        $project = Project::where('project_id', '=', $projectId)->first();

        if ($project == null) {
            return response([
                "Project not found."
            ], Response::HTTP_NOT_FOUND);
        }

        $path = null;
        $file = null;

        if ($request->hasFile('image')) {

            $file = $request->file('image');

            if (!$file->isValid()) {
                return response()->json(['invalid_file_upload'], Response::HTTP_BAD_REQUEST);
            }

            $path = Storage::putFile('public/project_assets/assets', $file);
        }

        $projectAsset =  new ProjectAsset([
            "project_id" => $project->project_id,
            "asset_url" => $path,
            'video_url' => $request->input('videoUrl') 
        ]);

        $projectAsset->save();

        return response([
            "assetId" => $projectAsset->project_asset_id
        ], Response::HTTP_CREATED);
    }

    public function assets_destroy(string $assetId)
    {
        $asset = ProjectAsset::where('project_asset_id', '=', $assetId)->first();

        if ($asset == null) {
            return response([], Response::HTTP_NOT_FOUND);
        }

        $asset->delete();

        return response([], Response::HTTP_CREATED);
    }
    //Assets End
}
