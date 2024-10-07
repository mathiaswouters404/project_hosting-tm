<?php

namespace App\Http\Controllers;

use App\Medication;
use App\Services\MedicationService;
use Illuminate\Http\Request;

class MedicationController extends Controller
{
    private $medicationService;
    private $sorts = [
        ["id (eerste => laatste)", "id", "asc"],
        ["id (eerste <= laatste)", "id", "desc"],
        ["Name (A => Z)", "name", "asc"],
        ["Name (A <= Z)", "name", "desc"],
    ];

    public function __construct(MedicationService $medicationService) {
        $this->medicationService = $medicationService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sorts = $this->sorts;
        $result = compact('sorts');
        return view("medication.index", $result);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return redirect('medication');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validate $request
        $this->validate($request, [
            'name' => 'required|min:3|unique:medications,name',
            'description' => 'required|min:3'
        ]);

        return $this->medicationService->store($request);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Medication  $medication
     * @return \Illuminate\Http\Response
     */
    public function show(Medication $medication)
    {
        return redirect('medication');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Medication  $medication
     * @return \Illuminate\Http\Response
     */
    public function edit(Medication $medication)
    {
        return redirect('medication');
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
        // validate $request
        $this->validate($request, [
            'name' => 'required|min:3|unique:medications,name,'.$medication->id,
            'description' => 'required|min:3'
        ]);

        return $this->medicationService->update($request, $medication);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Medication  $medication
     * @return \Illuminate\Http\Response
     */
    public function destroy(Medication $medication)
    {
        return $this->medicationService->destroy($medication);
    }

    public function query(Request $request)
    {
        $sorts = $this->sorts;
        $name = '%' . $request->input("name") . '%';
        $sort = $sorts[$request->input("sort") ?? 0];
        $medications = Medication::orderBy($sort[1], $sort[2])->where('name', 'like', $name)->withCount('medicationPatients')->get();
        return $medications;
    }
}

