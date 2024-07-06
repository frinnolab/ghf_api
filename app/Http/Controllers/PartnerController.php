<?php

namespace App\Http\Controllers;

use App\Models\Partners\Partner;
use App\Models\Partners\PartnerType;
use App\Models\Profiles\Profile;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PartnerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $partners = Partner::all();


        if ($partners == null) {
            return response([], Response::HTTP_NO_CONTENT);
        }
        $response = [$partners];

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

        $partnerType = PartnerType::where('type','=', $request->input('type'))->first();
        
        $newPartner = new Partner([
            'logo_url'=>'',
            'name'=>$request->input('name'),
            'description'=>$request->input('description'),
            'type'=>$partnerType->type,
        ]);

        $newPartner->save();

        return response([
            'partnerId'=>$newPartner->partner_id
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $partnerId)
    {
        //
        $partner = Partner::where('partner_id','=', $partnerId)->first();

        if(!$partner){
            return response([], Response::HTTP_NOT_FOUND);
        }

        return response([$partner], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $partnerId)
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
        //
        $partner = Partner::where('partner_id', '=', $partnerId)->first();

        if (!$partner) {
            return response([], Response::HTTP_NOT_FOUND);
        }

        $partnerType = PartnerType::where('type','=', $request->input('type'))->first();

        $partner->logo_url = '' ?? $partner->logo_url;
        $partner->name = $request->input('name') ?? $partner->name;
        $partner->description = $request->input('description') ?? $partner->description;
        $partner->type = $partnerType->type ?? $partner->type;
        
        $partner->save();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $partnerId)
    {
        //
        $partner = Partner::where('partner_id', '=', $partnerId)->first();

        if (!$partner) {
            return response([], Response::HTTP_NOT_FOUND);
        }

        $partner->delete();
    }
}
