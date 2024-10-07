<?php

namespace App\Services;

use App\Medication;

use App\MedicationPatient;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class PrescriptionService{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // create new prescription
        $prescription = new MedicationPatient(request()->all());
        $prescription->save();

        // Return a success message to master page
        return response()->json([
            'type' => 'success',
            'text' => "The prescription has been added",
            'prescription' => $prescription
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\MedicationPatient  $medicationPatient
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MedicationPatient $medicationPatient)
    {
        // update prescription
        $medicationPatient->update($request->all());

        $medicationPatient->save();

        // Return a success message to master page
        return response()->json([
            'type' => 'success',
            'text' => "The prescription has been update"
        ]);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MedicationPatient  $medicationPatient
     * @return \Illuminate\Http\Response
     */
    public function destroy(MedicationPatient $medicationPatient)
    {
        $medicationPatient->delete();

        return response()->json([
            'type' => 'success',
            'text' => "The prescription has been deleted"
        ]);
    }

    public function query($id)
    {
        if($id) {
            $prescription = Auth::user()->patients()->findOrFail($id)->prescriptions()->with('medication')->get();
        }
        else {
            $prescription = Auth::user()->prescriptions()->with('medication')->get();
        }

        $prescription->transform(function($item, $key) {
            $item->startDate = Carbon::parse($item->startDate)->format('Y-m-d');
            $item->endDate = $item->endDate ? Carbon::parse($item->endDate)->format('Y-m-d') : '';
            //$item->medicationName = $item->medication->name;
            unset($item->created_at, $item->updated_at);
            return $item;
        });

        return $prescription;
    }

}

