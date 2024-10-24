<?php

namespace App\Http\Controllers;

use App\Models\Profiles\Profile;
use App\Models\Programmes\Programmes;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProgrammesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $programmes = Programmes::latest()->get();


        if ($programmes == null) {
            return response([], Response::HTTP_NO_CONTENT);
        }
        $response = [];


        foreach ($programmes as $programme) {
            $data = [
                'programmeId' => $programme->programme_id,
                'name' => $programme->name,
                'description' => $programme->description,
                // 'status' => $programme->status,
                // 'regionsReached' => $programme->regions_reached,
                // 'districtsReached' => $programme->districts_reached,
                // 'schoolsReached' => $programme->schools_reached,
                // 'studentsReached' => $programme->students_reached,
                'dateStart' => $programme->date_start,
                'dateEnd' => $programme->date_end,
                'publisherId' => $programme->publisher_profile_id,
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

        $newProgramme = new Programmes([
            'name'=>$request->input('name'),
            'description'=>$request->input('description'),
            // 'regions_reached'=>intval($request->input('regionsReached')),
            // 'districts_reached'=>intval($request->input('districtsReached')),
            // 'schools_reached'=>intval($request->input('schoolsReached')),
            // 'students_reached'=>intval($request->input('studentsReached')),
            'status'=>intval($request->input('status')),
            'date_start'=>$request->input('dateStart'),
            'date_end'=>$request->input('dateEnd'),
            'publisher_profile_id'=>$creatorProfile->profile_id,
        ]);

        $newProgramme->save();

        $response = [
            'programme_id'=>$newProgramme->programme_id
        ];

        return response($response, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $programmeId)
    {
        //
        $programme = Programmes::where('programme_id', '=', $programmeId)->first();

        if (!$programme) {
            return response([], Response::HTTP_NOT_FOUND);
        }

        return response([
            'programmeId' => $programme->programme_id,
            'name' => $programme->name,
            'description' => $programme->description,
            'status' => $programme->status,
            // 'regionsReached' => $programme->regions_reached,
            // 'districtsReached' => $programme->districts_reached,
            // 'schoolsReached' => $programme->schools_reached,
            // 'studentsReached' => $programme->students_reached,
            'dateStart' => $programme->date_start,
            'dateEnd' => $programme->date_end,
            'publisherId' => $programme->publisher_profile_id,
        ], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $programmeId)
    {
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
        $programme = Programmes::where('programme_id', '=', $programmeId)->first();

        if (!$programme) {
            return response([], Response::HTTP_NOT_FOUND);
        }

        $programme->name = $request->input('name') ?? $programme->name;
        $programme->description = $request->input('description') ?? $programme->description;
        $programme->status = $request->input('status') ?? $programme->status;
        $programme->date_start = $request->input('dateStart') ?? $programme->date_start;
        $programme->date_end = $request->input('dateEnd') ?? $programme->date_end;
        $programme->publisher_profile_id = $request->input('dateEnd') ?? $programme->publisher_profile_id;

        $programme->save();

        return response([], Response::HTTP_CREATED);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $programmeId)
    {
        //
        $programme = Programmes::where('programme_id','=', $programmeId)->first();

        if($programme){
            return response([], Response::HTTP_NOT_FOUND);
        }

        $programme->delete();

        return response([], Response::HTTP_OK);
    }


    //Programme Assets Management Endpoints
    
}
