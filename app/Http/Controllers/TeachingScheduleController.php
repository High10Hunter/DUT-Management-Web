<?php

namespace App\Http\Controllers;

use App\Models\TeachingSchedule;
use App\Http\Requests\StoreTeachingScheduleRequest;
use App\Http\Requests\UpdateTeachingScheduleRequest;

class TeachingScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreTeachingScheduleRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTeachingScheduleRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TeachingSchedule  $teachingSchedule
     * @return \Illuminate\Http\Response
     */
    public function show(TeachingSchedule $teachingSchedule)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TeachingSchedule  $teachingSchedule
     * @return \Illuminate\Http\Response
     */
    public function edit(TeachingSchedule $teachingSchedule)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateTeachingScheduleRequest  $request
     * @param  \App\Models\TeachingSchedule  $teachingSchedule
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTeachingScheduleRequest $request, TeachingSchedule $teachingSchedule)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TeachingSchedule  $teachingSchedule
     * @return \Illuminate\Http\Response
     */
    public function destroy(TeachingSchedule $teachingSchedule)
    {
        //
    }
}
