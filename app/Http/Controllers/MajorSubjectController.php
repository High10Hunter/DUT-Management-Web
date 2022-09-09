<?php

namespace App\Http\Controllers;

use App\Models\MajorSubject;
use App\Http\Requests\StoreMajorSubjectRequest;
use App\Http\Requests\UpdateMajorSubjectRequest;

class MajorSubjectController extends Controller
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
     * @param  \App\Http\Requests\StoreMajorSubjectRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMajorSubjectRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MajorSubject  $majorSubject
     * @return \Illuminate\Http\Response
     */
    public function show(MajorSubject $majorSubject)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MajorSubject  $majorSubject
     * @return \Illuminate\Http\Response
     */
    public function edit(MajorSubject $majorSubject)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateMajorSubjectRequest  $request
     * @param  \App\Models\MajorSubject  $majorSubject
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMajorSubjectRequest $request, MajorSubject $majorSubject)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MajorSubject  $majorSubject
     * @return \Illuminate\Http\Response
     */
    public function destroy(MajorSubject $majorSubject)
    {
        //
    }
}
