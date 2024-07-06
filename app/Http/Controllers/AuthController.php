<?php

namespace App\Http\Controllers;

use App\Models\Profiles\Profile;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    //Login Profile
    public function login(Request $request)
    {
        if (!$request->all()) {
            return response("Body cannot be empty!.", Response::HTTP_BAD_REQUEST);
        }

        //Validate Email
        if (!$request->input('email')) {
            return response("Email is required!.", Response::HTTP_BAD_REQUEST);
        }

        //validate password
        if (!$request->input('password')) {
            return response("Password is required!.", Response::HTTP_FORBIDDEN);
        }

        $profile = Profile::where('email','=', $request->input('email'))->first();

        if($profile == null){
            return response("Profile not found!.",Response::HTTP_NOT_FOUND);
        }

        if($profile->tokens()){
            $profile->tokens()->delete();
        }

        if(!password_verify($request->input('password'), $profile->hashed_password)){
            return response("Passwords did not macth", 401);
        }

        $token = $profile->createToken($profile->email)->plainTextToken;


        return response([
            'profileId'=>$profile->profile_id,
            'token'=>$token,
            'email'=>$profile->email,
            'roleType'=>$profile->roleType
        ], Response::HTTP_OK);
    }

    //sign up
    public function signUp(Request $request)
    {

        if (!$request->all()) {
            return response("Body cannot be empty!.", Response::HTTP_FORBIDDEN);
        }

        //Validate Email
        if (!$request->input('email')) {
            return response("Email is required!.", Response::HTTP_BAD_REQUEST);
        }

        //validate Firstname
        if (!$request->input('firstname')) {
            return response("Firstname is required!.", Response::HTTP_BAD_REQUEST);
        }

        
        //validate password
        if (!$request->input('password')) {
            return response("Password is required!.", Response::HTTP_BAD_REQUEST);
        }

        //validate RoleType
        // if (!$request->input('roleType')) {
        //     return response("Role is required!.", Response::HTTP_BAD_REQUEST);
        // }

        $activeProfile = Profile::where('email','=', $request->input('email'))->first();

        if($activeProfile != null){
            return response('Profile already exists!.', Response::HTTP_FOUND);
        }

        $roleType = intval($request->input('roleType') ?? 0);

        if($roleType == -1 || $roleType == 1){
            //SuperAdmin && Admin
            return response("", Response::HTTP_UNAUTHORIZED);
        }

        $profile = new Profile([
            'firstname' => $request->input('firstname'),
            'email' => $request->input('email'),
            'hashed_password' => password_hash($request->input('password'), HASH_HMAC),
            'roleType' => $roleType,
        ]);

        $profile->save();

        if (!$profile) {
            return response("Failed to create a new profile.", Response::HTTP_BAD_REQUEST);
        }

        $response = [
            'profileId'=>$profile->profile_id
        ];

        return response($response, Response::HTTP_CREATED);
    }

    // Reset Pass
    public function resetPassword(Request $request)
    {

    }
}
