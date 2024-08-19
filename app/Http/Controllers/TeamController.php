<?php

namespace App\Http\Controllers;

use App\Models\Profiles\Profile;
use App\Models\Teams\Team;
use App\Models\Teams\TeamManager;
use App\Models\Teams\TeamMember;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $teams = Team::all();

        if (!$teams) {
            return response([], Response::HTTP_NO_CONTENT);
        }

        $response = [];

        foreach ($teams as $team) {

            $tm = TeamMember::where('team_id', '=', $team->team_id, 'AND', 'member_id', '!=', null)->get();

            $data = [
                "teamId" => $team->team_id,
                "name" => $team->name,
                "totalMembers" => count($tm) ?? 0,
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
        $admin = Profile::where('profile_id', '=', $request->input('profileId'))->first();

        if ($admin == null) {
            return response('Profile not found', Response::HTTP_NOT_FOUND);
        }

        $authRole = intval($admin->roleType);

        switch ($authRole) {
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

        //New Team
        $newTeam = new Team([
            "name" => $request->input('name') ?? ''
        ]);


        $newTeam->save();

        return response([], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $teamId)
    {
        //
        $team = Team::where('team_id', '=', $teamId)->first();

        $tm = TeamMember::where('team_id', '=', $team->team_id, 'AND', 'member_id', '!=', null)->get();

        if (!$team) {
            return response([], Response::HTTP_NOT_FOUND);
        }

        $response = [
            'teamId' => $team->team_id,
            'name' => $team->name,
            'totalMembers' => count($tm) ?? 0,
        ];

        return response($response, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        //
        $admin = Profile::where('profile_id', '=', $request->input('profileId'))->first();

        if ($admin) {
            return response([], Response::HTTP_NOT_FOUND);
        }

        $authRole = intval($admin->roleType);

        switch ($authRole) {
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

        $team = Team::where('team_id', '=', $request->input('teamId'))->first();

        if (!$team) {
            return response([], Response::HTTP_NOT_FOUND);
        }

        $team->name = $request->input('name');
        //$team->team_members = count(Team::all());

        return response([], Response::HTTP_CREATED);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $teamId)
    {
        //
        $team = Team::where('team_id', '=', $teamId)->first();

        $team->delete();

        return response([], Response::HTTP_CREATED);
    }

    //Team Members Endpoints

    public function getTeamMembers(string $teamId)
    {

        $team = Team::where('team_id', '=', $teamId)->first();

        if (!$team) {
            return response([], Response::HTTP_NOT_FOUND);
        }

        $tMemmbers = TeamMember::where('team_id', '=', $team->team_id)->get();

        if (!$tMemmbers) {
            return response([], Response::HTTP_NO_CONTENT);
        }

        $response = [];

        foreach ($tMemmbers as $tm) {
            $pf = Profile::where('profile_id', '=', $tm->member_id)->first();

            $imgUrl = asset(Storage::url($pf->avatar_url));

            $member = [
                'profileId' => $pf->profile_id,
                'avatarUrl' => $pf->$imgUrl,
                'email' => $pf->email,
                'firstname' => $pf->firstname,
                'lastname' => $pf->lastname,
                'position' => $pf->position,
                'mobile' => $pf->mobile,
                'roleType' => $pf->roleType,
            ];

            $data = [
                'teamId' => $team->team_id,
                'memberId' => $pf->profile_id,
                'member' => $member,
                'teamPosition' => $tm->team_position,
            ];

            array_push($response, $data);
        }

        return response($response, Response::HTTP_OK);
    }


    public function getTeamMember(string $teamId, string $memberId)
    {

        $team = Team::where('team_id', '=', $teamId)->first();

        $memb = Profile::where('profile_id', '=', $memberId)->first();

        if (!$team) {
            return response([], Response::HTTP_NOT_FOUND);
        }

        if (!$memb) {
            return response([], Response::HTTP_NOT_FOUND);
        }

        $tMemmbers = TeamMember::where('team_id', '=', $team->team_id, 'AND', 'member_id', '=', $memb->profile_id)->get();

        if (!$tMemmbers) {
            return response([], Response::HTTP_NO_CONTENT);
        }

        $response = [
            "Id" => $tMemmbers->team_m_id,
            "avatarUrl" => $memb->avatar_url,
            "email" => $tMemmbers->email,
            "teamId" => $tMemmbers->team_id,
            "memberId" => $tMemmbers->member_id,
            "teamPosition" => $team->team_position,
        ];


        return response($response, Response::HTTP_OK);
    }


    //Add member to team
    public function addMemberToTeam(Request $request)
    {

        $team = Team::where('team_id', '=', $request->input('teamId'))->first();

        if (!$team) {
            return response('Team not found', Response::HTTP_NOT_FOUND);
        }
        $prf = Profile::where('profile_id', '=', $request->input('memberId'))->first();

        if (!$prf) {
            return response('Profile not found', Response::HTTP_NOT_FOUND);
        }

        $tm = new TeamMember([
            "team_id" => $team->team_id,
            "member_id" => $prf->profile_id,
            "team_position" => $request->input('teamPosition'),
        ]);

        $tm->save();

        $team->total_members = +1;

        $team->update();

        return response([], Response::HTTP_CREATED);
    }

    //Update member to team
    public function updateMemberToTeam(Request $request)
    {
        $team = Team::where('team_id', '=', $request->input('teamId'))->first();

        if (!$team) {
            return response('Team not found', Response::HTTP_NOT_FOUND);
        }
        $prf = Profile::where('profile_id', '=', $request->input('memberId'))->first();

        if (!$prf) {
            return response('Profile not found', Response::HTTP_NOT_FOUND);
        }

        $tm = TeamMember::where(
            'team_id',
            '=',
            $team->team_id,
            'AND',
            'member_id',
            '=',
            $prf->profile_id
        )->first();

        if (!$tm) {
            return response([], Response::HTTP_NOT_FOUND);
        }

        $tm = new TeamMember([
            "team_id" => $team->team_id,
            "member_id" => $prf->profile_id,
            "team_position" => $request->input('teamPosition'),
        ]);

        $tm->team_position = $request->input('teamPosition') ?? $tm->team_position;

        $tm->save();

        return response([], Response::HTTP_CREATED);
    }

    //Remove member to team
    public function removeMemberToTeam(Request $request)
    {
        $team = Team::where('team_id', '=', $request->input('teamId'))->first();

        if (!$team) {
            return response('Team not found', Response::HTTP_NOT_FOUND);
        }
        $prf = Profile::where('profile_id', '=', $request->input('memberId'))->first();

        if (!$prf) {
            return response('Profile not found', Response::HTTP_NOT_FOUND);
        }

        $tm = TeamMember::where(
            'team_id',
            '=',
            $team->team_id,
            'AND',
            'member_id',
            '=',
            $prf->profile_id
        )->first();

        if (!$tm) {
            return response([], Response::HTTP_NOT_FOUND);
        }

        $tm->delete();

        return response([], Response::HTTP_CREATED);
    }
}
