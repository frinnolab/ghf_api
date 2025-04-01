<?php

namespace App\Http\Controllers;

use App\Models\Careers\Career;
use App\Models\Careers\CareerApplication;
use App\Models\Profiles\Profile;
use App\Models\Profiles\ProfileRoles;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class CareersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $careers = Career::latest()->get();

        if ($careers == null) {
            return response([], Response::HTTP_NO_CONTENT);
        }

        if ($request->query("validity") == 1) {
            $careers = $careers->where('career_validity', '=', 1);
        }

        $data = [];

        //format response
        foreach ($careers as $ca) {
            $d = [
                'careerId' => $ca->career_id,
                'position' => $ca->position,
                'description' => $ca->description,
                'requirements' => $ca->requirements,
                'careerType' => $ca->career_type,
                'careerValidity' => $ca->career_validity,
            ];

            array_push($data, $d);
        }

        return response($data, Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //

        $newCar = new Career([
            'position' => $request->input('position'),
            'description' => $request->input('description'),
            'requirements' => $request->input('requirements'),
            'career_type' => intval($request->input('careerType') ?? 0),
            'career_validity' => intval($request->input('careerValidity') ?? 0)
        ]);

        $newCar->save();

        return response([], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $career = Career::where('career_id', '=', $id)->first();

        if ($career == null) {
            return response([
                'Career not found.'
            ], Response::HTTP_NOT_FOUND);
        }

        $data = [
            'careerId' => $career->career_id,
            'position' => $career->position,
            'description' => $career->description,
            'requirements' => $career->requirements,
            'careerType' => intval($career->career_type ?? 0),
            'careerValidity' => intval($career->career_validity ?? 0)
        ];

        return response($data, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $career = Career::where('career_id', '=', $id)->first();

        if ($career == null) {
            return response([
                'Career not found.'
            ], Response::HTTP_NOT_FOUND);
        }

        $career->position = $request->input('position');
        $career->description = $request->input('description');
        $career->career_type = intval($request->input('careerType') ?? $career->career_type);
        $career->career_validity = intval($request->input('careerValidity') ?? $career->career_validity ?? 0);
        $career->requirements = $request->input('requirements');

        $career->save();

        return response([], Response::HTTP_CREATED);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $career = Career::where('career_id', '=', $id)->first();

        if ($career == null) {
            return response([
                'Career not found.'
            ], Response::HTTP_NOT_FOUND);
        }

        $capps = CareerApplication::where('career_app_id', '=', $career->career_id)->get();

        foreach ($capps as $cap) {
            $cap->delete();
        }

        $career->delete();

        return response([], Response::HTTP_CREATED);
    }

    /**
     * Display a listing of the resource.
     */
    public function applications_index(Request  $request, string $id)
    {
        //
        $career = Career::where('career_id', '=', $id)->first();

        if ($career == null) {
            return response([
                'Career not found.'
            ], Response::HTTP_NOT_FOUND);
        }

        $career_apps = CareerApplication::where('career_id', '=', $career->career_id)->latest()->get();

        if ($career_apps == null) {
            return response([], Response::HTTP_NO_CONTENT);
        }

        $data = [];

        //format response
        foreach ($career_apps as $ca) {
            $imgUrl = null;
            $cvUrl = null;

            if ($ca->avatar_url) {
                $imgUrl = asset(Storage::url($ca->avatar_url));
            }

            if ($ca->cv_url) {
                $cvUrl = asset(Storage::url($ca->cv_url));
            }
            $d = [
                'careerAppId' => $ca->career_app_id,
                'careerId' => $ca->career_id,
                'avatarUrl' => $imgUrl,
                'cvUrl' => $cvUrl,
                'email' => $ca->email,
                'firstname' => $ca->firstname,
                'lastname' => $ca->lastname,
                'mobile' => $ca->mobile,
                'biography' => $ca->biography ?? null,
                'city' => $ca->city,
                'country' => $ca->country,
                'careerStatus' => $ca->career_status,
                'careerRoleType' => $ca->career_role_type,
            ];

            array_push($data, $d);
        }

        return response($data, Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function applications_store(Request $request, string $id)
    {
        //
        $career = Career::where('career_id', '=', $id)->first();

        if ($career == null) {
            return response([
                'Career not found!.'
            ], Response::HTTP_NOT_FOUND);
        }

        $imgUrl = null;
        $file = null;

        if ($request->hasFile('avatar')) {

            $file = $request->file('avatar');
            if (!$file->isValid()) {
                return response()->json(['invalid_file_upload'], Response::HTTP_BAD_REQUEST);
            }

            $imgUrl = Storage::putFile('public/profiles', $file);
        }

        //CV upload
        $cvUrl = null;
        $cvFile = null;

        if ($request->hasFile('cv')) {

            $cvFile = $request->file('cv');
            if (!$cvFile->isValid()) {
                return response()->json(['invalid_file_upload'], Response::HTTP_BAD_REQUEST);
            }

            $cvUrl = Storage::putFile('public/career_assets', $cvFile);
        }

        $newCarApp = new CareerApplication([
            'career_id' => $career->career_id,
            'avatar_url' => $imgUrl,
            'cv_url' => $cvUrl,
            'email' => $request->input('email'),
            'firstname' => $request->input('firstname'),
            'lastname' => $request->input('lastname'),
            'mobile' => $request->input('mobile'),
            'biography' => $request->input('biography') ?? null,
            'city' => $request->input('city'),
            'country' => $request->input('country'),
            'career_status' => intval($request->input('careerStatus') ?? 0),
            'career_role_type' => intval($request->input('careerRoleType') ?? 4)
        ]);

        $newCarApp->save();

        return response([], Response::HTTP_CREATED);
    }

    /**
     * Store a newly Volunteer created resource in storage.
     */
    public function application_store(Request $request)
    {
        //Find Volunteer Career.
        $career = Career::where('career_type', '=', 0)->first();
        $newCareer = null;

        if ($career == null) {
            // return response([
            //     'Career not found!.'
            // ], Response::HTTP_NOT_FOUND);

            //Create Volunteer Career

            $newCareer = new Career([
                'position' => $request->input('position') ?? "VOLUNTEERING",
                'description' => $request->input('description'),
                'requirements' => $request->input('requirements'),
                'career_type' => intval($request->input('careerType') ?? 0),
                'career_validity' => intval($request->input('careerValidity') ?? 0)
            ]);

            $newCareer->save();
        } else {
            $newCareer = $career;
        }

        $imgUrl = null;
        $file = null;

        if ($request->hasFile('avatar')) {

            $file = $request->file('avatar');
            if (!$file->isValid()) {
                return response()->json(['invalid_file_upload'], Response::HTTP_BAD_REQUEST);
            }

            $imgUrl = Storage::putFile('public/profiles', $file);
        }

        //CV upload
        $cvUrl = null;
        $cvFile = null;

        if ($request->hasFile('cv')) {

            $cvFile = $request->file('cv');
            if (!$cvFile->isValid()) {
                return response()->json(['invalid_file_upload'], Response::HTTP_BAD_REQUEST);
            }

            $cvUrl = Storage::putFile('public/career_assets', $cvFile);
        }

        $newCarApp = new CareerApplication([
            'career_id' => $newCareer->career_id,
            'avatar_url' => $imgUrl,
            'cv_url' => $cvUrl,
            'email' => $request->input('email'),
            'firstname' => $request->input('firstname'),
            'lastname' => $request->input('lastname'),
            'mobile' => $request->input('mobile'),
            'biography' => $request->input('biography') ?? null,
            'city' => $request->input('city'),
            'country' => $request->input('country'),
            'career_status' => intval($request->input('careerStatus') ?? 0),
            'career_role_type' => intval($request->input('careerRoleType') ?? 4)
        ]);

        $newCarApp->save();

        return response([], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function applications_show(string $id)
    {
        //
        $career = CareerApplication::where('career_app_id', '=', $id)->first();

        if ($career == null) {
            return response([
                'Application not found!.'
            ], Response::HTTP_NOT_FOUND);
        }

        $imgUrl = null;

        if ($career->avatar_url) {
            $imgUrl = asset(Storage::url($career->avatar_url));
        }

        $cvUrl = null;

        if ($career->cv_url) {
            $cvUrl = asset(Storage::url($career->cv_url));
        }

        $data = [
            'careerAppId' => $career->career_app_id,
            'careerId' => $career->career_id,
            'avatarUrl' => $imgUrl,
            'cvUrl' => $cvUrl,
            'email' => $career->email,
            'firstname' => $career->firstname,
            'lastname' => $career->lastname,
            'mobile' => $career->mobile,
            'biography' => $career->biography ?? null,
            'city' => $career->city,
            'country' => $career->country,
            'careerStatus' => $career->career_status,
            'careerRoleType' => $career->career_role_type,
        ];

        return response($data, Response::HTTP_OK);
    }


    public function applications_download(string $id)
    {
        //
        $career = CareerApplication::where('career_app_id', '=', $id)->first();

        if ($career == null) {
            return response([
                'Application not found!.'
            ], Response::HTTP_NOT_FOUND);
        }

        if ($career->cv_url == null || '') {
            return response([
                'Application cv not found!.'
            ], Response::HTTP_NOT_FOUND);
        }

        // $imgUrl = null;

        // $cvUrl = null;

        // if ($career->cv_url) {
        //     $cvUrl = asset(Storage::url($career->cv_url));
        // }

        // $data = [
        //     'careerAppId' => $career->career_app_id,
        //     'careerId' => $career->career_id,
        //     'avatarUrl' => $imgUrl,
        //     'cvUrl' => $cvUrl,
        //     'email' => $career->email,
        //     'firstname' => $career->firstname,
        //     'lastname' => $career->lastname,
        //     'mobile' => $career->mobile,
        //     'biography' => $career->biography,
        //     'city' => $career->city,
        //     'country' => $career->country,
        //     'careerStatus' => $career->career_status,
        //     'careerRoleType' => $career->career_role_type,
        // ];

        return response()->download(storage_path("app/" . $career->cv_url));
    }

    /**
     * Update the specified resource in storage.
     */
    public function applications_update(Request $request, string $appId)
    {
        //
        // $career = Career::where('career_id', '=', $id)->first();

        // if ($career == null) {
        //     return response([
        //         'Career not found!.'
        //     ], Response::HTTP_NOT_FOUND);
        // }

        $career_apps = CareerApplication::where('career_app_id', '=', $appId)->first();

        if ($career_apps == null) {
            return response([
                'Application not found!.'
            ], Response::HTTP_NOT_FOUND);
        }

        $imgUrl = null;
        $file = null;

        if ($request->hasFile('avatar')) {

            $file = $request->file('avatar');
            if (!$file->isValid()) {
                return response()->json(['invalid_file_upload'], Response::HTTP_BAD_REQUEST);
            }

            $imgUrl = Storage::putFile('public/profiles', $file);
        }


        $cvUrl = null;
        $cvFile = null;

        if ($request->hasFile('cv')) {

            $cvFile = $request->file('cv');
            if (!$cvFile->isValid()) {
                return response()->json(['invalid_file_upload'], Response::HTTP_BAD_REQUEST);
            }

            $cvUrl = Storage::putFile('public/career_assets', $cvFile);
        }

        $career_apps->career_id = $request->input('careerId');
        $career_apps->avatar_url = $imgUrl ?? $career_apps->avatar_url;
        $career_apps->cv_url = $cvUrl ?? $career_apps->cv_url;
        $career_apps->email = $request->input('email');
        $career_apps->firstname = $request->input('firstname');
        $career_apps->lastname = $request->input('lastname');
        $career_apps->mobile = $request->input('mobile');
        $career_apps->biography = $request->input('biography');
        $career_apps->city = $request->input('city');
        $career_apps->country = $request->input('country');
        $career_apps->career_status = intval($request->input('careerStatus'));
        $career_apps->career_role_type = intval($request->input('careerRoleType') ?? $career_apps->career_role_type);

        $career_apps->save();

        //If Accepted
        if (intval($career_apps->career_status) == 2) {

            $userRole = ProfileRoles::where('type', '=', intval($career_apps->career_role_type))->first();
            //Create New User Profile

            $newUser = new Profile([
                'avatar_url' => $career_apps->avatar_url ?? null,
                'firstname' => $career_apps->firstname,
                'lastname' => $career_apps->lastname,
                'position' => null,
                'mobile' => $career_apps->mobile,
                'email' => $career_apps->email,
                'hashed_password' => password_hash($career_apps->email, HASH_HMAC),
                'roleType' => $userRole->type
            ]);

            $newUser->save();
        }

        return response([], Response::HTTP_CREATED);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function applications_destroy(string $id)
    {
        //
        $careerApp = CareerApplication::where('career_app_id', '=', $id)->first();

        if ($careerApp == null) {
            return response([
                'Application not found!.'
            ], Response::HTTP_NOT_FOUND);
        }

        $careerApp->delete();

        return response([], Response::HTTP_CREATED);
    }
}
