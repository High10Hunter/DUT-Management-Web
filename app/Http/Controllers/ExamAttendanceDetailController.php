<?php

namespace App\Http\Controllers;

use App\Models\ExamAttendanceDetail;
use App\Http\Requests\StoreExamAttendanceDetailRequest;
use App\Http\Requests\UpdateExamAttendanceDetailRequest;

class ExamAttendanceDetailController extends Controller
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
     * @param  \App\Http\Requests\StoreExamAttendanceDetailRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreExamAttendanceDetailRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ExamAttendanceDetail  $examAttendanceDetail
     * @return \Illuminate\Http\Response
     */
    public function show(ExamAttendanceDetail $examAttendanceDetail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ExamAttendanceDetail  $examAttendanceDetail
     * @return \Illuminate\Http\Response
     */
    public function edit(ExamAttendanceDetail $examAttendanceDetail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateExamAttendanceDetailRequest  $request
     * @param  \App\Models\ExamAttendanceDetail  $examAttendanceDetail
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateExamAttendanceDetailRequest $request, ExamAttendanceDetail $examAttendanceDetail)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ExamAttendanceDetail  $examAttendanceDetail
     * @return \Illuminate\Http\Response
     */
    public function destroy(ExamAttendanceDetail $examAttendanceDetail)
    {
        //
    }
}
