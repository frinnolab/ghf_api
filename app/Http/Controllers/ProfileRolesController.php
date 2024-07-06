<?php

namespace App\Http\Controllers;

use App\Models\Profiles\Profile;
use App\Models\Profiles\ProfileRoles;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProfileRolesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $roles = ProfileRoles::all();

        if ($roles == null) {
            return response([], Response::HTTP_NO_CONTENT);
        }

        $response = [];

        foreach ($roles as $role) {
            # code...
            $data = [
                'roleId' => $role->profile_role_id,
                'roleName' => $role->name,
                'roleType' => $role->type,
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

        $creatorProfile = Profile::where('profile_id', '=', $request->input('profileId'))->first();

        if (!$creatorProfile) {
            return response("Profile not found!.", Response::HTTP_NOT_FOUND);
        }

        $creatorRoleType = $creatorProfile->roleType;

        if ($creatorRoleType != -1 || $creatorRoleType != 1) {
            return response("", Response::HTTP_UNAUTHORIZED);
        }

        $newRole = new ProfileRoles([
            'name' => $request->input('roleName'),
            'type' => count(ProfileRoles::all()) + 1,
        ]);

        $newRole->save();

        $response = [
            'roleId' => $newRole->profile_role_id,
        ];

        return response($response, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show($roleId)
    {
        //
        $role = ProfileRoles::where('profile_role_id', '=', $roleId)->first();

        if (!$role) {
            return response([], Response::HTTP_NOT_FOUND);
        }

        $response = [
            'roleId' => $role->profile_role_id,
            'roleName' => $role->name,
            'roleType' => $role->type,
        ];

        return response($response, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $roleId)
    {
        //
        if ($request->all() === null) {
            return response('Request cannot be empty', Response::HTTP_BAD_REQUEST);
        }

        $creatorProfile = Profile::where('profile_id', '=', $request->input('profileId'))->first();

        if (!$creatorProfile) {
            return response("Profile not found!.", Response::HTTP_NOT_FOUND);
        }

        $creatorRoleType = $creatorProfile->roleType;

        if ($creatorRoleType != -1 || $creatorRoleType != 1) {
            return response("", Response::HTTP_UNAUTHORIZED);
        }

        $role = ProfileRoles::where('profile_role_id', '=', $roleId)->first();

        if (!$role) {
            return response([], Response::HTTP_NOT_FOUND);
        }

        $role->name = $request->input('roleName');
        // $role->type = intval($request->input('roleType'));
        $role->save();

        return response([
            'roleId'=>$role->profile_role_id
        ], Response::HTTP_CREATED);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $roleId)
    {
        //
        $role = ProfileRoles::where('profile_role_id', '=', $roleId)->first();

        $role->delete();
    }
}
