<?php

namespace App\Http\Controllers;

use App\Models\Alumnis\Alumni;
use App\Models\Profiles\Profile;
use App\Models\Profiles\ProfileRoles;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $profiles = Profile::latest()->get();

        if (!$profiles) {
            return response([], Response::HTTP_NO_CONTENT);
        }

        //Search by email
        if ($request->query('email')) {
            $profiles = Profile::where('email', 'like', '%' . $request->query('email') . '%')->get();
        }


        $response = [];

        foreach ($profiles as $profile) {
            $imgUrl = null;

            if ($profile->avatar_url) {
                $imgUrl = asset(Storage::url($profile->avatar_url));
            }

            $data = [
                'profileId' => $profile->profile_id,
                'avatarUrl' => $profile->$imgUrl,
                'email' => $profile->email,
                'firstname' => $profile->firstname,
                'lastname' => $profile->lastname,
                'position' => $profile->position,
                'mobile' => $profile->mobile,
                'biography' => $profile->biography,
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

        if ($creatorProfile == null) {
            return response("Creator Profile not found!.", Response::HTTP_NOT_FOUND);
        }


        if (intval($creatorProfile->roleType) != -1) {
            return response("You're not Authorised to perform this action as a Superadmin.", Response::HTTP_UNAUTHORIZED);
        }

        //RoleType For New User

        if ($request->input('roleType') == null) {
            return response('Role Type is required', Response::HTTP_BAD_REQUEST);
        }

        $userRole = ProfileRoles::where('type', '=', intval($request->input('roleType')))->first();

        if (!$userRole) {
            return response('Role not found', Response::HTTP_NOT_FOUND);
        }

        $path = null;
        $file = null;

        if ($request->file('avatar')) {

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
            'position' => $request->input('position'),
            'mobile' => $request->input('mobile'),
            'email' => $request->input('email'),
            'biography' => $request->input('biography'),
            'hashed_password' => password_hash($request->input('email'), HASH_HMAC),
            'roleType' => $userRole->type
        ]);

        $newProfile->save();

        //add alumni
        if (intval($newProfile->roleType) == 2) {
            $alumni = new Alumni([
                'profile_id' => $newProfile->profile_id,
                'is_published' => false
            ]);

            $alumni->save();
        }

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
        if (!$profileId) {
            return response('ProfileId is required', Response::HTTP_BAD_REQUEST);
        }

        $profile = Profile::where('profile_id', '=', $profileId)->first();

        if ($profile == null) {
            return response('Profile not found!.', Response::HTTP_NOT_FOUND);
        }

        $imgUrl = null;

        if ($profile->avatar_url) {

            $imgUrl = asset(Storage::url($profile->avatar_url));
        }

        $response = [
            'profileId' => $profile->profile_id,
            'avatarUrl' => $imgUrl,
            'email' => $profile->email ?? '',
            'mobile' => $profile->mobile ?? '',
            'firstname' => $profile->firstname ?? '',
            'lastname' => $profile->lastname ?? '',
            'position' => $profile->position ?? '',
            'roleType' => $profile->roleType,
            'biography' => $profile->biography,

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

        if (!$profile) {
            return response('Profile not found!.', Response::HTTP_NOT_FOUND);
        }

        $profile->firstname = $request->input('firstname') ?? $profile->firstname;
        $profile->lastname = $request->input('lastname') ?? $profile->lastname;
        $profile->email = $request->input('email') ?? $profile->email;
        $profile->mobile = $request->input('mobile') ?? $profile->mobile;
        $profile->position = $request->input('position') ?? $profile->position;
        $profile->roleType = intval($request->input('roleType')) ?? $profile->roleType;
        $profile->biography = $request->input('biography')?? $profile->biography;


        if ($request->input('password')) {
            $profile->hashed_password = $request->input('password') != '' ? password_hash($request->input('password'), HASH_HMAC) : $profile->hashed_password;
        }

        $path = null;
        $file = null;

        if ($request->file('avatar')) {

            $file = $request->file('avatar');
            if (!$file->isValid()) {
                return response()->json(['invalid_file_upload'], Response::HTTP_BAD_REQUEST);
            }

            $path = Storage::putFile('public/avatars', $file);
        }


        $profile->avatar_url = $path;

        $profile->save();

        //add alumni
        if (intval($profile->roleType) == 2) {

            //Find Alumni

            $alumni = Alumni::where('profile_id', '=', $profile->profile_id)->first();

            if ($alumni == null) {

                $alumniData = new Alumni([
                    'profile_id' => $profile->profile_id,
                    'is_published' => false
                ]);

                $alumniData->save();
            }
        }

        $response = [
            "profileId" => $profile->profile_id
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

        if (intval($profile->roleType) == 2) {
            //Alumni

            $alumni = Alumni::where('profile_id', '=', $profile->profile_id)->first();

            if ($alumni) {
                $alumni->delete();
            }
        }

        if (!$profile) {
            return response('Profile not found!.', Response::HTTP_NOT_FOUND);
        }

        if ($profile->tokens()) {
            $profile->tokens()->delete();
        }

        $profile->delete();

        return response('', Response::HTTP_CREATED);
    }
}
