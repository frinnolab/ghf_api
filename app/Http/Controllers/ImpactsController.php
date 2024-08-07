<?php

namespace App\Http\Controllers;

use App\Models\Impacts\Impact;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ImpactsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $response =  [];

        $datas = Impact::all();

        if ($datas == null) {
            return response($response, Response::HTTP_NO_CONTENT);
        }


        foreach ($datas as $data) {
            $dataRes = [
                "impactId" => $data->impact_id,
                "title" => $data->title,
                "description" => $data->description,
                "schoolName" => $data->school_name,
                "schoolRegion" => $data->school_region,
                "schoolDistrict" => $data->school_district,
                "studentGirls" => $data->student_girls,
                "studentBoys" => $data->student_boys,
                "studentsTotal" => $data->student_total,
            ];

            array_push($response, $dataRes);
        }

        return response($response);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate(['title' => ['required']]);

        $data = new Impact([
            "title" => $request->input('title'),
            "description" => $request->input('description'),
            "school_name" => $request->input('schoolName'),
            "school_region" => $request->input('schoolRegion'),
            "school_district" => $request->input('schoolDistrict'),
            "student_boys" => intval($request->input('studentBoys') ?? 0),
            "student_girls" => intval($request->input('studentGirls') ?? 0),
        ]);

        $data->student_total =
            $data->student_boys + $data->student_girls;

        $data->save();



        $response = [
            "impactId" => $data->impact_id
        ];

        return response($response, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //

        $data = Impact::where('impact_id', '=', $id)->first();

        if ($data == null) {
            return response([], Response::HTTP_NOT_FOUND);
        }


        $response = [
            "impactId" => $data->impact_id,
            "title" => $data->title,
            "description" => $data->description,
            "schoolName" => $data->school_name,
            "schoolRegion" => $data->school_region,
            "schoolDistrict" => $data->school_district,
            "studentGirls" => $data->student_girls,
            "studentBoys" => $data->student_boys,
            "studentsTotal" => $data->student_total,
        ];

        return response($response);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $data = Impact::where('impact_id', '=', $id)->first();

        if ($data == null) {
            return response([], Response::HTTP_NOT_FOUND);
        }

        $data->title = $request->input('title') ?? $data->title;
        $data->description = $request->input('description') ?? $data->description;
        $data->school_name = $request->input('schoolName') ?? $data->school_name;
        $data->school_region = $request->input('schoolRegion') ?? $data->school_region;
        $data->school_district = $request->input('schoolDistrict') ?? $data->school_district;
        $data->student_boys = intval($request->input('studentBoys')) ?? $data->student_boys;
        $data->student_girls = intval($request->input('studentGirls')) ?? $data->student_girls;
        $data->student_total = intval($data->student_boys + $data->student_girls);

        $data->update();

        $response = [
            "impactId" => $data->impact_id
        ];

        return response($response, Response::HTTP_CREATED);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $data = Impact::where('impact_id', '=', $id)->first();

        if ($data == null) {
            return response([], Response::HTTP_NOT_FOUND);
        }

        $data->delete();

        return response([], Response::HTTP_CREATED);
    }

    //Impact Assets
}
