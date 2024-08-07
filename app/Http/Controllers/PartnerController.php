<?php

namespace App\Http\Controllers;

use App\Models\Partners\Partner;
use App\Models\Partners\PartnerType;
use App\Models\Profiles\Profile;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

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


        $response = [];

        foreach ($partners as $partner) {
            $imgUrl = null;
            if ($partner->thumbnail_url != '' or $partner->logo_url != null) {
                $imgUrl = asset(Storage::url($partner->logo_url));
            }
            $data = [
                'partnerId' => $partner->partner_id,
                'name' => $partner->name,
                'type' => $partner->type,
                'logoUrl' => $imgUrl,
                'description' => $partner->description,
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

        // if ($creatorRoleType != -1 || $creatorRoleType != 1) {
        //     return response("", Response::HTTP_UNAUTHORIZED);
        // }


        switch ($creatorRoleType) {
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

        $partnerType = PartnerType::where('type', '=', $request->input('type'))->first();

        $path = null;
        $file = null;

        if ($request->input('image')) {

            $file = $request->file('image');
            if (!$file->isValid()) {
                return response()->json(['invalid_file_upload'], Response::HTTP_BAD_REQUEST);
            }

            $path = Storage::putFile('public/partner_assets', $file);
        }

        $newPartner = new Partner([
            'logo_url' => $path ?? '',
            'name' => $request->input('name') ?? null,
            'description' => $request->input('description') ?? null,
            'type' => $partnerType->type ?? null,
        ]);

        $newPartner->save();

        return response([
            'partnerId' => $newPartner->partner_id
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $partnerId)
    {
        //
        $partner = Partner::where('partner_id', '=', $partnerId)->first();

        if (!$partner) {
            return response([], Response::HTTP_NOT_FOUND);
        }

        $imgUrl = null;
        if ($partner->logo_url != null || $partner->logo_url != '') {

            $imgUrl = asset(Storage::url($partner->logo_url));
        }

        $response = [
            "partnerId"=>$partner->partner_id,
            "logoUrl"=>$partner->imgUrl,
            "name"=>$partner->name,
            "descriprion"=>$partner->descriprion,
            "type"=>$partner->type,
        ];

        return response($response, Response::HTTP_OK);
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
        switch ($creatorRoleType) {
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
        //
        $partner = Partner::where('partner_id', '=', $partnerId)->first();

        if (!$partner) {
            return response([], Response::HTTP_NOT_FOUND);
        }

        $partnerType = PartnerType::where('type', '=', $request->input('type'))->first();

        $path = '';
        $file = null;

        if ($request->input('image')) {

            $file = $request->file('image');
            if (!$file->isValid()) {
                return response()->json(['invalid_file_upload'], Response::HTTP_BAD_REQUEST);
            }

            $path = Storage::putFile('public/blog_assets', $file);
        }

        $partner->logo_url = $path ?? $partner->logo_url;
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
