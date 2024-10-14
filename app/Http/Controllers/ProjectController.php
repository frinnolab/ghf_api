<?php

namespace App\Http\Controllers;

use App\Models\Profiles\Profile;
use App\Models\Programmes\Programmes;
use App\Models\Projects\Project;
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
        $projects = Project::all();


        if ($projects == null) {
            return response([], Response::HTTP_NO_CONTENT);
        }
        $response = [];


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
}
