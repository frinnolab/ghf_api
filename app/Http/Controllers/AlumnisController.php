<?php

namespace App\Http\Controllers;

use App\Models\Alumnis\Alumni;
use App\Models\Profiles\Profile;
use App\Models\Profiles\ProfileRoles;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class AlumnisController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $alumnis = [];
        $response = [];
        $isPublished = boolval($request->query('isPublished') ?? false);

        if($isPublished){
            $alumnis = Alumni::where('is_published', '=', $isPublished)->latest()->get();
        }else{
            $alumnis = Alumni::latest()->get();
        }




        if ($alumnis == null) {
            return response($response, Response::HTTP_NO_CONTENT);
        }

        $imgUrl = null;
        $profile = null;
        foreach ($alumnis as $alumni) {

            if ($alumni->profile_id != null) {

                // $profile = Profile::where("profile_id", "=", $alumni->profile_id)->get();
                $profile = DB::table('profiles')->where('profile_id', '=', $alumni->profile_id)->first();
            }

            if ($profile->avatar_url != null ||  $profile->avatar_url != "") {
                $imgUrl = asset(Storage::url($profile->avatar_url));
            }

            $profileData = [
                'profileId' => $profile->profile_id,
                'avatarUrl' => $imgUrl,
                'email' => $profile->email ?? null,
                'firstname' => $profile->firstname ?? null,
                'lastname' => $profile->lastname ?? null,
                'position' => $profile->position ?? null,
                'mobile' => $profile->mobile ?? null,
                'roleType' => $profile->roleType ?? null,
            ];


            $data = [
                "alumniId" => $alumni->alumni_id,
                "profileId" => $alumni->profile_id,
                "alumniProfile" => $profileData,
                "age" => $alumni->age,
                "participationSchool" => $alumni->participation_school,
                "participationYear" => $alumni->participation_year,
                "currenctOccupation" => $alumni->currenct_occupation,
                "story" => $alumni->story,
                "isPublished" => $alumni->is_published,
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
        $response = [];
        //
        if (!$request->input('roleType')) {
            return response('Role Type is required', Response::HTTP_BAD_REQUEST);
        }

        $userRole = ProfileRoles::where('type', '=', intval($request->input('roleType')))->first();

        if (!$userRole) {
            return response('Role not found', Response::HTTP_NOT_FOUND);
        }
        if (intval($userRole->type) != 2) {
            return response('Role not Authorized', Response::HTTP_UNAUTHORIZED);
        }

        $path = null;
        $file = null;

        if ($request->hasFile('avatar')) {

            $file = $request->file('avatar');
            if (!$file->isValid()) {
                return response()->json(['invalid_file_upload'], Response::HTTP_BAD_REQUEST);
            }

            $path = Storage::putFile('public/alumni', $file);
        }

        //Default New User Password = email
        $newProfile = new Profile([
            'avatar_url' => $path ?? null,
            'firstname' => $request->input('firstname'),
            'lastname' => $request->input('lastname'),
            'position' => $request->input('position'),
            'mobile' => $request->input('mobile'),
            'email' => $request->input('email'),
            'hashed_password' => password_hash($request->input('email'), HASH_HMAC),
            'roleType' => $userRole->type,
        ]);

        $newProfile->save();

        if ($newProfile == null) {
            return response([
                'Failed to create alumni profile'
            ], Response::HTTP_BAD_REQUEST);
        }

        $newAlumni = new Alumni([
            "profile_id" => $newProfile->profile_id,
            "age" => intval($request->input('age')),
            "participation_school" => $request->input('participationSchool'),
            "participation_year" => intval($request->input('participationYear')),
            "currenct_occupation" => $request->input('currenctOccupation'),
            "story" => $request->input('story'),
            "is_published" => boolval($request->input('isPublished') ?? false) 
        ]);

        $newAlumni->save();

        $response =
            [
                "alumniId" => $newAlumni->alumni_id,
                "profileId" => $newAlumni->profile_id
            ];


        return response($response, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, string $profileId)
    {
        //
        $response = [];
        $imgUrl = null;
        $alumni = Alumni::where('alumni_id', '=', $id)->first();
        // $profile = null;

        if ($alumni == null) {
            return response(['Alumni not found'], Response::HTTP_NOT_FOUND);
        }

        // $alumniProfile = Profile::where('profile_id', '=', $profileId)->first();

        $alumniProfile = DB::table('profiles')->where('profile_id', '=', $profileId)->first();

        if ($alumniProfile == null) {

            return response(['Alumni Profile not found'], Response::HTTP_NOT_FOUND);
        }


        if ($alumniProfile->avatar_url) {

            $imgUrl = asset(Storage::url($alumniProfile->avatar_url));
        }

        $profileData = [
            'profileId' => $alumniProfile->profile_id,
            'avatarUrl' => $imgUrl,
            'email' => $alumniProfile->email,
            'mobile' => $alumniProfile->mobile,
            'firstname' => $alumniProfile->firstname,
            'lastname' => $alumniProfile->lastname,
            'position' => $alumniProfile->position,
            'roleType' => $alumniProfile->roleType,
        ];

        //format response
        $response = [
            "profileId" => $alumni->profile_id,
            "alumniProfile" => $profileData,
            "age" => $alumni->age,
            "participationSchool" => $alumni->participation_school,
            "participationYear" => $alumni->participation_year,
            "currenctOccupation" => $alumni->currenct_occupation,
            "story" => $alumni->story,
            "isPublished" => $alumni->is_published,
        ];


        return response($response, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $response = [];

        $alumni = Alumni::where('alumni_id', '=', $id)->first();

        if ($alumni == null) {
            return response('Alumni not found', Response::HTTP_NOT_FOUND);
        }



        $alumni->age = intval($request->input('age') ?? $alumni->age);
        $alumni->participation_school = $request->input('participationSchool') ?? $alumni->participation_school;
        $alumni->participation_year = intval($request->input('participationYear') ?? $alumni->participation_year);
        $alumni->currenct_occupation = $request->input('currenctOccupation') ?? $alumni->currenct_occupation;
        $alumni->story = $request->input('story') ?? $alumni->story;
        // $alumni->is_published = intval($request->input('isPublished') ?? $alumni->is_published ?? 0) ;

        $alumni->save();




        $response = [
            "alumniId" => $alumni->alumni_id,
            "profileId" => $alumni->profile_id
        ];

        return response($response, Response::HTTP_CREATED);
    }

    public function updatePublish(Request $request, string $id)
    {
        //
        $response = [];

        $alumni = Alumni::where('alumni_id', '=', $id)->first();

        if ($alumni == null) {
            return response('Alumni not found', Response::HTTP_NOT_FOUND);
        }

        $alumni->is_published = boolval($request->input('isPublished') ?? $alumni->is_published ?? false) ;

        $alumni->save();




        $response = [
            "alumniId" => $alumni->alumni_id,
            "profileId" => $alumni->profile_id
        ];

        return response($response, Response::HTTP_CREATED);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $alumni = Alumni::where('alumni_id', '=', $id)->first();

        if ($alumni == null) {
            return response(['Alumni not found'], Response::HTTP_NOT_FOUND);
        }

        $profile = Profile::where('profile_id', '=', $alumni->profile_id)->first();


        if ($profile != null) {
            //return response(['Alumni Profile not found'], Response::HTTP_NOT_FOUND);
            $profile->delete();
        }


        $alumni->delete();

        return response([
            "Alumni removed"
        ], Response::HTTP_CREATED);
    }
}
