<?php

namespace App\Services;

use App\Medication;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class MedicationService{


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // create new genre
        $medication = new Medication(request()->all());

        $medication->save();

        // Return a success message to master page
        return response()->json([
            'type' => 'success',
            'text' => "The medication <b>$medication->name</b> has been added"
        ]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Medication  $medication
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Medication $medication)
    {
        // update genre
        $medication->update($request->all());
        $medication->save();

        // Return a success message to master page
        return response()->json([
            'type' => 'success',
            'text' => "The medication <b>$medication->name</b> has been update"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Medication  $medication
     * @return \Illuminate\Http\Response
     */
    public function destroy(Medication $medication)
    {
        $medication->delete();

        return response()->json([
            'type' => 'success',
            'text' => "The medication <b>$medication->name</b> has been deleted"
        ]);
    }

}
