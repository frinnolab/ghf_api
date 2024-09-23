<?php

namespace App\Http\Controllers;

use App\Models\Alumnis\Alumni;
use App\Models\Profiles\Profile;
use App\Models\Profiles\ProfileRoles;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class AlumnisController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $response = [];
        $alumnis = Alumni::all();

        if ($alumnis == null) {
            return response($response, Response::HTTP_NO_CONTENT);
        }

        foreach ($alumnis as $alumni) {
            $imgUrl = null;
            $profile = Profile::where('profile_id', '=', $alumni->profile_id)->first();

            if($profile == null){
                return response(["Profile not found."], Response::HTTP_NOT_FOUND);
            }
            if ($profile->avatar_url != '' or $profile->avatar_url != null) {
                $imgUrl = asset(Storage::url($profile->avatar_url));
            }

            $profileData = [
                'profileId' => $profile->profile_id,
                'avatarUrl' => $imgUrl,
                'email' => $profile->email,
                'firstname' => $profile->firstname,
                'lastname' => $profile->lastname,
                'position' => $profile->position,
                'mobile' => $profile->mobile,
                'roleType' => $profile->roleType,
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

        $path = '';
        $file = null;

        if ($request->hasFile('avatar')) {

            $file = $request->file('avatar');
            if (!$file->isValid()) {
                return response()->json(['invalid_file_upload'], Response::HTTP_BAD_REQUEST);
            }

            $path = Storage::putFile('public/avatars', $file);
        }

        //Default New User Password = email
        $newProfile = new Profile([
            'avatar_url' => $path ?? null,
            'firstname' => $request->input('firstname'),
            'lastname' => $request->input('lastname'),
            'position' => $request->input('position') ?? null,
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
            "story" => $request->input('story')
        ]);

        $newAlumni->save();

        $response =
            [
                "alumniId" => $newAlumni->alumni_id,
                "profileId" => $newProfile->profile_id
            ];


        return response($response, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $response = [];
        $alumni = Alumni::where('alumni_id', '=', $id)->first();

        if ($alumni == null) {
            return response(['Alumni not found'], Response::HTTP_NOT_FOUND);
        }

        $profile = Profile::where('profile_id', '=', $alumni->profile_id)->first();


        if ($profile == null) {
            return response(['Alumni Profile not found'], Response::HTTP_NOT_FOUND);
        }

        $imgUrl = null;
        if ($profile->avatar_url != null || $profile->avatar_url != '') {

            $imgUrl = asset(Storage::url($profile->avatar_url));
        }

        $profileData = [
            'profileId' => $profile->profile_id,
            'avatarUrl' => $imgUrl,
            'email' => $profile->email ?? '',
            'mobile' => $profile->mobile ?? '',
            'firstname' => $profile->firstname ?? '',
            'lastname' => $profile->lastname ?? '',
            'position' => $profile->position ?? '',
            'roleType' => $profile->roleType,
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

        $alumni->age = intval($request->input('age') ?? $alumni->age);
        $alumni->participation_school = $request->input('participationSchool') ?? $alumni->participation_school;
        $alumni->participation_year = intval($request->input('participationYear'))  ?? $alumni->participation_year;
        $alumni->currenct_occupation = $request->input('currenctOccupation') ?? $alumni->currenct_occupation;
        $alumni->story = $request->input('story') ?? $alumni->story;

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


        if ($profile == null) {
            return response(['Alumni Profile not found'], Response::HTTP_NOT_FOUND);
        }

        $profile->delete();

        $alumni->delete();

        return response([
            "Alumni removed"
        ], Response::HTTP_CREATED);
    }
}
