<?php

namespace App\Http\Controllers;

use App\Models\Profiles\Profile;
use App\Models\Profiles\ProfileRoles;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $profiles = Profile::all();

        // //Query by email
        // if($request->query('email')){
        //     response([
        //         'withEmail'=>$request->query('email')
        //     ],200);
        //     $profiles = $profiles->where('email','=',$request->query('email'));
        // }

        // //Query by Position
        // if($request->query('position')){
        //     $profiles = $profiles->where('position','=',$request->query('position'));
        // }

        
        if($profiles == null){
            return response([], Response::HTTP_NO_CONTENT);
        }
        $response = [];


        foreach ($profiles as $profile) {
            $data = [
                'profileId' => $profile->profile_id,
                'email' => $profile->email,
                'firstname' => $profile->firstname,
                'lastname' => $profile->lastname,
                'position' => $profile->position,
                'mobile' => $profile->mobile,
                'roleType' => $profile->roleType,
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

        $creatorProfile = Profile::where('profile_id', '=', $request->input('creatorProfileId'))->first();

        if (!$creatorProfile) {
            return response("Profile not found!.", Response::HTTP_NOT_FOUND);
        }

        $creatorRoleType = $creatorProfile->roleType;

        if ($creatorRoleType != -1 || $creatorRoleType != 1) {
            return response("", Response::HTTP_UNAUTHORIZED);
        }

        //RoleType For New User

        if (!$request->input('roleType')) {
            return response('Role Type is required', Response::HTTP_BAD_REQUEST);
        }

        $userRole = ProfileRoles::where('type', '=', intval($request->input('roleType')))->first();

        if (!$userRole) {
            return response('Role not found', Response::HTTP_NOT_FOUND);
        }

        //Default New User Password = email
        $newProfile = new Profile([
            'firstname' => $request->input('firstname'),
            'lastname' => $request->input('lastname'),
            'position' => $request->input('position'),
            'mobile' => $request->input('mobile'),
            'email' => $request->input('email'),
            'hashed_password' => password_hash($request->input('email'), HASH_HMAC),
            'roleType' => $userRole->roleType,
        ]);

        $newProfile->save();

        $response = [
            'profileId' => $newProfile->profileId,
            'creatorProfileId' => $creatorProfile->profile_id ?? $newProfile->profile_id
        ];

        return response($response, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $profileId)
    {
        //
        if ($profileId == '') {
            return response('ProfileId is required', Response::HTTP_BAD_REQUEST);
        }

        $profile = Profile::where('profile_id', '=', $profileId)->first();

        if ($profile == null) {
            return response('Profile not found!.', Response::HTTP_NOT_FOUND);
        }
        $response = [
            'profileId' => $profile->profile_id,
            'email' => $profile->email ?? '',
            'mobile' => $profile->mobile ?? '',
            'firstname' => $profile->firstname ?? '',
            'lastname' => $profile->lastname ?? '',
            'position' => $profile->position ?? '',
            'roleType' => $profile->roleType,
        ];

        return response($response, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $profileId)
    {
        //
        $profile = Profile::where('profile_id', '=', $profileId)->first();

        if(!$profile){
            return response('Profile not found!.', Response::HTTP_NOT_FOUND);
        }

        $profile->firstname = $request->input('firstname');
        $profile->lastname = $request->input('lastname');
        $profile->email = $request->input('email');
        $profile->mobile = $request->input('mobile');
        $profile->position = $request->input('position');
        $profile->hashed_password = $request->input('password') != '' ? password_hash($request->input('password'), HASH_HMAC) : $profile->hashed_password;

        $profile->save();

        $response = [
            "profileId"=>$profile->profile_id
        ];

        return response($response, Response::HTTP_CREATED);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $profileId)
    {
        //
        $profile = Profile::where('profile_id', '=', $profileId)->first();

        if(!$profile){
            return response('Profile not found!.', Response::HTTP_NOT_FOUND);
        }

        if($profile->tokens()){
            $profile->tokens()->delete();
        }

        $profile->delete();

        return response('', Response::HTTP_CREATED);
    }
}
