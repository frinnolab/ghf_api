<?php

namespace App\Http\Controllers;

use App\Models\Profiles\Profile;
use App\Models\Programmes\Programmes;
use App\Models\Projects\Project;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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
            $data = [
                'projectId' => $project->project_id,
                'programmeId' => $project->programme_id,
                'name' => $project->name,
                'description' => $project->description,
                'status' => $project->status,
                'regionsReached' => $project->regions_reached,
                'districtsReached' => $project->districts_reached,
                'schoolsReached' => $project->schools_reached,
                'studentsReached' => $project->students_reached,
                'dateStart' => $project->districts_reached,
                'dateEnd' => $project->districts_reached,
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

        $creatorRoleType = $creatorProfile->roleType;

        if ($creatorRoleType != -1 || $creatorRoleType != 1) {
            return response("", Response::HTTP_UNAUTHORIZED);
        }

        $programme = Programmes::first();

        $newProject = new Project([
            'programme_id'=> $programme->programme_id,
            'publisher_profile_id'=> $creatorProfile->profile_id,
            'title'=> $request->input('title'),
            'description'=> $request->input('description'),
            'regions_reached'=> $request->input('regionsReached'),
            'districts_reached'=> $request->input('districtsReached'),
            'schools_reached'=> $request->input('schoolsReached'),
            'students_reached'=> $request->input('studentsReached'),
            'date_start'=> $request->input('dateStart'),
            'date_end'=> $request->input('dateEnd'),
        ]);

        $newProject->save();

        $response = [
            'projectId'=>$newProject->project_id
        ];

        return response($response, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $projectId)
    {
        //
        $project = Project::where('project_id','=', $projectId)->first();

        if(!$project){
            return response([], Response::HTTP_NOT_FOUND);
        }

        return response([$project], Response::HTTP_OK);
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

        $creatorRoleType = $creatorProfile->roleType;

        if ($creatorRoleType != -1 || $creatorRoleType != 1) {
            return response("", Response::HTTP_UNAUTHORIZED);
        }
        //
        $project = Project::where('project_id', '=', $projectId)->first();

        if (!$project) {
            return response([], Response::HTTP_NOT_FOUND);
        }

        $project->programme_id = $project->programme_id;
        $project->publisher_profile_id = $creatorProfile->profile_id ?? $project->publisher_profile_id;
        $project->title = $request->input('title') ?? $project->title;
        $project->description = $request->input('description') ?? $project->description;
        $project->regions_reached = $request->input('regionsReached') ?? $project->regions_reached;
        $project->districts_reached = $request->input('districtsReached') ?? $project->districts_reached;
        $project->schools_reached = $request->input('schoolsReached') ?? $project->schools_reached;
        $project->students_reached = $request->input('studentsReached') ?? $project->students_reached;
        $project->date_start = $request->input('dateStart') ?? $project->date_start;
        $project->date_end =  $request->input('dateEnd') ?? $project->date_end;

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

        return response([],Response::HTTP_OK);
    }
}
