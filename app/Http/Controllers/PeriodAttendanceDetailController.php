<?php

namespace App\Http\Controllers;

use App\Models\PeriodAttendanceDetail;
use App\Http\Requests\StorePeriodAttendanceDetailRequest;
use App\Http\Requests\UpdatePeriodAttendanceDetailRequest;

class PeriodAttendanceDetailController extends Controller
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
     * @param  \App\Http\Requests\StorePeriodAttendanceDetailRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePeriodAttendanceDetailRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PeriodAttendanceDetail  $periodAttendanceDetail
     * @return \Illuminate\Http\Response
     */
    public function show(PeriodAttendanceDetail $periodAttendanceDetail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PeriodAttendanceDetail  $periodAttendanceDetail
     * @return \Illuminate\Http\Response
     */
    public function edit(PeriodAttendanceDetail $periodAttendanceDetail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePeriodAttendanceDetailRequest  $request
     * @param  \App\Models\PeriodAttendanceDetail  $periodAttendanceDetail
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePeriodAttendanceDetailRequest $request, PeriodAttendanceDetail $periodAttendanceDetail)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PeriodAttendanceDetail  $periodAttendanceDetail
     * @return \Illuminate\Http\Response
     */
    public function destroy(PeriodAttendanceDetail $periodAttendanceDetail)
    {
        //
    }
}
