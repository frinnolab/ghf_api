<?php

namespace App\Http\Controllers;

use App\Models\Profiles\Profile;
use App\Models\Teams\Team;
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
        $teams = Team::latest()->get();

        if ($teams == null) {
            return response([], Response::HTTP_NO_CONTENT);
        }

        $response = [];

        foreach ($teams as $team) {

            $tm = TeamMember::where('team_id', '=', $team->team_id, 'AND', 'member_id', '!=', null)->latest()->get();

            $data = [
                "teamId" => $team->team_id,
                "name" => $team->name,
                "isMainBoard" => $team->is_main_board,
                "isTeamMember" => $team->is_team_member,
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
            return response('Admin profile not found!.', Response::HTTP_NOT_FOUND);
        }

        $authRole = intval($admin->roleType);

        switch ($authRole) {
            case -1:
                break;
            case 1:
                break;
            default:
                return response("You're not Authorised to perform!.", Response::HTTP_UNAUTHORIZED);
        }

        //New Team
        $newTeam = new Team([
            "name" => $request->input('name'),
            "is_main_board" => $request->input('isMainBoard'),
        ]);


        $newTeam->save();

        return response([], Response::HTTP_CREATED);
    }

    /**
     * Display MainBoard Team specified resource.
     */

    public function getMainBoardTeam()
    {
        //Public facing endponit

        $team = Team::where('is_main_board', '=', true)->first();

        if (!$team) {
            return response([], Response::HTTP_NO_CONTENT);
        }
        $tm = TeamMember::where('team_id', '=', $team->team_id, 'AND', 'member_id', '!=', null)->get();

        if (!$team) {
            return response([], Response::HTTP_NOT_FOUND);
        }

        $response = [
            'teamId' => $team->team_id,
            'name' => $team->name,
            "isMainBoard" => boolval($team->is_main_board),
            'totalMembers' => count($tm) ?? 0,
        ];


        return response($response, Response::HTTP_OK);
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
            // 'isTeamMember' => $team->is_team_member,
            "isMainBoard" => boolval($team->is_main_board),
            'totalMembers' => count($tm) ?? 0,
        ];

        return response($response, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $teamId)
    {

        $team = Team::where('team_id', '=', $request->input('teamId'))->first();

        if ($team == null) {
            return response(["Team not found"], Response::HTTP_NOT_FOUND);
        }

        $team->name = $request->input('name') ?? $team->name;
        $team->is_main_board = $request->input('isMainBoard');
        // $team->is_team_member = $request->input('isTeamMember');

        $team->save();
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

    public function getMainTeamMembers()
    {
        //Public facing endponit

        // $team = Team::where('is_main_board', '=', true)->first();

        // if (!$team) {
        //     return response([], Response::HTTP_NO_CONTENT);
        // }


        $tMemmbers = TeamMember::where('is_team_member', '=', true)->latest()->get()->unique('member_id');

        if ($tMemmbers == null) {
            return response([], Response::HTTP_NO_CONTENT);
        }

        $response = [];

        foreach ($tMemmbers as $tm) {
            $pf = Profile::where('profile_id', '=', $tm->member_id)->first();

            if ($pf === null) {
                continue;
            }

            $imgUrl = null;

            if ($pf->avatar_url !== null) {
                $imgUrl = asset(Storage::url($pf->avatar_url));
            }


            $member = [
                'profileId' => $pf->profile_id,
                'avatarUrl' => $pf->$imgUrl ?? null,
                'email' => $pf->email,
                'firstname' => $pf->firstname,
                'lastname' => $pf->lastname,
                'position' => $pf->position,
                'mobile' => $pf->mobile,
                'roleType' => $pf->roleType,
            ];

            $data = [
                'teamId' => $tm->team_id,
                'memberId' => $pf->profile_id,
                'memberAvatarUrl' => $imgUrl,
                'isTeamMember' => $tm->is_team_member,
                'member' => $member,
                'teamPosition' => $tm->team_position,
            ];

            array_push($response, $data);
        }

        return response($response, Response::HTTP_OK);
    }

    public function getMainBoardTeamMembers()
    {
        //Public facing endponit

        $team = Team::where('is_main_board', '=', true)->first();

        if (!$team) {
            return response([], Response::HTTP_NO_CONTENT);
        }


        $tMemmbers = TeamMember::where('team_id', '=', $team->team_id)->latest()->get();

        if (!$tMemmbers) {
            return response([], Response::HTTP_NO_CONTENT);
        }

        $response = [];
        $imgUrl = null;


        foreach ($tMemmbers as $tm) {
            $pf = Profile::where('profile_id', '=', $tm->member_id)->first();

            if ($pf->avatar_url) {

                $imgUrl = asset(Storage::url($pf->avatar_url));
            }


            $member = [
                'profileId' => $pf->profile_id,
                'avatarUrl' => $pf->$imgUrl,
                'email' => $pf->email,
                'firstname' => $pf->firstname,
                'lastname' => $pf->lastname,
                'position' => $pf->position,
                'mobile' => $pf->mobile,
                'biography' => $pf->biography,
                'roleType' => $pf->roleType,
            ];

            $data = [
                'teamId' => $team->team_id,
                'memberId' => $pf->profile_id,
                'memberAvatarUrl' => $imgUrl,
                'member' => $member,
                'teamPosition' => $tm->team_position,
            ];

            array_push($response, $data);
        }

        return response($response, Response::HTTP_OK);
    }


    public function getTeamMembers(string $teamId)
    {

        $team = Team::where('team_id', '=', $teamId)->first();

        if ($team == null) {
            return response([
                "Team not found!."
            ], Response::HTTP_NOT_FOUND);
        }

        $tMemmbers = TeamMember::where('team_id', '=', $team->team_id)->get();

        if ($tMemmbers == null) {
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
                'teamMemberId' => $tm->team_m_id,
                'teamId' => $team->team_id,
                'memberId' => $pf->profile_id,
                'isTeamMember' => $tm->is_team_member,
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
            'isTeamMember' => $tMemmbers->is_team_member,
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

        $tmr = TeamMember::where('team_id', '=', $team->team_id)->where('member_id', '=', $prf->profile_id)->first();


        if ($tmr != null) {
            return response(['Profile already exists'], Response::HTTP_FOUND);
        }

        $tm = new TeamMember([
            "team_id" => $team->team_id,
            "member_id" => $prf->profile_id,
            "team_position" => $request->input('teamPosition'),
            "is_team_member" => boolval($request->input('isTeamMember')),
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

        if ($team == null) {
            return response('Team not found', Response::HTTP_NOT_FOUND);
        }
        $prf = Profile::where('profile_id', '=', $request->input('memberId'))->first();

        if ($prf == null) {
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

        if ($tm == null) {
            return response('Team member not found', Response::HTTP_NOT_FOUND);
        }

        $tm->team_id = $team->team_id;
        $tm->member_id = $prf->profile_id;
        $tm->is_team_member = boolval($request->input('isTeamMember')) ?? false;
        $tm->team_position = $request->input('teamPosition') ?? $tm->team_position;

        $tm->save();

        return response([], Response::HTTP_CREATED);
    }

    //Remove member to team
    public function removeMemberToTeam(string $teamId, string $memberId)
    {
        $team = Team::where('team_id', '=', $teamId)->first();

        if (!$team) {
            return response('Team not found', Response::HTTP_NOT_FOUND);
        }

        $prf = Profile::where('profile_id', '=', $memberId)->first();

        if (!$prf) {
            return response('Profile not found', Response::HTTP_NOT_FOUND);
        }

        $tm = TeamMember::where('team_id', '=', $team->team_id)->where('member_id', '=', $prf->profile_id)->first();

        if (!$tm) {
            return response([], Response::HTTP_NOT_FOUND);
        }

        $tm->delete();

        $team->total_members = -1;

        $team->update();

        return response([], Response::HTTP_CREATED);
    }
}
