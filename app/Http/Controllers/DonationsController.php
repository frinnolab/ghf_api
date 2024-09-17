<?php

namespace App\Http\Controllers;

use App\Models\Donations\Donation;
use App\Models\Donations\DonorCurrencyType;
use App\Models\Donations\DonorType;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Ramsey\Uuid\Type\Decimal;

class DonationsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index_types()
    {
        //
        $response = [];

        $data = DonorType::all();

        if ($data == null) {
            return response($response, Response::HTTP_NO_CONTENT);
        }

        //format data

        foreach ($data as $value) {
            $d = [
                "donorTypeId" => $value->donor_type_id,
                "title" => $value->title,
                "type" => $value->type
            ];

            array_push($response, $d);
        }

        return response($response, Response::HTTP_OK);
    }

    public function index_currencies()
    {
        //
        $response = [];

        $data = DonorCurrencyType::all();

        if ($data == null) {
            return response($response, Response::HTTP_NO_CONTENT);
        }

        //format data

        foreach ($data as $value) {
            $d = [
                "donorCurrencyId" => $value->donor_currency_id,
                "title" => $value->title,
                "shortname" => $value->short_name
            ];

            array_push($response, $d);
        }

        return response($response, Response::HTTP_OK);
    }


    public function index()
    {
        //
        $response = [];

        $data = Donation::all();

        if ($data == null) {
            return response($response, Response::HTTP_NO_CONTENT);
        }

        //format data

        foreach ($data as $value) {
            $d = [
                "donationId" => $value->donation_id,
                "email" => $value->email,
                "firstname" => $value->first_name,
                "lastname" => $value->last_name,
                "company" => $value->company,
                "description" => $value->description,
                "mobile" => $value->mobile,
                "amountPledged" => $value->amount_pledged,
                "donorCurrencyType" => $value->donor_currency_type,
                "donorType" => $value->donor_type
            ];

            array_push($response, $d);
        }

        return response($response, Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $response = [];
        $newData = [
            "email" => $request->input('email'),
            "first_name" => $request->input('firstname'),
            "last_name" => $request->input('lastname'),
            "company" => $request->input('company'),
            "description" => $request->input('description'),
            "mobile" => $request->input('mobile'),
            "amount_pledged" => new Decimal($request->input('amountPledged')),
            "donor_currency_type" => $request->input('donorCurrencyType'),
            "donor_type" => $request->input('donorType'),
        ];

        $data = new Donation($newData);

        $data->save();

        $response =
            [
                "donationId" => $data->donation_id
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
        $data = Donation::where('donation_id', '=', $id)->first();

        if ($data == null) {
            return response($response, Response::HTTP_NOT_FOUND);
        }

        $response = [
            "donationId" => $data->donation_id,
            "email" => $data->email,
            "firstname" => $data->first_name,
            "lastname" => $data->last_name,
            "company" => $data->company,
            "description" => $data->description,
            "mobile" => $data->mobile,
            "amountPledged" => $data->amount_pledged,
            "donorCurrencyType" => $data->donor_currency_type,
            "donorType" => $data->donor_type
        ];

        response($response, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $response = [];
        $data = Donation::where('donation_id', '=', $id)->first();

        if ($data == null) {
            return response($response, Response::HTTP_NOT_FOUND);
        }


        $data->email = $request->input('email') ?? $data->email;
        $data->first_name = $request->input('firstname') ?? $data->first_name;
        $data->last_name = $request->input('lastname') ?? $data->last_name;
        $data->company = $request->input('company') ?? $data->company;
        $data->description = $request->input('description') ?? $data->description;
        $data->mobile = $request->input('mobile') ?? $data->mobile;
        $data->amount_pledged = new Decimal($request->input('amountPledged'))  ?? $data->amount_pledged;
        $data->donor_currency_type = $request->input('donorCurrencyType') ?? $data->donor_currency_type;
        $data->donor_type = $request->input('donorType') ?? $data->donor_type;

        $data->save();

        $response = [
            "donationId" => $data->donation_id
        ];

        return response($response, Response::HTTP_CREATED);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $response = [];
        $data = Donation::where('donation_id', '=', $id)->first();

        if ($data == null) {
            return response($response, Response::HTTP_NOT_FOUND);
        }

        $data->delete();

        return response($response, Response::HTTP_CREATED);
    }
}